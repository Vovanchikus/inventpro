<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\OperationType;
use DB;
use Exception;
use Input;
use Log;

class AddOperation extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Добавление операции',
            'description' => 'Создание новой операции с товарами и документами'
        ];
    }

    protected function firstError($field, $message)
    {
        return [
            'validationErrors' => [
                ['field' => $field, 'message' => $message]
            ],
            'toast' => [
                'message' => 'Проверьте выделенное поле!',
                'type' => 'error',
                'timeout' => 5000,
                'position' => 'top-center'
            ]
        ];
    }

    public function onSearchProductsByInv()
    {
        $q = trim(post('query', ''));

        if (!$q) return ['products' => []];

        // Ищем товары, у которых inv_number начинается с $q
        $product = Product::where('inv_number', 'LIKE', "$q%")
            ->orderBy('inv_number')
            ->first(['name', 'inv_number', 'unit', 'price']); // <- только первый результат

        return ['products' => $product ? [$product] : []];
    }

    public function onAddOperation()
    {

        $user = \Auth::getUser();

        // 🔐 Проверка прав: только admin
        if (!$user || !$user->isInGroup('admin')) {
            throw new \ApplicationException('У вас нет прав на создание операций!');
        }

        $data = post();

        // --- Тип операции ---
        if (empty($data['type_id'])) {
            return $this->firstError('type_id', 'Не указан тип операции');
        }

        // ⬇⬇⬇ ВАЖНО: определяем тип ДО использования
        $opType = OperationType::find($data['type_id']);
        $operationTypeName = mb_strtolower(trim($opType->name ?? ''));

        // Контрагент обязателен НЕ для всех операций
        if (!in_array($operationTypeName, ['списание'])) {
            if (empty($data['counteragent'])) {
                return $this->firstError('counteragent', 'Не указан контрагент');
            }
        }

        // --- Документы ---
        if (empty($data['doc_name']) || !is_array($data['doc_name'])) {
            return $this->firstError('doc_name', 'Не добавлены документы');
        }
        foreach ($data['doc_name'] as $i => $docName) {
            if (!$docName) return $this->firstError("doc_name[$i]", 'Обязательное поле');
            if (empty($data['doc_num'][$i])) return $this->firstError("doc_num[$i]", 'Номера нет');
            if (empty($data['doc_date'][$i])) return $this->firstError("doc_date[$i]", 'Укажите дату');
        }

        // --- Товары обязательно ---
        if (empty($data['name']) || !is_array($data['name'])) {
            return $this->firstError('name', 'Не добавлены товары');
        }

        $errors = [];

        // Тип операции
        try {
            $opType = OperationType::find($data['type_id']);
            $operationTypeName = mb_strtolower(trim($opType->name ?? ''));
        } catch (Exception $e) {
            $operationTypeName = '';
        }

        // ======================================
        // 🔥 Проверка на дубликаты товаров
        // ======================================
        $usedInvNumbers = [];

        foreach ($data['name'] as $i => $name) {

            $inv_number   = $data['inv_number'][$i] ?? null;

            if ($inv_number) {
                if (in_array($inv_number, $usedInvNumbers)) {

                    return [
                        'validationErrors' => [
                            [
                                'field' => "inv_number[$i]",
                                'message' => "Этот товар уже добавлен в операцию"
                            ]
                        ],
                        'toast' => [
                            'message' => "Нельзя добавить один и тот же товар дважды!",
                            'type' => 'error',
                            'timeout' => 5000,
                            'position' => 'top-center'
                        ]
                    ];
                }

                $usedInvNumbers[] = $inv_number;
            }
        }

        // ======================================
        // 🔥 Основная валидация каждого товара
        // ======================================
        foreach ($data['name'] as $i => $name) {

            $inv_number   = $data['inv_number'][$i] ?? null;
            $unit         = $data['unit'][$i] ?? null;
            $price_raw    = $data['price'][$i] ?? null;
            $quantity_raw = $data['quantity'][$i] ?? null;
            $sum_raw      = $data['sum'][$i] ?? null;

            if (!$name)       $errors[] = ["field" => "name[$i]", "message" => "Обязательное поле"];
            if (!$inv_number) $errors[] = ["field" => "inv_number[$i]", "message" => "Обязательное поле"];
            if (!$unit)       $errors[] = ["field" => "unit[$i]", "message" => "Не указано"];

            if (!is_numeric($price_raw) || floatval($price_raw) <= 0)
                $errors[] = ["field" => "price[$i]", "message" => "Должно быть больше 0"];
            if (!is_numeric($quantity_raw) || floatval($quantity_raw) <= 0)
                $errors[] = ["field" => "quantity[$i]", "message" => "Должно быть больше 0"];
            if (!is_numeric($sum_raw) || floatval($sum_raw) <= 0)
                $errors[] = ["field" => "sum[$i]", "message" => "Должно быть больше 0"];

            // --- Проверка остатка ---
            if (in_array($operationTypeName, ['расход', 'передача'])) {

                $product = Product::where('inv_number', $inv_number)->first();
                $currentQty = $product->calculated_quantity ?? 0;

                if (is_numeric($quantity_raw) && floatval($quantity_raw) > $currentQty) {

                    $errors[] = ["field" => "quantity[$i]", "message" => "Слишком много"];

                    return [
                        'validationErrors' => $errors,
                        'toast' => [
                            'message' =>
                                "<b>Ошибка!</b><br>Нельзя передать <b>{$quantity_raw}</b> ед. товара \"<b>{$name}</b>\",
                                 на складе всего <b>{$currentQty}</b>",
                            'type' => 'error',
                            'timeout' => 6000,
                            'position' => 'top-center'
                        ]
                    ];
                }
            }
        }

        if (!empty($errors)) {
            return [
                'validationErrors' => $errors,
                'toast' => [
                    'message' => 'Исправьте ошибки перед сохранением!',
                    'type' => 'error',
                    'timeout' => 6000,
                    'position' => 'top-center'
                ]
            ];
        }

        // ================================
        // СОХРАНЕНИЕ ОПЕРАЦИИ
        // ================================
        try {
            DB::beginTransaction();

            $operation = new Operation();
            $operation->type_id = $data['type_id'];
            $operation->save();

            $operationCounteragent = $data['counteragent'] ?? null;

            $files = Input::file('doc_file'); // может быть UploadedFile или массив UploadedFile

            // Приводим к массиву, чтобы всегда можно было использовать foreach
            if ($files && !is_array($files)) {
                $files = [$files];
                foreach ($files as $f) {
                    \Log::info('Получен файл: ' . $f->getClientOriginalName());
                }
            } else {
                \Log::warning('Файлы не пришли');
            }

            foreach ($data['doc_name'] as $i => $docName) {
                $uploadedFile = $files[$i] ?? null;

                $document = $operation->documents()->create([
                    'doc_name' => $docName,
                    'doc_num'  => $data['doc_num'][$i] ?? '',
                    'doc_purpose' => $data['doc_purpose'][$i] ?? null,
                    'doc_date' => $data['doc_date'][$i] ?? null,
                ]);

                if ($uploadedFile) {
                    $document->doc_file = $uploadedFile; // attachOne автоматически сохранит файл
                    $document->save();
                }
            }


            // Товары
            foreach ($data['name'] as $i => $name) {

                $inv_number = $data['inv_number'][$i];
                $unit       = $data['unit'][$i];
                $price      = floatval($data['price'][$i]);
                $quantity   = floatval($data['quantity'][$i]);

                $product = Product::firstOrCreate(
                    ['inv_number' => $inv_number],
                    ['name' => $name, 'unit' => $unit, 'price' => $price]
                );

                $pivotSum = round($quantity * $price, 2);

                $operation->products()->attach($product->id, [
                    'quantity'     => $quantity,
                    'sum'          => $pivotSum,
                    'counteragent' => $operationCounteragent
                ]);
            }

            DB::commit();

            return [
                'toast' => [
                    'message' => 'Операция успешно добавлена',
                    'type' => 'success',
                    'timeout' => 4000,
                    'position' => 'top-center'
                ]
            ];

        } catch (Exception $e) {

            DB::rollBack();
            return [
                'toast' => [
                    'message' => 'Ошибка при сохранении операции: ' . $e->getMessage(),
                    'type' => 'error',
                    'timeout' => 7000,
                    'position' => 'top-center'
                ]
            ];
        }
    }


    // ---- Поиск товаров ----
    public function onSearchProducts()
    {
        $query = trim(post('query', ''));
        if (!$query) return ['results' => []];

        $q = mb_strtolower($query);

        $products = Product::whereRaw('LOWER(name) LIKE ?', ["%{$q}%"])
            ->orWhereRaw('LOWER(inv_number) LIKE ?', ["%{$q}%"])
            ->limit(20)
            ->get(['id','name','inv_number','unit','price']);

        return [
            'results' => $products->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'inv_number' => $p->inv_number,
                    'unit' => $p->unit,
                    'price' => $p->price,
                    'calculated_quantity' => $p->calculated_quantity ?? 0,
                    'calculated_sum' => $p->calculated_sum ?? 0,
                ];
            })
        ];
    }

    public function onShowProductSearchModal()
    {
        $html = $this->renderPartial('modals/modal_product_list');

        return [
            'modalContent' => $html,
            'modalType' => 'info',
            'modalTitle' => 'Выберите товар'
        ];
    }
}
