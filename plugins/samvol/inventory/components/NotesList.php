<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Note;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;
use Carbon\Carbon;
use DB;

class NotesList extends ComponentBase
{
    public $notes;

    public function componentDetails()
    {
        return [
            'name' => 'Notes list',
            'description' => 'Provide notes list and handlers for frontend'
        ];
    }

    public function defineProperties()
    {
        return [
            'noteId' => [
                'title'       => 'ID заметки',
                'description' => 'ID заметки из URL (необязательный)',
                'type'        => 'string',
                'default'     => null,
            ],
        ];
    }

    public function onRun()
    {
        $noteId = $this->property('noteId');
        if ($noteId) {
            $note = Note::with([
                'products',
                'operations.products',
                'operations.documents',
                'operations.documents.doc_file',
                'operations.type'
            ])->where('id', $noteId)->first();

            $normalized = $note ? $this->normalizeNotes(collect([$note])) : [];
            if (!empty($normalized[0])) {
                $normalized[0]['is_single'] = true;
            }
            $this->page['note'] = $normalized[0] ?? null;
            return;
        }

        // Eager load operations with products/documents to render cards without extra queries
        $raw = Note::with([
            'products',
            'operations.products',
            'operations.documents',
            'operations.documents.doc_file',
            'operations.type'
        ])->orderBy('due_date', 'asc')->get();
        $this->notes = $raw;
        // Normalize notes for page consumption (ensure products array shape)
        $this->page['notes'] = $this->normalizeNotes($raw);
    }

    public function onListNotes()
    {
        $raw = Note::with([
            'products',
            'operations.products',
            'operations.documents',
            'operations.documents.doc_file',
            'operations.type'
        ])->orderBy('due_date', 'asc')->get();
        $notes = $this->normalizeNotes($raw);

            // Также возвращаем отрендеренный HTML партиал (Twig) для модалки
            try {
                $html = $this->renderPartial('modals/modal_notes_list', ['notes' => $notes]);
            } catch (\Exception $e) {
                \Log::warning('[samvol] onListNotes: failed to render notes partial: ' . $e->getMessage());
                $html = null;
            }

            // Render main notes grid partial so frontend can replace it dynamically
            try {
                $gridHtml = $this->renderPartial('notes_grid', ['notes' => $notes]);
            } catch (\Exception $e) {
                \Log::warning('[samvol] onListNotes: failed to render notes grid partial: ' . $e->getMessage());
                $gridHtml = null;
            }

        return ['notes' => $notes, 'notesHtml' => $html, 'notesGridHtml' => $gridHtml];
    }

