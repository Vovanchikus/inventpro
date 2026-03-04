<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;
use DB;
use Exception;
use Input;
use Carbon\Carbon;
use Log;
use Samvol\Inventory\Classes\OrganizationAccess;

class EditOperation extends ComponentBase
{
    public $operation;
    public $operationId;
    public $products = [];

    public function componentDetails()
    {
        return [
            'name' => 'Редактирование операции',
            'description' => 'Редактирование существующей операции с товарами и документами'
        ];
    }

    public function defineProperties()
    {
        return [
            'operation_id' => [
                'title' => 'ID операции',
                'type' => 'string',
                'default' => '{{ :id }}'
            ]
        ];
    }

    protected function normalizeNumericInput($value)
    {
        if ($value === null) {
            return null;
        }

        $normalized = trim((string) $value);
        $normalized = str_replace(["\xC2\xA0", ' '], '', $normalized);
        $normalized = str_replace(',', '.', $normalized);

        return $normalized;
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

    public function onRun()
    {
        $this->operationId = $this->property('operation_id');

        if ($this->operationId) {
            // Если передан ID через URL
            $this->operation = Operation::with(['products', 'documents'])->find($this->operationId);
            $this->products = $this->operation ? $this->operation->products : [];
        } else {
            // Если ID нет — пробуем взять из localStorage через JS
            $this->operation = null;
            $this->products = []; // пустой массив для JS, будет заполнен через localStorage
        }

        $this->page['operation'] = $this->operation;
        $this->page['products'] = $this->products;
    }

    public function onEditOperation()
    {
        $user = $this->resolveCurrentUser();

        if (!$this->hasAdminAccess($user)) {
            Log::warning('[samvol] onEditOperation denied', [
                'user_present' => (bool)$user,
                'user_class' => $user ? get_class($user) : null,
                'user_id' => $user->id ?? null,
                'login' => $user->login ?? null,
                'email' => $user->email ?? null,
            ]);
            throw new \ApplicationException('У вас нет прав на создание операций!');
        }

        $data = post();
        $isCreating = empty($data['operation_id']);
        $noteId = $data['note_id'] ?? null;

        // --- Валидация типа и контрагента ---
        if (empty($data['type_id'])) return $this->firstError('type_id', 'Не указан тип операции');
        if (empty($data['counteragent'])) return $this->firstError('counteragent', 'Не указан контрагент');

        // --- Валидация документов ---
        // На странице редактирования документы и PDF обязательны.
        $files = Input::file('doc_file');
        if ($files && !is_array($files)) {
            $files = [$files];
        }

        $hasFiles = false;
        if (is_array($files) && count(array_filter($files))) {
            $hasFiles = true;
        } elseif ($files) {
            $hasFiles = true;
        }

        // Проверяем поля документов — все обязательны
        if (!empty($data['doc_name']) && is_array($data['doc_name'])) {
            foreach ($data['doc_name'] as $i => $docName) {
                if (empty($docName)) return $this->firstError("doc_name[$i]", 'Наименование документа обязательно');
                if (empty($data['doc_num'][$i])) return $this->firstError("doc_num[$i]", 'Номер документа обязателен');
                if (empty($data['doc_purpose'][$i])) return $this->firstError("doc_purpose[$i]", 'Укажите мету документа');
                if (empty($data['doc_date'][$i])) return $this->firstError("doc_date[$i]", 'Укажите дату документа');
                $file = $files[$i] ?? null;
                if (!$file) return $this->firstError("doc_file[$i]", 'Прикрепите PDF-документ');
            }
        }

        if (!$hasFiles) {
            return $this->firstError('doc_file', 'Прикрепите PDF-документ');
        }

        // --- Валидация товаров ---
        if (empty($data['name']) || !is_array($data['name'])) {
            return $this->firstError('name', 'Не добавлены товары');
        }

        $errors = [];

        // Тип операции для проверки остатков
        try {
            $opType = \Samvol\Inventory\Models\OperationType::find($data['type_id']);
            $operationTypeName = mb_strtolower(trim($opType->name ?? ''));
        } catch (\Exception $e) {
            $operationTypeName = '';
        }

        // Проверка дубликатов
        $usedInvNumbers = [];
        foreach ($data['inv_number'] as $i => $inv_number) {
            if (!$inv_number) continue;
            if (in_array($inv_number, $usedInvNumbers)) {
                return [
                    'validationErrors' => [
                        ['field' => "inv_number[$i]", 'message' => "Этот товар уже добавлен в операцию"]
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

        // Проверка каждого товара
        foreach ($data['name'] as $i => $name) {
            $inv_number   = $data['inv_number'][$i] ?? null;
            $unit         = $data['unit'][$i] ?? null;
            $price_raw    = $this->normalizeNumericInput($data['price'][$i] ?? null);
            $quantity_raw = $this->normalizeNumericInput($data['quantity'][$i] ?? null);
            $sum_raw      = $this->normalizeNumericInput($data['sum'][$i] ?? null);

            if (!$name)       $errors[] = ["field" => "name[$i]", "message" => "Обязательное поле"];
            if (!$inv_number) $errors[] = ["field" => "inv_number[$i]", "message" => "Обязательное поле"];
            if (!$unit)       $errors[] = ["field" => "unit[$i]", "message" => "Не указано"];
            if (!is_numeric($price_raw) || floatval($price_raw) <= 0)
                $errors[] = ["field" => "price[$i]", "message" => "Должно быть больше 0"];
            if (!is_numeric($quantity_raw) || floatval($quantity_raw) <= 0)
                $errors[] = ["field" => "quantity[$i]", "message" => "Должно быть больше 0"];
            if (!is_numeric($sum_raw) || floatval($sum_raw) <= 0)
                $errors[] = ["field" => "sum[$i]", "message" => "Должно быть больше 0"];

            // Проверка остатков для расхода/передачи/списания
            if (in_array($operationTypeName, ['расход', 'передача', 'списание'])) {
                $product = Product::where('inv_number', $inv_number)->first();
                $currentQty = $product->calculated_quantity ?? 0;

                if (is_numeric($quantity_raw) && floatval($quantity_raw) > $currentQty) {
                    $actionVerb = $operationTypeName === 'передача' ? 'передать' : 'списать';
                    $errors[] = ["field" => "quantity[$i]", "message" => "Слишком много"];
                    return [
                        'validationErrors' => $errors,
                        'toast' => [
                            'message' => "<b>Ошибка!</b><br>Нельзя {$actionVerb} <b>{$quantity_raw}</b> ед. товара \"<b>{$name}</b>\", на складе всего <b>{$currentQty}</b>",
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
        // Сохранение операции
        // ================================
        try {
            DB::beginTransaction();

            $operation = !empty($data['operation_id'])
                ? Operation::find($data['operation_id'])
                : new Operation();


            // Решим draft/финальная по наличию файлов и по noteId
            $files = Input::file('doc_file');
            if ($files && !is_array($files)) $files = [$files];

            $hasFiles = false;
            if (is_array($files) && count(array_filter($files))) {
                $hasFiles = true;
            } elseif ($files) {
                $hasFiles = true;
            }

            // Если нет файлов и операция привязана к заметке — считаем черновой
            $operation->is_draft = (!$hasFiles && !empty($noteId));
            $operation->is_posted = $hasFiles ? true : false;

            if ($operation->is_draft && empty($operation->prepared_at)) {
                $operation->prepared_at = Carbon::now();
            }
            if ($operation->is_posted && empty($operation->completed_at)) {
                $operation->completed_at = Carbon::now();
            }

            // Связываем с заметкой (если передана)
            $operation->note_id = $noteId ?? null;

            $operation->type_id = $data['type_id'];
            $operation->save();

            $operationCounteragent = $data['counteragent'] ?? null;

            // Удаляем старые документы
            $operation->documents()->delete();

            foreach ($data['doc_name'] as $i => $docName) {
                $uploadedFile = $files[$i] ?? null;
                $document = $operation->documents()->create([
                    'doc_name' => $docName,
                    'doc_num'  => $data['doc_num'][$i] ?? '',
                    'doc_purpose' => $data['doc_purpose'][$i] ?? null,
                    'doc_date' => $data['doc_date'][$i] ?? null,
                ]);

                if ($uploadedFile) {
                    $document->doc_file = $uploadedFile;
                    $document->save();
                }
            }

            // Привязка товаров — только для финальной операции. Для черновика не трогаем pivot,
            // чтобы не ломать историю операций.
            if (!$operation->is_draft) {
                foreach ($data['name'] as $i => $name) {
                    $inv_number = $data['inv_number'][$i];
                    $unit       = $data['unit'][$i];
                    $price      = floatval($this->normalizeNumericInput($data['price'][$i] ?? null));
                    $quantity   = floatval($this->normalizeNumericInput($data['quantity'][$i] ?? null));

                    $product = Product::firstOrCreate(
                        ['inv_number' => $inv_number],
                        ['name' => $name, 'unit' => $unit, 'price' => $price]
                    );

                    $pivotSum = round($quantity * $price, 2);

                    // ========================
                    // ЧАСТИЧНЫЙ ПЕРЕНОС ТОВАРА
                    // ========================

                    // Ищем товар в старой операции
                    $oldPivot = DB::table('samvol_inventory_operation_products')
                        ->where('product_id', $product->id)
                        ->where('operation_id', '!=', $operation->id)
                        ->first();

                    if ($oldPivot) {
                        $oldQty = floatval($oldPivot->quantity);
                        $newQty = floatval($quantity);

                        // Остаток в старой операции
                        $diff = $oldQty - $newQty;

                        if ($diff > 0) {
                            // 🔹 Переносим часть → уменьшаем количество в старой операции
                            DB::table('samvol_inventory_operation_products')
                                ->where('product_id', $product->id)
                                ->where('operation_id', $oldPivot->operation_id)
                                ->update([
                                    'quantity' => $diff,
                                    'sum'      => round($diff * $price, 2),
                                ]);

                        } else {
                            // 🔹 Переносим всё → удаляем старую запись
                            DB::table('samvol_inventory_operation_products')
                                ->where('product_id', $product->id)
                                ->where('operation_id', $oldPivot->operation_id)
                                ->delete();
                        }
                    }

                    // Добавляем товар в текущую операцию (новая запись или обновление)
                    $operation->products()->syncWithoutDetaching([
                        $product->id => [
                            'quantity' => $quantity,
                            'sum' => $pivotSum,
                            'counteragent' => $operationCounteragent
                        ]
                    ]);
                }
            } else {
                // Для черновой операции — сохраним текущий выбор товаров в заметке (если есть),
                // вместо записи в pivot
                if (!empty($noteId) && !empty($data['name']) && is_array($data['name'])) {
                    $note = \Samvol\Inventory\Models\Note::find($noteId);
                    if ($note) {
                        $tmp = [];
                        foreach ($data['name'] as $i => $name) {
                            $inv = $data['inv_number'][$i] ?? null;
                            if (!$inv) continue;
                            $tmp[] = [
                                'inv_number' => $inv,
                                'name' => $name,
                                'unit' => $data['unit'][$i] ?? null,
                                'price' => isset($data['price'][$i]) ? floatval($this->normalizeNumericInput($data['price'][$i])) : null,
                                'quantity' => isset($data['quantity'][$i]) ? floatval($this->normalizeNumericInput($data['quantity'][$i])) : 0,
                            ];
                        }
                        $note->products = $tmp;
                        $note->save();
                    }
                }
            }
            // После успешного сохранения операций и документов — пересчитываем статус заметки
            if ($operation->note) {
                $operation->note->recalcStatus();
            }

            DB::commit();

            return [
                'toast' => [
                    'message' => 'Операция успешно обновлена',
                    'type' => 'success',
                    'timeout' => 4000,
                    'position' => 'top-center'
                ],
                'operationId' => (int)$operation->id,
                'showGenerateDocModal' => true,
                'operationTypeName' => (string)($operation->type->name ?? $operationTypeName ?? ''),
            ];

        } catch (Exception $e) {
            DB::rollBack();
            return [
                'toast' => [
                    'message' => 'Ошибка при обновлении операции: ' . $e->getMessage(),
                    'type' => 'error',
                    'timeout' => 7000,
                    'position' => 'top-center'
                ]
            ];
        }
    }

    public function onShowDocGenerationModal()
    {
        $operationId = (int)post('operation_id', 0);
        $kind = mb_strtolower(trim((string)post('kind', 'transfer')));
        if (!in_array($kind, ['transfer', 'writeoff'], true)) {
            $kind = 'transfer';
        }

        $writeoffSubtype = mb_strtolower(trim((string)post('writeoff_subtype', 'autoparts')));
        if (!in_array($writeoffSubtype, ['materials', 'autoparts'], true)) {
            $writeoffSubtype = 'autoparts';
        }

        $documents = [];
        if ($operationId > 0) {
            $operation = Operation::with(['documents'])->find($operationId);
            if ($operation && $operation->documents) {
                foreach ($operation->documents as $document) {
                    $docName = trim((string)($document->doc_name ?? ''));
                    if ($docName !== '') {
                        $documents[] = $docName;
                    }
                }
            }
        }

        $documents = array_values(array_unique($documents));

        $writeoffSubtypeOptions = [
            ['key' => 'materials', 'label' => 'Буд. матеріали'],
            ['key' => 'autoparts', 'label' => 'Автозапчастини'],
        ];

        $html = $this->renderPartial('modals/modal_doc_generation', [
            'kind' => $kind,
            'documents' => $documents,
            'writeoffSubtype' => $writeoffSubtype,
            'writeoffSubtypeOptions' => $writeoffSubtypeOptions,
        ]);

        return [
            'modalContent' => $html,
            'modalType' => 'info',
            'modalTitle' => 'Сформувати документи?',
            'modalSubtitle' => 'Сформувати DOCX документи з вашими товарами',
        ];
    }

    protected function resolveCurrentUser()
    {
        try {
            $frontendUser = \Auth::getUser();
            if ($frontendUser) {
                return $frontendUser;
            }
        } catch (\Throwable $e) {
        }

        try {
            if (class_exists(\Backend\Facades\BackendAuth::class)) {
                $backendUser = \Backend\Facades\BackendAuth::getUser();
                if ($backendUser) {
                    return $backendUser;
                }
            }
        } catch (\Throwable $e) {
        }

        return null;
    }

    protected function hasAdminAccess($user): bool
    {
        if (!$user) {
            return false;
        }

        if (OrganizationAccess::isOrganizationAdmin($user)) {
            return true;
        }

        try {
            if (method_exists($user, 'isInGroup') && $user->isInGroup('admin')) {
                return true;
            }
        } catch (\Throwable $e) {
        }

        try {
            if (method_exists($user, 'groups') && $user->groups()->where('code', 'admin')->exists()) {
                return true;
            }
        } catch (\Throwable $e) {
        }

        if (property_exists($user, 'is_superuser') && (bool)$user->is_superuser === true) {
            return true;
        }

        try {
            if (method_exists($user, 'hasAccess') && $user->hasAccess('samvol.inventory.*')) {
                return true;
            }
        } catch (\Throwable $e) {
        }

        return false;
    }

}
