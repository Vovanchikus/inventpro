<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Note;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\OperationType;
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

    public function onRun()
    {
        // Eager load operations with products/documents to render cards without extra queries
        $raw = Note::with(['operations.products','operations.documents'])->orderBy('due_date', 'asc')->get();
        $this->notes = $raw;
        // Normalize notes for page consumption (ensure products array shape)
        $this->page['notes'] = $this->normalizeNotes($raw);
    }

    public function onListNotes()
    {
        $raw = Note::with(['operations.products','operations.documents'])->orderBy('due_date', 'asc')->get();
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

    /**
     * Normalize a collection of Note models into an array suitable for Twig rendering.
     * Keeps products and operations flattened with necessary pivot fields.
     */
    protected function normalizeNotes($collection)
    {
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

        // Preload raw JSON 'products' column for notes where needed
        $rawProductsByNote = [];
        if (!empty($noteIds)) {
            try {
                $rows = \DB::table('samvol_inventory_notes')->whereIn('id', $noteIds)->pluck('products', 'id');
                foreach ($rows as $nid => $raw) {
                    $rawProductsByNote[$nid] = $raw;
                }
            } catch (\Exception $e) {
                \Log::warning('[samvol] normalizeNotes: preload raw products failed - ' . $e->getMessage());
            }
        }

        return collect($collection)->map(function($n) use ($pivotRowsGrouped, $rawProductsByNote) {
            // Prepare operations list
            $ops = $n->operations->map(function($o){
                return [
                    'id' => $o->id,
                    'is_draft' => !empty($o->is_draft),
                    'is_posted' => !empty($o->is_posted),
                    'type' => $o->type?->name ?? null,
                    'documents_count' => $o->documents->count(),
                    'products' => $o->products->map(function($p){
                        return [
                            'inv_number' => $p->inv_number,
                            'name' => $p->name,
                            'quantity' => $p->pivot->quantity,
                        ];
                    })->toArray()
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

            return [
                'id' => $n->id,
                'title' => $n->title,
                'description' => $n->description,
                'due_date' => $n->due_date,
                'status' => $n->status,
                'human_status' => $n->human_status ?? null,
                'products' => $prodList,
                'operations' => $ops,
            ];
        })->toArray();
    }
}