    public function onAddProductsToNote()
    {
        $noteId = post('note_id');
        $products = post('products');

        // Логирование входящих данных для диагностики
        try {
            // debug logging removed
        } catch (\Exception $e) {
            \Log::warning('[samvol] onAddProductsToNote: failed to log incoming products: ' . $e->getMessage());
        }

        if (!$noteId) return ['error' => 'note_id is required'];
        if (!$products) return ['error' => 'products required'];

        // products can be JSON string
        if (is_string($products)) {
            $products = json_decode($products, true) ?: [];
        }

        try {
            DB::beginTransaction();

            $note = Note::find($noteId);
            if (!$note) throw new \Exception('Note not found');


            // При добавлении товаров к уже существующей заметке не создаём операцию автоматически.
            // Операция-черновик должна создаваться только явным действием пользователя на странице операций.

            // Normalize products and deduplicate by inv_number (keep the latest selection order)
            $normalized = [];
            foreach ($products as $p) {
                $inv = $p['inv_number'] ?? ($p['inv'] ?? null);
                if (!$inv) continue;
                $normalized[$inv] = [
                    'inv_number' => $inv,
                    'name' => $p['name'] ?? null,
                    'unit' => $p['unit'] ?? null,
                    'price' => isset($p['price']) ? floatval($p['price']) : null,
                    'quantity' => isset($p['quantity']) ? floatval($p['quantity']) : (isset($p['qty']) ? floatval($p['qty']) : 0),
                ];
            }

            // Replace note products with the current selection (do not keep older ones)
            // Создаём/находим товары по inv_number и формируем payload для sync()
            $sync = [];
            foreach ($normalized as $inv => $p) {
                try {
                    $product = Product::firstOrCreate(
                        ['inv_number' => $inv],
                        ['name' => $p['name'] ?? null, 'unit' => $p['unit'] ?? null, 'price' => $p['price'] ?? null]
                    );
                    $pid = $product->id;
                    $qty = isset($p['quantity']) ? floatval($p['quantity']) : 0;
                    $price = isset($p['price']) ? floatval($p['price']) : null;
                    $sync[$pid] = [
                        'quantity' => $qty,
                        'sum' => $price !== null ? round($qty * $price, 2) : null,
                        'counteragent' => null,
                    ];
                } catch (\Exception $e) {
                    \Log::warning('[samvol] onAddProductsToNote: failed to ensure product ' . $inv . ' - ' . $e->getMessage());
                }
            }

            try {
                // Синхронизируем pivot; если пусто — очистим связь
                if (!empty($sync)) {
                    $note->products()->sync($sync);
                } else {
                    $note->products()->sync([]);
                }
            } catch (\Exception $e) {
                \Log::error('[samvol] onAddProductsToNote: sync failed: ' . $e->getMessage());
                throw $e;
            }

            $note->save();

            // Обновим статус заметки и завершим транзакцию. Не создаём операцию/историю здесь —
            // черновики должны создаваться только явным действием пользователя на странице операций.
            try {
                $note->recalcStatus();
            } catch (\Exception $e) {
                \Log::error('[samvol] onAddProductsToNote: recalcStatus failed: ' . $e->getMessage());
                try { \Log::error($e->getTraceAsString()); } catch (\Exception $ee) {}
                throw $e; // rethrow to be handled by outer catch
            }

            DB::commit();

            return ['toast' => ['message' => 'Товары сохранены в заметке', 'type' => 'success']];

        } catch (\Exception $e) {
            DB::rollBack();
            try {
                \Log::error('[samvol] onAddProductsToNote failed: ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
            } catch (\Exception $_) {}
            return ['toast' => ['message' => 'Ошибка: '.$e->getMessage(), 'type' => 'error']];
        }
    }

    public function onToggleAccountingStatus()
    {
        $operationId = post('operation_id');
        $noteId = post('note_id');

        try {
            \Log::info('[samvol] toggle accounting: request', [
                'operation_id' => $operationId,
                'note_id' => $noteId,
            ]);
        } catch (\Exception $e) {
            \Log::warning('[samvol] toggle accounting: log request failed - ' . $e->getMessage());
        }

        if (!$operationId) {
            return ['toast' => ['message' => 'operation_id is required', 'type' => 'error']];
        }

        $lockKey = 'samvol_toggle_accounting_' . $operationId;
        try {
            if (!\Cache::add($lockKey, true, 2)) {
                \Log::info('[samvol] toggle accounting: ignored duplicate', [
                    'operation_id' => $operationId,
                    'note_id' => $noteId,
                ]);
                return ['status' => 'ignored'];
            }
        } catch (\Exception $e) {
            \Log::warning('[samvol] toggle accounting: lock failed - ' . $e->getMessage());
        }

        $operation = Operation::find($operationId);
        if (!$operation) {
            return ['toast' => ['message' => 'Операция не найдена', 'type' => 'error']];
        }

        try {
            \Log::info('[samvol] toggle accounting: before', [
                'operation_id' => $operation->id,
                'accounting_at' => $operation->accounting_at,
                'is_posted' => $operation->is_posted,
                'is_draft' => $operation->is_draft,
            ]);
        } catch (\Exception $e) {
            \Log::warning('[samvol] toggle accounting: log before failed - ' . $e->getMessage());
        }

        if (!\Schema::hasColumn('samvol_inventory_operations', 'accounting_at')) {
            return ['toast' => ['message' => 'Поле статуса отсутствует', 'type' => 'error']];
        }

        if (!empty($operation->is_posted)) {
            return ['toast' => ['message' => 'Нельзя изменить статус выполненной операции', 'type' => 'error']];
        }

        $newAccountingAt = $operation->accounting_at ? null : Carbon::now();
        \DB::table('samvol_inventory_operations')
            ->where('id', $operation->id)
            ->update(['accounting_at' => $newAccountingAt]);

        try {
            \Log::info('[samvol] toggle accounting: after update', [
                'operation_id' => $operation->id,
                'new_accounting_at' => $newAccountingAt,
            ]);
        } catch (\Exception $e) {
            \Log::warning('[samvol] toggle accounting: log after update failed - ' . $e->getMessage());
        }

        if ($noteId) {
            $note = Note::with([
                'products',
                'operations.products',
                'operations.documents',
                'operations.documents.doc_file',
                'operations.type'
            ])->where('id', $noteId)->first();

            $normalized = $note ? $this->normalizeNotes(collect([$note])) : [];
            $noteView = $normalized[0] ?? null;

            if ($note) {
                try {
                    $note->recalcStatus();
                } catch (\Exception $e) {
                    \Log::warning('[samvol] onToggleAccountingStatus: recalcStatus failed - ' . $e->getMessage());
                }
            }

            if ($noteView) {
                $noteView['is_single'] = true;
                $html = $this->renderPartial('notes/item', ['note' => $noteView]);
                try {
                    \Log::info('[samvol] toggle accounting: rendered note', [
                        'note_id' => $noteId,
                        'note_status' => $noteView['status'] ?? null,
                        'ops_count' => $noteView['operations_count'] ?? null,
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('[samvol] toggle accounting: log render failed - ' . $e->getMessage());
                }
                return ['#note-card-' . $noteId => $html];
            }
        }

        return ['toast' => ['message' => 'Статус обновлён', 'type' => 'success']];
    }

    /**
     * Normalize a collection of Note models into an array suitable for Twig rendering.
     * Keeps products and operations flattened with necessary pivot fields.
     */
    protected function normalizeNotes($collection)
    {
        $statusClassMap = [
            'new' => 'note-status--new',
            'in_development' => 'note-status--dev',
            'document_prepared' => 'note-status--prepared',
            'in_accounting' => 'note-status--accounting',
            'completed' => 'note-status--completed',
        ];

        $operationStatusLabelMap = [
            'in_development' => 'В розробці',
            'document_prepared' => 'Документи готові',
            'in_accounting' => 'В бухгалтерії',
            'completed' => 'Виконано',
        ];

        $operationStatusClassMap = [
            'in_development' => 'note-op-status--dev',
            'document_prepared' => 'note-op-status--prepared',
            'in_accounting' => 'note-op-status--accounting',
            'completed' => 'note-op-status--completed',
        ];

        // Preload fallback pivot rows and raw JSON products for all notes to avoid N+1 queries
        $noteIds = $collection->pluck('id')->filter()->unique()->values()->all();

        $pivotRowsGrouped = [];
        if (!empty($noteIds)) {
            try {
                $rows = \DB::table('samvol_inventory_note_products as np')
                    ->leftJoin('samvol_inventory_products as p', 'np.product_id', '=', 'p.id')
                    ->whereIn('np.note_id', $noteIds)
                    ->select('np.*', 'p.id as p_id', 'p.name as p_name', 'p.inv_number as p_inv', 'p.unit as p_unit', 'p.price as p_price', 'np.note_id')
                    ->get();

                foreach ($rows as $r) {
                    $nid = $r->note_id ?? null;
                    if ($nid === null) continue;
                    $pivotRowsGrouped[$nid][] = $r;
                }
            } catch (\Exception $e) {
                \Log::warning('[samvol] normalizeNotes: preload pivot failed - ' . $e->getMessage());
            }
        }

        // Preload raw JSON 'products' column for notes where needed (only if column exists)
        $rawProductsByNote = [];
        if (!empty($noteIds)) {
            try {
                if (\Schema::hasColumn('samvol_inventory_notes', 'products')) {
                    $rows = \DB::table('samvol_inventory_notes')->whereIn('id', $noteIds)->pluck('products', 'id');
                    foreach ($rows as $nid => $raw) {
                        $rawProductsByNote[$nid] = $raw;
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('[samvol] normalizeNotes: preload raw products failed - ' . $e->getMessage());
            }
        }

        $statusPriority = [
            'in_development' => 1,
            'document_prepared' => 2,
            'in_accounting' => 3,
            'completed' => 4,
        ];

        return collect($collection)->map(function($n) use ($pivotRowsGrouped, $rawProductsByNote, $statusClassMap, $operationStatusLabelMap, $operationStatusClassMap, $statusPriority) {
            // Prepare operations list
            $ops = $n->operations->map(function($o) use ($operationStatusLabelMap, $operationStatusClassMap){
                $doc = $o->documents->sortBy('id')->first();
                $docName = $doc?->doc_name ?: ($o->doc_name ?? null);
                $docNum = $doc?->doc_num ?: ($o->doc_num ?? null);
                $docDate = $doc?->doc_date ?: ($o->doc_date ?? null);

                try {
                    \Log::info('[samvol] notes doc debug', [
                        'operation_id' => $o->id,
                        'doc_rel_exists' => $doc ? true : false,
                        'doc_rel_name' => $doc?->doc_name,
                        'doc_rel_num' => $doc?->doc_num,
                        'doc_rel_date' => $doc?->doc_date,
                        'op_doc_name' => $o->doc_name ?? null,
                        'op_doc_num' => $o->doc_num ?? null,
                        'op_doc_date' => $o->doc_date ?? null,
                        'resolved_name' => $docName,
                        'resolved_num' => $docNum,
                        'resolved_date' => $docDate,
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('[samvol] notes doc debug failed: ' . $e->getMessage());
                }
                $docDateLabel = $docDate ? Carbon::parse($docDate)->format('d.m.Y') : null;

                $dateLabel = $docDate
                    ? Carbon::parse($docDate)->format('d.m.Y')
                    : ($o->created_at ? Carbon::parse($o->created_at)->format('d.m.Y') : null);

                $titleBase = null;
                if ($docName || $docNum) {
                    $parts = [];
                    if ($docName) $parts[] = $docName;
                    if ($docNum) $parts[] = '№ ' . $docNum;
                    $titleBase = implode(' ', $parts);
                }

                if (!$titleBase) {
                    $titleBase = $o->type?->name
                        ? 'Операція: ' . $o->type->name
                        : 'Операція №' . $o->id;
                }

                $docLabel = $titleBase . ($docDateLabel ? ' от ' . $docDateLabel : '');
                $title = ($docName || $docNum || $docDateLabel) ? $docLabel : ($dateLabel ? ($titleBase . ' от ' . $dateLabel) : $titleBase);

                if (!empty($o->is_posted)) {
                    $statusKey = 'completed';
                } elseif (!empty($o->accounting_at)) {
                    $statusKey = 'in_accounting';
                } elseif (!empty($o->is_draft) || (!empty($o->note_id) && empty($o->is_posted))) {
                    $statusKey = 'document_prepared';
                } else {
                    $statusKey = 'in_development';
                }

                $statusLabel = $operationStatusLabelMap[$statusKey] ?? $statusKey;
                $statusClass = $operationStatusClassMap[$statusKey] ?? null;

                $opProducts = [];
                if ($o->products && $o->products->count()) {
                    $opProducts = $o->products->map(function($p){
                        $name = $p->name ?: $p->inv_number;
                        $qty = isset($p->pivot->quantity) ? floatval($p->pivot->quantity) : null;
                        $label = trim($name . ($qty !== null ? ' — ' . $qty : ''));

                        return [
                            'name' => $p->name,
                            'inv_number' => $p->inv_number,
                            'quantity' => $qty,
                            'label' => $label,
                        ];
                    })->toArray();
                } elseif (!empty($o->draft_products) && is_array($o->draft_products)) {
                    foreach ($o->draft_products as $p) {
                        $name = $p['name'] ?? ($p['inv_number'] ?? null);
                        $qty = isset($p['quantity']) ? floatval($p['quantity']) : null;
                        $label = trim($name . ($qty !== null ? ' — ' . $qty : ''));
                        $opProducts[] = [
                            'name' => $p['name'] ?? null,
                            'inv_number' => $p['inv_number'] ?? null,
                            'quantity' => $qty,
                            'label' => $label,
                        ];
                    }
                }

                $dateSort = $docDate
                    ? Carbon::parse($docDate)->timestamp
                    : ($o->created_at ? Carbon::parse($o->created_at)->timestamp : null);

                $preparedAt = $o->prepared_at ?: ((!empty($o->is_draft) || (!empty($o->note_id) && empty($o->is_posted))) ? $o->created_at : null);
                $completedAt = $o->completed_at ?: (!empty($o->is_posted) ? ($o->updated_at ?: $o->created_at) : null);
                $accountingAt = $o->accounting_at;
                if (is_string($accountingAt) && strpos($accountingAt, '0000-00-00') === 0) {
                    $accountingAt = null;
                }

                return [
                    'id' => $o->id,
                    'title' => $title,
                    'title_base' => $titleBase,
                    'doc_label' => $docLabel,
                    'date_label' => $dateLabel,
                    'date_sort' => $dateSort,
                    'status_key' => $statusKey,
                    'status_label' => $statusLabel,
                    'status_class' => $statusClass,
                    'created_at' => $o->created_at,
                    'prepared_at' => $preparedAt,
                    'accounting_at' => $accountingAt,
                    'completed_at' => $completedAt,
                    'can_toggle_accounting' => in_array($statusKey, ['document_prepared', 'in_accounting'], true),
                    'toggle_label' => $statusKey === 'in_accounting' ? 'Документи готові' : 'В бухгалтерії',
                    'products' => $opProducts,
                    'products_count' => count($opProducts),
                    'show_products' => !empty($opProducts),
                ];
            })->toArray();

            // Normalize products: support both relation collection (with pivot) and legacy array
            $prodList = [];
            try {
                $nProducts = $n->products;
                if ($nProducts instanceof \Illuminate\Support\Collection) {
                    foreach ($nProducts as $it) {
                        $prodList[] = [
                            'inv_number' => $it->inv_number ?? null,
                            'name' => $it->name ?? null,
                            'unit' => $it->unit ?? null,
                            'price' => isset($it->price) ? floatval($it->price) : null,
                            'quantity' => isset($it->pivot->quantity) ? floatval($it->pivot->quantity) : null,
                            'sum' => isset($it->pivot->sum) ? floatval($it->pivot->sum) : null,
                        ];
                    }
                } elseif (is_array($nProducts)) {
                    foreach ($nProducts as $it) {
                        if (!is_array($it)) continue;
                        $prodList[] = [
                            'inv_number' => $it['inv_number'] ?? ($it['inv'] ?? null),
                            'name' => $it['name'] ?? null,
                            'unit' => $it['unit'] ?? null,
                            'price' => isset($it['price']) ? floatval($it['price']) : null,
                            'quantity' => isset($it['quantity']) ? floatval($it['quantity']) : null,
                            'sum' => isset($it['sum']) ? floatval($it['sum']) : null,
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::warning('[samvol] normalizeNotes: failed to normalize products for note ' . ($n->id ?? 'n/a') . ' - ' . $e->getMessage());
            }

            // If prodList is empty, try to recover products from pivot join or raw JSON column
            if (empty($prodList)) {
                // use preloaded pivot rows if available
                $rows = $pivotRowsGrouped[$n->id] ?? [];
                if (!empty($rows)) {
                    foreach ($rows as $r) {
                        $prodList[] = [
                            'inv_number' => $r->p_inv ?? $r->inv_number ?? null,
                            'name' => $r->p_name ?? $r->name ?? null,
                            'unit' => $r->p_unit ?? null,
                            'price' => isset($r->p_price) ? floatval($r->p_price) : null,
                            'quantity' => isset($r->quantity) ? floatval($r->quantity) : null,
                            'sum' => isset($r->sum) ? floatval($r->sum) : null,
                        ];
                    }
                } else {
                    // fallback to preloaded raw JSON
                    $raw = $rawProductsByNote[$n->id] ?? null;
                    if ($raw) {
                        $items = json_decode($raw, true);
                        if (is_array($items)) {
                            foreach ($items as $it) {
                                $prodList[] = [
                                    'inv_number' => $it['inv_number'] ?? $it['inv'] ?? $it['id'] ?? null,
                                    'name' => $it['name'] ?? null,
                                    'unit' => $it['unit'] ?? null,
                                    'price' => isset($it['price']) ? floatval($it['price']) : null,
                                    'quantity' => isset($it['quantity']) ? floatval($it['quantity']) : (isset($it['qty']) ? floatval($it['qty']) : null),
                                    'sum' => isset($it['sum']) ? floatval($it['sum']) : null,
                                ];
                            }
                        }
                    }
                }
            }

            $productsView = [];
            foreach ($prodList as $p) {
                $name = $p['name'] ?? $p['inv_number'] ?? null;
                $qty = $p['quantity'] ?? null;
                $label = trim($name . ($qty !== null ? ' — ' . $qty : ''));
                $productsView[] = [
                    'inv_number' => $p['inv_number'] ?? null,
                    'name' => $p['name'] ?? null,
                    'quantity' => $qty,
                    'label' => $label,
                ];
            }

            $statusKey = $n->status ?: 'new';
            $statusLabel = $n->human_status ?? $statusKey;
            $statusClass = $statusClassMap[$statusKey] ?? null;

            if (!empty($ops)) {
                $minKey = null;
                $minPriority = PHP_INT_MAX;
                foreach ($ops as $op) {
                    $key = $op['status_key'] ?? null;
                    if (!$key) continue;
                    $priority = $statusPriority[$key] ?? PHP_INT_MAX;
                    if ($priority < $minPriority) {
                        $minPriority = $priority;
                        $minKey = $key;
                    }
                }

                if ($minKey) {
                    $statusKey = $minKey;
                    $statusLabel = $operationStatusLabelMap[$minKey] ?? $minKey;
                    $statusClass = $statusClassMap[$minKey] ?? null;
                }
            }

            $dueDateLabel = $n->due_date
                ? Carbon::parse($n->due_date)->format('d.m.Y')
                : null;

            $createdAtTs = $n->created_at
                ? Carbon::parse($n->created_at)->timestamp
                : null;

            $filterStatusKey = $statusKey === 'new' ? 'in_development' : $statusKey;

            $timeline = collect($ops)
                ->flatMap(function($op) use ($operationStatusLabelMap, $operationStatusClassMap) {
                    $steps = [
                        ['key' => 'in_development', 'date' => $op['created_at'] ?? null],
                        ['key' => 'document_prepared', 'date' => $op['prepared_at'] ?? null],
                        ['key' => 'in_accounting', 'date' => $op['accounting_at'] ?? null],
                        ['key' => 'completed', 'date' => $op['completed_at'] ?? null],
                    ];

                    $items = [];
                    foreach ($steps as $step) {
                        if (empty($step['date'])) continue;
                        $dateLabel = Carbon::parse($step['date'])->format('d.m.Y');
                        $items[] = [
                            'label' => trim(($operationStatusLabelMap[$step['key']] ?? $step['key']) . ' - ' . ($op['doc_label'] ?? $op['title_base'] ?? '')),
                            'date_label' => $dateLabel,
                            'date_sort' => Carbon::parse($step['date'])->timestamp,
                            'status_key' => $step['key'],
                            'status_class' => $operationStatusClassMap[$step['key']] ?? null,
                        ];
                    }

                    return $items;
                })
                ->sortBy('date_sort')
                ->values()
                ->map(function($item){
                    unset($item['date_sort']);
                    return $item;
                })
                ->toArray();

            return [
                'id' => $n->id,
                'title' => $n->title ?: 'Без назви',
                'description' => $n->description ?: '',
                'due_date' => $n->due_date,
                'due_date_label' => $dueDateLabel,
                'status' => $n->status,
                'status_key' => $statusKey,
                'filter_status_key' => $filterStatusKey,
                'status_label' => $statusLabel,
                'status_class' => $statusClass,
                'created_at_ts' => $createdAtTs,
                'products' => $productsView,
                'products_count' => count($productsView),
                'show_products' => !empty($productsView),
                'products_title' => 'Товари:',
                'operations' => $ops,
                'operations_count' => count($ops),
                'show_operations' => !empty($ops),
                'operations_title' => 'Операції:',
                'timeline' => $timeline,
                'show_timeline' => !empty($timeline),
                'timeline_title' => 'Історія',
                'tab_info_label' => 'Інформація',
                'tab_history_label' => 'Історія',
                'is_single' => false,
                'action_label' => 'Відкрити',
                'action_url' => '/notes/' . $n->id,
                'create_operation_label' => 'Розпочати розробку',
                'create_operation_url' => '/add-operation?note_id=' . $n->id,
            ];
        })->toArray();
    }
}
