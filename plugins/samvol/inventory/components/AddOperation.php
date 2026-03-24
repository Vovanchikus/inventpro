<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\OperationType;
use DB;
use Exception;
use Input;
use Log;
use Carbon\Carbon;
use Samvol\Inventory\Classes\OrganizationAccess;

class AddOperation extends ComponentBase
{
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

    public function componentDetails()
    {
        return [
            'name' => 'Добавление операции',
            'description' => 'Создание новой операции с товарами и документами'
        ];
    }

    public function onRun()
    {
        $organizationId = $this->organizationId();
        $operationId = input('operation_id');
        $this->page['operation_id'] = $operationId;
        if ($operationId) {
            $operationQuery = Operation::with(['note.products', 'products', 'documents'])->where('id', $operationId);
            $operation = $this->constrainByOrganization($operationQuery)->first();
            if ($operation) {
                $prefill = [];
                $noteId = $operation->note_id;
                $documents = [];

                $noteProducts = ($noteId && $operation->note && $operation->note->products)
                    ? $operation->note->products
                    : null;

                $counteragentPrefill = $operation->first_counteragent;
                if (empty($counteragentPrefill) && $noteProducts && $noteProducts->count()) {
                    $firstNoteProduct = $noteProducts->first();
                    $counteragentPrefill = $firstNoteProduct?->pivot?->counteragent;
                }
                if (empty($counteragentPrefill) && !empty($operation->draft_products)) {
                    $items = $operation->draft_products;
                    if (is_string($items)) {
                        $items = json_decode($items, true);
                    }
                    if (is_array($items) && !empty($items)) {
                        $first = $items[0] ?? null;
                        if (is_array($first) && !empty($first['counteragent'])) {
                            $counteragentPrefill = $first['counteragent'];
                        }
                    }
                }

                if ($noteProducts && $noteProducts->count()) {
                    foreach ($noteProducts as $p) {
                        $prefill[] = [
                            'id' => $p->id,
                            'name' => $p->name,
                            'inv_number' => $p->inv_number,
                            'unit' => $p->unit,
                            'price' => $p->price,
                            'quantity' => isset($p->pivot->quantity) ? (float)$p->pivot->quantity : null,
                            'sum' => isset($p->pivot->sum) ? (float)$p->pivot->sum : null,
                        ];
                    }
                } elseif ($operation->products && $operation->products->count()) {
                    foreach ($operation->products as $p) {
                        $prefill[] = [
                            'id' => $p->id,
                            'name' => $p->name,
                            'inv_number' => $p->inv_number,
                            'unit' => $p->unit,
                            'price' => $p->price,
                            'quantity' => isset($p->pivot->quantity) ? (float)$p->pivot->quantity : null,
                            'sum' => isset($p->pivot->sum) ? (float)$p->pivot->sum : null,
                        ];
                    }
                } elseif (!empty($operation->draft_products)) {
                    $items = $operation->draft_products;
                    if (is_string($items)) {
                        $items = json_decode($items, true);
                    }
                    if (is_array($items)) {
                        foreach ($items as $it) {
                            $prefill[] = [
                                'id' => $it['id'] ?? null,
                                'name' => $it['name'] ?? null,
                                'inv_number' => $it['inv_number'] ?? $it['inv'] ?? null,
                                'unit' => $it['unit'] ?? null,
                                'price' => isset($it['price']) ? (float)$it['price'] : null,
                                'quantity' => isset($it['quantity']) ? (float)$it['quantity'] : null,
                                'sum' => isset($it['sum']) ? (float)$it['sum'] : null,
                            ];
                        }
                    }
                }

                // Fallback: если товаров нет, попробуем достать их из заметки через pivot/JSON
                if (empty($prefill) && $noteId) {
                    try {
                        $rows = \DB::table('samvol_inventory_note_products as np')
                            ->leftJoin('samvol_inventory_products as p', 'np.product_id', '=', 'p.id')
                            ->where('np.note_id', $noteId)
                            ->select('np.*', 'p.id as p_id', 'p.name as p_name', 'p.inv_number as p_inv', 'p.unit as p_unit', 'p.price as p_price')
                            ->get();

                        foreach ($rows as $r) {
                            $prefill[] = [
                                'id' => $r->p_id ?? null,
                                'name' => $r->p_name ?? null,
                                'inv_number' => $r->p_inv ?? null,
                                'unit' => $r->p_unit ?? null,
                                'price' => isset($r->p_price) ? (float)$r->p_price : null,
                                'quantity' => isset($r->quantity) ? (float)$r->quantity : null,
                                'sum' => isset($r->sum) ? (float)$r->sum : null,
                            ];
                        }

                        if (empty($prefill)) {
                            $raw = \DB::table('samvol_inventory_notes')->where('id', $noteId)->value('products');
                            if ($raw) {
                                $items = json_decode($raw, true);
                                if (is_array($items)) {
                                    foreach ($items as $it) {
                                        $prefill[] = [
                                            'inv_number' => $it['inv_number'] ?? $it['inv'] ?? $it['id'] ?? null,
                                            'name' => $it['name'] ?? null,
                                            'unit' => $it['unit'] ?? null,
                                            'price' => isset($it['price']) ? (float)$it['price'] : null,
                                            'quantity' => isset($it['quantity']) ? (float)$it['quantity'] : (isset($it['qty']) ? (float)$it['qty'] : null),
                                            'sum' => isset($it['sum']) ? (float)$it['sum'] : null,
                                        ];
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::warning('[samvol] add-operation prefill fallback failed: ' . $e->getMessage());
                    }
                }

                Log::info('[samvol] add-operation prefill from operation_id', [
                    'operation_id' => $operationId,
                    'note_id' => $noteId,
                    'prefill_count' => count($prefill)
                ]);

                try {
                    Log::info('[samvol] add-operation prefill debug', [
                        'operation_id' => $operationId,
                        'note_id' => $noteId,
                        'note_products_count' => $noteProducts ? $noteProducts->count() : 0,
                        'operation_products_count' => $operation->products ? $operation->products->count() : 0,
                        'draft_products_count' => is_array($operation->draft_products) ? count($operation->draft_products) : (is_string($operation->draft_products) ? count((array)json_decode($operation->draft_products, true)) : 0),
                        'prefill_sample' => array_slice($prefill, 0, 2),
                    ]);
                } catch (\Exception $e) {
                    Log::warning('[samvol] add-operation prefill debug failed: ' . $e->getMessage());
                }

                if ($operation->documents && $operation->documents->count()) {
                    foreach ($operation->documents as $doc) {
                        $documents[] = [
                            'doc_name' => $doc->doc_name,
                            'doc_num' => $doc->doc_num,
                            'doc_purpose' => $doc->doc_purpose,
                            'doc_date' => $doc->doc_date,
                        ];
                    }
                }

                $this->page['prefill_operation'] = [
                    'type_id' => $operation->type_id,
                    'counteragent' => $counteragentPrefill,
                ];
                $this->page['prefill_documents'] = $documents;

                $this->page['prefill_products'] = $prefill;
                $this->page['note_id'] = $noteId;
                return;
            }
        }

        // Если пришёл note_id в URL, передадим товары из заметки в страницу (не создавая операцию)
        $noteId = input('note_id') ?: session('note_id');
        if ($noteId) {
            $note = \Samvol\Inventory\Models\Note::query()
                ->where('id', $noteId)
                ->where('organization_id', $organizationId)
                ->first();
            if ($note) {
                $prefill = [];

                // Попробуем собрать массив продуктов с полями pivot (quantity, sum)
                try {
                    foreach ($note->products as $p) {
                        $prefill[] = [
                            'id' => $p->id,
                            'name' => $p->name,
                            'inv_number' => $p->inv_number,
                            'unit' => $p->unit,
                            'price' => $p->price,
                            'quantity' => isset($p->pivot->quantity) ? (float)$p->pivot->quantity : null,
                            'sum' => isset($p->pivot->sum) ? (float)$p->pivot->sum : null,
                        ];
                    }
                } catch (\Exception $e) {
                    $prefill = [];
                }

                // Если связанных продуктов нет (в pivot product_id может быть NULL),
                // попробуем восстановить из JSON-поля notes.products
                if (empty($prefill)) {
                    try {
                        // Сначала попробуем собрать из pivot + products через JOIN
                        $rows = \DB::table('samvol_inventory_note_products as np')
                            ->leftJoin('samvol_inventory_products as p', 'np.product_id', '=', 'p.id')
                            ->where('np.note_id', $note->id)
                            ->select('np.*', 'p.id as p_id', 'p.name as p_name', 'p.inv_number as p_inv', 'p.unit as p_unit', 'p.price as p_price')
                            ->get();

                        foreach ($rows as $r) {
                            $prefill[] = [
                                'id' => $r->p_id ?? null,
                                'name' => $r->p_name ?? null,
                                'inv_number' => $r->p_inv ?? null,
                                'unit' => $r->p_unit ?? null,
                                'price' => isset($r->p_price) ? (float)$r->p_price : null,
                                'quantity' => isset($r->quantity) ? (float)$r->quantity : null,
                                'sum' => isset($r->sum) ? (float)$r->sum : null,
                            ];
                        }

                        // Если pivot пуст — попробуем декодировать сырое JSON поле как запасной вариант
                        if (empty($prefill)) {
                            $raw = \DB::table('samvol_inventory_notes')->where('id', $note->id)->value('products');
                            if ($raw) {
                                $items = json_decode($raw, true);
                                if (is_array($items)) {
                                    foreach ($items as $it) {
                                        $prefill[] = [
                                            'inv_number' => $it['inv_number'] ?? $it['inv'] ?? $it['id'] ?? null,
                                            'name' => $it['name'] ?? null,
                                            'unit' => $it['unit'] ?? null,
                                            'price' => isset($it['price']) ? (float)$it['price'] : null,
                                            'quantity' => isset($it['quantity']) ? (float)$it['quantity'] : (isset($it['qty']) ? (float)$it['qty'] : null),
                                            'sum' => isset($it['sum']) ? (float)$it['sum'] : null,
                                        ];
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // noop
                    }
                }

                // Логируем для отладки — сколько продуктов найдено и пример
                try {
                    // debug logging removed
                    // Если prefill пуст — выведем сырые pivot-строки для заметки
                    if (empty($prefill)) {
                        try {
                            $rows = \DB::table('samvol_inventory_note_products')->where('note_id', $note->id)->get();
                            // debug logging removed
                        } catch (\Exception $e) {
                            \Log::warning('[samvol] cannot read pivot rows: ' . $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
                    // noop
                }

                $this->page['prefill_products'] = $prefill;
                $this->page['note_id'] = $note->id;
            }
        }
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
            ->where('organization_id', $this->organizationId())
            ->orderBy('inv_number')
            ->first(['name', 'inv_number', 'unit', 'price']); // <- только первый результат

        return ['products' => $product ? [$product] : []];
    }

    public function onAddOperation()
    {
        $user = $this->resolveCurrentUser();

        if (!$this->hasAdminAccess($user)) {
            Log::warning('[samvol] onAddOperation denied', [
                'user_present' => (bool)$user,
                'user_class' => $user ? get_class($user) : null,
                'user_id' => $user->id ?? null,
                'login' => $user->login ?? null,
                'email' => $user->email ?? null,
            ]);
            throw new \ApplicationException('У вас нет прав на создание операций!');
        }

        $data = post();
        $generateDocMode = filter_var($data['generate_doc_mode'] ?? false, FILTER_VALIDATE_BOOLEAN);
        // Логируем пришедшие данные кратко для диагностики
        try {
            if (!empty($data['products'])) {
                // debug logging removed
            } else {
                // логируем количество строк формовых полей как индикатор
                $countNames = is_array($data['name']) ? count($data['name']) : 0;
                // debug logging removed
            }
        } catch (Exception $e) {
            Log::warning('[samvol] onAddOperation: failed to log incoming data: ' . $e->getMessage());
        }
        // Ранняя рекурсивная проверка: ищем вложенные массивы в любом уровне вложенности
        try {
            $found = null;
            $checkRecursive = function($value, $path = '') use (&$checkRecursive, &$found) {
                if ($found) return true; // уже нашли
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        $currentPath = $path === '' ? (string)$k : $path . '[' . $k . ']';
                        if (is_array($v)) {
                            $found = $currentPath;
                            return true;
                        }
                        // continue deeper
                        if (is_array($v) || is_object($v)) {
                            // only recurse into arrays (objects are typically UploadedFile instances)
                            if (is_array($v)) {
                                if ($checkRecursive($v, $currentPath)) return true;
                            }
                        }
                    }
                }
                return false;
            };

            foreach ($data as $key => $val) {
                if ($checkRecursive($val, $key)) break;
            }

            if ($found) {
                Log::warning('[samvol] onAddOperation: nested array detected in field path: ' . $found);
                return [
                    'validationErrors' => [
                        ['field' => (string)$found, 'message' => 'Неподдерживаемая вложенная структура данных']
                    ],
                    'toast' => [
                        'message' => 'Ошибка данных: найден вложенный массив в поле "' . $found . '"',
                        'type' => 'error',
                        'timeout' => 7000,
                        'position' => 'top-center'
                    ]
                ];
            }
        } catch (Exception $e) {
            Log::warning('[samvol] onAddOperation nested-check failed: ' . $e->getMessage());
        }
        // Protective normalization: if any product input array elements are themselves arrays,
        // replace them with the first scalar value to avoid nested arrays reaching DB queries
        // (log occurrences for later inspection).
        try {
            $fieldsToCheck = ['name','inv_number','unit','price','quantity','sum'];
            foreach ($fieldsToCheck as $f) {
                if (!empty($data[$f]) && is_array($data[$f])) {
                    foreach ($data[$f] as $i => $val) {
                        if (is_array($val)) {
                            // find first scalar inside
                            $replacement = null;
                            foreach ($val as $sub) {
                                if (is_scalar($sub) || $sub === null) { $replacement = $sub; break; }
                            }
                            if ($replacement === null) {
                                // fallback to JSON-encoded string
                                $replacement = json_encode($val);
                            }
                            Log::warning("[samvol] onAddOperation: field {$f}[{$i}] contained nested array; replaced with scalar/encoded.");
                            $data[$f][$i] = $replacement;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            Log::warning('[samvol] onAddOperation normalization failed: ' . $e->getMessage());
        }

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
        // Считаем операцию финальной только если загружен хотя бы один PDF-файл.
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

        $hasDocs = $hasFiles;

        $noteIdFromForm = !empty($data['note_id']) ? (int) $data['note_id'] : null;
        $requireFile = !$generateDocMode && empty($noteIdFromForm);

        // Валидируем документы в порядке: имя -> номер -> мета -> дата -> файл
        $docNames = $data['doc_name'] ?? [];
        $docNums = $data['doc_num'] ?? [];
        $docPurposes = $data['doc_purpose'] ?? [];
        $docDates = $data['doc_date'] ?? [];
        $rowsCount = max(
            count($docNames),
            count($docNums),
            count($docPurposes),
            count($docDates),
            is_array($files) ? count($files) : 0
        );

        $hasAnyDocInput = false;
        for ($i = 0; $i < $rowsCount; $i++) {
            $hasAnyDocInput = !empty($docNames[$i])
                || !empty($docNums[$i])
                || !empty($docPurposes[$i])
                || !empty($docDates[$i])
                || !empty(is_array($files) ? ($files[$i] ?? null) : null);

            if ($hasAnyDocInput) {
                break;
            }
        }

        if ($rowsCount > 0 && ($requireFile || $hasAnyDocInput)) {
            $firstFile = is_array($files) ? ($files[0] ?? null) : null;
            $firstEmpty = empty($docNames[0])
                && empty($docNums[0])
                && empty($docPurposes[0])
                && empty($docDates[0])
                && empty($firstFile);
            if ($firstEmpty) {
                return $this->firstError("doc_name[0]", 'Укажите имя документа');
            }
        }

        for ($i = 0; $i < $rowsCount; $i++) {
            $docName = $docNames[$i] ?? null;
            $docNum = $docNums[$i] ?? null;
            $docPurpose = $docPurposes[$i] ?? null;
            $docDate = $docDates[$i] ?? null;
            $file = (is_array($files) ? ($files[$i] ?? null) : null);

            $hasAny = !empty($docName) || !empty($docNum) || !empty($docPurpose) || !empty($docDate) || !empty($file);
            if (!$hasAny) {
                continue;
            }

            if (empty($docName)) return $this->firstError("doc_name[$i]", 'Укажите имя документа');
            if (empty($docNum)) return $this->firstError("doc_num[$i]", 'Укажите номер документа');
            if (empty($docPurpose)) return $this->firstError("doc_purpose[$i]", 'Укажите мету документа');
            if (empty($docDate)) return $this->firstError("doc_date[$i]", 'Укажите дату документа');
            if ($requireFile && empty($file)) return $this->firstError("doc_file[$i]", 'Прикрепите PDF-документ');
        }

        // Если операция без заметки — файл обязателен (проверяем последним)
        if ($requireFile && !$hasDocs) {
            return [
                'validationErrors' => [
                    ['field' => 'doc_file', 'message' => 'Прикрепите PDF-документ для операции без заметки']
                ],
                'toast' => [
                    'message' => 'Прикрепите PDF-документ для операции без заметки',
                    'type' => 'error',
                    'timeout' => 5000,
                    'position' => 'top-center'
                ]
            ];
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

            // --- Проверка остатка ---
            if (in_array($operationTypeName, ['расход', 'передача', 'списание'])) {

                $product = Product::where('inv_number', $inv_number)
                    ->where('organization_id', $this->organizationId())
                    ->first();
                $currentQty = $product->calculated_quantity ?? 0;

                if (is_numeric($quantity_raw) && floatval($quantity_raw) > $currentQty) {

                    $actionVerb = $operationTypeName === 'передача' ? 'передать' : 'списать';

                    $errors[] = ["field" => "quantity[$i]", "message" => "Слишком много"];

                    return [
                        'validationErrors' => $errors,
                        'toast' => [
                            'message' =>
                                "<b>Ошибка!</b><br>Нельзя {$actionVerb} <b>{$quantity_raw}</b> ед. товара \"<b>{$name}</b>\",
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

            $operationId = $data['operation_id'] ?? null;
            $operation = $operationId
                ? $this->constrainByOrganization(Operation::with(['products', 'documents'])->where('id', $operationId))->first()
                : new Operation();
            if ($operationId && !$operation) {
                return [
                    'toast' => [
                        'message' => 'Операция не найдена',
                        'type' => 'error',
                        'timeout' => 5000,
                        'position' => 'top-center'
                    ]
                ];
            }

            $operation->type_id = $data['type_id'];
            $operation->organization_id = $this->organizationId();

            $hasExistingFiles = false;
            if ($operationId) {
                $hasExistingFiles = $operation->documents()
                    ->whereHas('doc_file')
                    ->exists();
            }

            $hasDocsFinal = $hasDocs || $hasExistingFiles;

            // Если есть PDF — финальная, иначе черновик
            $operation->is_draft = !$hasDocsFinal;
            $operation->is_posted = $hasDocsFinal;
            if ($operation->is_draft) {
                $operation->prepared_at = $operation->prepared_at ?: Carbon::now();
            } else {
                $operation->completed_at = $operation->completed_at ?: Carbon::now();
                $operation->draft_products = null;
            }

            // Привяжем к заметке, если есть
            if (!empty($data['note_id'])) {
                $operation->note_id = (int) $data['note_id'];
            }

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

            // Сохраняем документы по заполненным полям, файл необязателен
            if (!empty($data['doc_name']) && is_array($data['doc_name'])) {
                if ($operationId) {
                    $operation->documents()->delete();
                }
                foreach ($data['doc_name'] as $i => $docName) {
                    if (!$docName) continue;
                    $uploadedFile = $files[$i] ?? null;

                    $document = $operation->documents()->create([
                        'organization_id' => (int) $operation->organization_id,
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
            }


            // Товары — добавляем в pivot ТОЛЬКО для финальной операции
            if (!$operation->is_draft) {
                if ($operationId && $operation->products) {
                    $operation->products()->detach();
                }
                foreach ($data['name'] as $i => $name) {

                    $inv_number = $data['inv_number'][$i];
                    $unit       = $data['unit'][$i];
                    $price      = floatval($this->normalizeNumericInput($data['price'][$i] ?? null));
                    $quantity   = floatval($this->normalizeNumericInput($data['quantity'][$i] ?? null));

                    $product = Product::firstOrCreate(
                        ['inv_number' => $inv_number, 'organization_id' => $this->organizationId()],
                        ['name' => $name, 'unit' => $unit, 'price' => $price]
                    );

                    $pivotSum = round($quantity * $price, 2);

                    // Привязываем товары
                    $operation->products()->attach($product->id, [
                        'organization_id' => (int) ($operation->organization_id ?? 0) ?: null,
                        'quantity'     => $quantity,
                        'sum'          => $pivotSum,
                        'counteragent' => $operationCounteragent
                    ]);
                }
            } else {
                // Для черновой операции не трогаем pivot — вместо этого обновим товары в заметке (если она есть)
                if (!empty($operation->note_id) && !empty($data['name']) && is_array($data['name'])) {
                    $note = \Samvol\Inventory\Models\Note::query()
                        ->where('id', $operation->note_id)
                        ->where('organization_id', $this->organizationId())
                        ->first();
                    if ($note) {
                        // Build sync payload: ensure products exist and map by product ID
                        $sync = [];
                        foreach ($data['name'] as $i => $name) {
                            $inv = $data['inv_number'][$i] ?? null;
                            if (!$inv) continue;
                            try {
                                $product = Product::firstOrCreate(
                                    ['inv_number' => $inv, 'organization_id' => $this->organizationId()],
                                    ['name' => $name, 'unit' => $data['unit'][$i] ?? null, 'price' => isset($data['price'][$i]) ? floatval($this->normalizeNumericInput($data['price'][$i])) : null]
                                );
                                $pid = $product->id;
                                $qty = isset($data['quantity'][$i]) ? floatval($this->normalizeNumericInput($data['quantity'][$i])) : 0;
                                $price = isset($data['price'][$i]) ? floatval($this->normalizeNumericInput($data['price'][$i])) : null;
                                $sync[$pid] = [
                                    'organization_id' => (int) ($operation->organization_id ?? 0) ?: null,
                                    'quantity' => $qty,
                                    'sum' => $price !== null ? round($qty * $price, 2) : null,
                                    'counteragent' => $operationCounteragent,
                                ];
                            } catch (Exception $e) {
                                Log::warning('[samvol] onAddOperation: failed to ensure product for note sync: ' . ($inv ?? 'n/a') . ' - ' . $e->getMessage());
                            }
                        }

                        try {
                            if (!empty($sync)) {
                                $note->products()->sync($sync);
                            } else {
                                $note->products()->sync([]);
                            }
                        } catch (Exception $e) {
                            Log::error('[samvol] onAddOperation: note products sync failed: ' . $e->getMessage());
                            throw $e;
                        }
                    }
                }
            }

            // После сохранения — если операция draft, не трогаем склад, но обновим статус заметки
            if ($operation->is_draft) {
                try {
                    $draftProducts = [];
                    foreach ($data['name'] as $i => $name) {
                        $draftProducts[] = [
                            'name' => $name,
                            'inv_number' => $data['inv_number'][$i] ?? null,
                            'unit' => $data['unit'][$i] ?? null,
                            'price' => isset($data['price'][$i]) ? (float)$this->normalizeNumericInput($data['price'][$i]) : null,
                            'quantity' => isset($data['quantity'][$i]) ? (float)$this->normalizeNumericInput($data['quantity'][$i]) : null,
                            'sum' => isset($data['sum'][$i]) ? (float)$this->normalizeNumericInput($data['sum'][$i]) : null,
                            'counteragent' => $operationCounteragent,
                        ];
                    }
                    $operation->draft_products = $draftProducts;
                    $operation->save();

                    Log::info('[samvol] saved draft_products', [
                        'operation_id' => $operation->id,
                        'count' => count($draftProducts)
                    ]);
                } catch (Exception $e) {
                    Log::warning('[samvol] failed to save draft_products: ' . $e->getMessage());
                }

                if (!empty($operation->note_id)) {
                    $note = \Samvol\Inventory\Models\Note::query()
                        ->where('id', $operation->note_id)
                        ->where('organization_id', $this->organizationId())
                        ->first();
                    if ($note) {
                        $note->status = 'document_prepared';
                        $note->save();
                    }
                }

                DB::commit();

                return [
                    'toast' => [
                        'message' => 'Документ разработан, склад не изменён',
                        'type' => 'success',
                        'timeout' => 4000,
                        'position' => 'top-center'
                    ],
                    'operationId' => (int) $operation->id,
                    'showGenerateDocModal' => true,
                    'operationTypeName' => (string)($opType->name ?? ''),
                ];
            }

            // Если операция финальная — вызовем recalcStatus у заметки
            if (!empty($operation->note_id)) {
                $note = \Samvol\Inventory\Models\Note::query()
                    ->where('id', $operation->note_id)
                    ->where('organization_id', $this->organizationId())
                    ->first();
                if ($note) $note->recalcStatus();
            }

            DB::commit();

            return [
                'toast' => [
                    'message' => 'Операция успешно добавлена',
                    'type' => 'success',
                    'timeout' => 4000,
                    'position' => 'top-center'
                ],
                'operationId' => (int) $operation->id,
                'showGenerateDocModal' => true,
                'operationTypeName' => (string)($opType->name ?? ''),
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

        $products = Product::query()
            ->where('organization_id', $this->organizationId())
            ->where(function ($builder) use ($q) {
                $builder->whereRaw('LOWER(name) LIKE ?', ["%{$q}%"])
                    ->orWhereRaw('LOWER(inv_number) LIKE ?', ["%{$q}%"]);
            })
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
            $operation = $this->constrainByOrganization(
                Operation::with(['documents'])->where('id', $operationId)
            )->first();
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

        return OrganizationAccess::isProjectAdmin($user);
    }

    protected function organizationId(): int
    {
        $user = $this->resolveCurrentUser();
        return (int) ($user->organization_id ?? 0);
    }

    protected function constrainByOrganization($query, string $column = 'organization_id')
    {
        $organizationId = $this->organizationId();
        return $organizationId > 0
            ? $query->where($column, $organizationId)
            : $query->whereRaw('1 = 0');
    }
}
