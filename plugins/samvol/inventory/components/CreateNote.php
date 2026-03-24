<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Note;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\OperationType;
use DB;
use Log;

class CreateNote extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Создать заметку',
            'description' => 'Создание заметки и/или добавление товаров в заметку'
        ];
    }

    public function onShowCreateModal()
    {
        try {
            Log::info('[samvol] onShowCreateModal: start', [
                'ip' => request()->ip(),
                'ua' => request()->header('User-Agent'),
                'uri' => request()->getRequestUri(),
            ]);
        } catch (\Exception $e) {
            Log::warning('[samvol] onShowCreateModal: log start failed: ' . $e->getMessage());
        }

        $selected = post('selected_products', []);
        if (is_string($selected)) {
            $decoded = json_decode($selected, true);
            $selected = is_array($decoded) ? $decoded : [];
        }

        $html = $this->renderPartial('modals/create_note', [
            'selected' => is_array($selected) ? $selected : []
        ]);

        $response = [
            'modalContent' => $html,
            'modalType' => 'info',
            'modalTitle' => 'Создать заметку'
        ];

        try {
            Log::info('[samvol] onShowCreateModal: success', [
                'selected_count' => is_array($selected) ? count($selected) : 0,
                'has_content' => !empty($html),
            ]);
        } catch (\Exception $e) {
            Log::warning('[samvol] onShowCreateModal: log success failed: ' . $e->getMessage());
        }

        return $response;
    }

    public function onCreateNote()
    {
        $data = post();
        $organizationId = $this->organizationId();
        if ($organizationId <= 0) {
            return ['toast' => ['message' => 'Організація користувача не визначена', 'type' => 'error']];
        }

        try {
            Log::info('[samvol] onCreateNote: start', [
                'ip' => request()->ip(),
                'ua' => request()->header('User-Agent'),
                'uri' => request()->getRequestUri(),
                'has_note_id' => !empty($data['note_id']),
                'has_products' => !empty($data['products']),
                'title_len' => isset($data['title']) ? mb_strlen((string)$data['title']) : 0,
            ]);
        } catch (\Exception $e) {
            Log::warning('[samvol] onCreateNote: log start failed: ' . $e->getMessage());
        }

        // Ранняя рекурсивная проверка входных данных products — если найдены вложенные массивы
        // возвращаем понятную валидацию и логируем путь
        try {
            if (!empty($data['products'])) {
                $found = null;
                $checkRecursive = function($value, $path = '') use (&$checkRecursive, &$found) {
                    if ($found) return true;
                    if (is_array($value)) {
                        foreach ($value as $k => $v) {
                            $currentPath = $path === '' ? (string)$k : $path . '[' . $k . ']';
                            if (is_array($v)) {
                                // if nested array whose items are arrays, mark
                                foreach ($v as $subk => $subv) {
                                    if (is_array($subv)) {
                                        $found = $currentPath . '[' . $subk . ']';
                                        return true;
                                    }
                                }
                            }
                        }
                    }
                    return false;
                };

                if ($checkRecursive($data['products'], 'products')) {
                    Log::warning('[samvol] CreateNote: nested array detected in products at ' . $found);
                    return [
                        'validationErrors' => [ ['field' => $found ?? 'products', 'message' => 'Неподдерживаемая вложенная структура данных'] ],
                        'toast' => [ 'message' => 'Ошибка данных: найден вложенный массив в products', 'type' => 'error' ]
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('[samvol] CreateNote nested-check failed: ' . $e->getMessage());
        }

        DB::beginTransaction();
        try {
            // Создаём или обновляем заметку. Не создаём автоматически черновую операцию.
            if (!empty($data['note_id'])) {
                $note = Note::query()->where('id', $data['note_id'])->where('organization_id', $organizationId)->first();
                if (!$note) throw new \Exception('Заметка не найдена');
                $note->title = $data['title'] ?? $note->title;
                $note->description = $data['description'] ?? $note->description;
                $note->due_date = $data['due_date'] ?? $note->due_date;
            } else {
                $note = new Note();
                $note->organization_id = $organizationId;
                $note->title = $data['title'] ?? null;
                $note->description = $data['description'] ?? null;
                $note->due_date = $data['due_date'] ?? null;
                // По новой концепции при создании заметки статус — документы в разработке
                $note->status = 'in_development';
            }

            // Если пришли товары — сохраним их в заметке (не создаём операцию автоматически)
            if (!empty($data['products'])) {
                $products = is_string($data['products']) ? json_decode($data['products'], true) : $data['products'];
                $products = $products ?: [];
            } else {
                $products = [];
            }

            // Логируем полученные товары (для отладки) — безопасно сериализуем
            try {
                // debug logging removed
            } catch (\Exception $e) {
                \Log::warning('[samvol] CreateNote: failed to log incoming products: ' . $e->getMessage());
            }

            $note->save();

            // Привяжем товары через pivot (sync) — ожидание формата [{product_id, quantity, sum, counteragent}, ...]
            if (!empty($products)) {
                $syncData = [];
                foreach ($products as $pIndex => $p) {
                    if (!is_array($p)) {
                        Log::warning("[samvol] CreateNote: product entry not array at index {$pIndex}");
                        continue;
                    }

                    $pid = $p['product_id'] ?? $p['id'] ?? null;

                    // Если пришёл вложенный массив как id — попытаемся извлечь скаляр
                    if (is_array($pid)) {
                        if (isset($pid['id'])) $pid = $pid['id'];
                        elseif (isset($pid[0])) $pid = $pid[0];
                        else {
                            Log::warning("[samvol] CreateNote: product_id is nested array at index {$pIndex}");
                            $pid = null;
                        }
                    }

                    if (!$pid) continue;

                    // безопасно привести к int/string
                    $pidKey = is_numeric($pid) ? intval($pid) : trim((string)$pid);

                    $quantity = $p['quantity'] ?? 0;
                    if (is_array($quantity)) {
                        // если вдруг пришёл массив — возьмём первый элемент
                        $quantity = reset($quantity);
                    }

                    $sum = $p['sum'] ?? null;
                    if (is_array($sum)) {
                        $sum = reset($sum);
                    }

                    $counteragent = $p['counteragent'] ?? null;
                    if (is_array($counteragent)) {
                        $counteragent = reset($counteragent);
                    }

                    $syncData[$pidKey] = [
                        'organization_id' => $organizationId,
                        'quantity' => is_numeric($quantity) ? floatval($quantity) : 0,
                        'sum' => is_numeric($sum) ? floatval($sum) : ($sum === null ? null : (string)$sum),
                        'counteragent' => $counteragent,
                    ];
                }

                if (!empty($syncData)) {
                    try {
                        // debug logging removed
                    } catch (\Exception $e) {
                        Log::warning('[samvol] CreateNote: failed to json-encode syncData: ' . $e->getMessage());
                    }
                    $note->products()->sync($syncData);
                }
            }

            DB::commit();

            try {
                Log::info('[samvol] onCreateNote: success', [
                    'note_id' => $note->id,
                    'products_count' => is_array($products) ? count($products) : 0,
                ]);
            } catch (\Exception $e) {
                Log::warning('[samvol] onCreateNote: log success failed: ' . $e->getMessage());
            }

            return ['toast' => ['message' => 'Заметка сохранена', 'type' => 'success'], 'note_id' => $note->id];

        } catch (\Exception $e) {
            DB::rollBack();
            try {
                Log::error('[samvol] onCreateNote: failed', [
                    'message' => $e->getMessage(),
                    'ip' => request()->ip(),
                    'ua' => request()->header('User-Agent'),
                ]);
            } catch (\Exception $_) {}
            return ['toast' => ['message' => 'Ошибка: '.$e->getMessage(), 'type' => 'error']];
        }
    }

    protected function organizationId(): int
    {
        $user = \Auth::getUser();
        return (int) ($user->organization_id ?? 0);
    }
}
