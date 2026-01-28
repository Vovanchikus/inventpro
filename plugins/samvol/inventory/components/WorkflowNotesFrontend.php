<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\WorkflowNote;
use Samvol\Inventory\Models\WorkflowNoteItem;
use Samvol\Inventory\Models\Operation;

class WorkflowNotesFrontend extends ComponentBase
{
    public $notes;

    public function componentDetails()
    {
        return [
            'name'        => 'Workflow Notes Frontend',
            'description' => 'Вывод списка умных заметок с товарами и операциями, интерактивные действия.'
        ];
    }

    public function defineProperties()
    {
        return [
            'status' => [
                'title'       => 'Статус фильтра',
                'description' => 'completed — только завершенные, open — открытые, пусто — все',
                'type'        => 'string',
                'default'     => ''
            ],
            'deadlineDays' => [
                'title'       => 'Сроки (дней)',
                'description' => 'Если задано, покажет заметки с дедлайном в пределах N дней',
                'type'        => 'string',
                'default'     => ''
            ]
        ];
    }

    public function onRun()
    {
        $this->notes = $this->loadNotes();
        $this->page['notes'] = $this->notes;
    }

    protected function loadNotes()
    {
        $status = trim($this->property('status'));
        $deadlineDays = trim($this->property('deadlineDays'));

        $query = WorkflowNote::with(['items', 'operations', 'operations.type'])
            ->orderBy('deadline_at');

        // Фильтр по дедлайну
        if (is_numeric($deadlineDays) && intval($deadlineDays) > 0) {
            $maxDays = intval($deadlineDays);
            $query->whereBetween('deadline_at', [now(), now()->addDays($maxDays)]);
        }

        $notes = $query->get();

        // Применяем фильтр по статусу через accessors
        if ($status === 'completed') {
            $notes = $notes->filter(fn($n) => $n->is_completed);
        } elseif ($status === 'open') {
            $notes = $notes->filter(fn($n) => !$n->is_completed);
        }

        return $notes->values();
    }



    /**
     * AJAX: добавить операцию к заметке
     */
    public function onAddOperation()
    {
        $noteId = post('note_id');
        $typeId = post('type_id');

        if (!$noteId || !$typeId) return ['error' => 'Missing note_id or type_id'];

        $note = WorkflowNote::find($noteId);
        if (!$note) return ['error' => 'Note not found'];

        $op = new Operation();
        $op->type_id = $typeId;
        $op->save();

        // Не привязываем операцию к заметке напрямую без указания workflow_note_item_id
        $op->load('type');

        return [
            'success'   => true,
            'operation' => [
                'id'         => $op->id,
                'type'       => $op->type->name ?? null,
                'created_at' => $op->created_at?->format('d.m.Y H:i')
            ]
        ];
    }

    /**
   * AJAX: показать модалку создания заметки
   */
  public function onShowCreateModal()
  {
      $selected = post('selected_products') ?: [];
      $html = $this->renderPartial('modals/create_note.htm', ['selected' => $selected]);
      return ['success' => true, 'modalContent' => $html];
  }

  /**
   * AJAX: создать заметку с товарами
   */
  public function onCreateNote()
  {
      $title       = post('title');
      $description = post('description');
      $deadline    = post('deadline_at');
      $productIds  = post('product_ids'); // массив выбранных товаров

      if (!$title || !$productIds) {
          return ['error' => 'Нужно заполнить название и выбрать товары'];
      }

      $note = new \Samvol\Inventory\Models\WorkflowNote();
      $note->title = $title;
      $note->description = $description;
      $note->deadline_at = $deadline;
      $note->save();

      // Привязка товаров
      foreach ($productIds as $pid) {
          $note->items()->create([
              'product_id' => $pid,
              'required_quantity' => 1, // можно менять
              'completed_quantity' => 0
          ]);
      }

      return [
          'success' => true,
          'note_id' => $note->id,
          'message' => 'Заметка создана и товары добавлены'
      ];
  }

  /**
   * AJAX: добавить товары в существующую заметку
   */
  public function onAddToExistingNote()
  {
      $noteId     = post('note_id');
      $productIds = post('product_ids');

      $note = \Samvol\Inventory\Models\WorkflowNote::find($noteId);
      if (!$note || empty($productIds)) {
          return ['error' => 'Заметка не найдена или товары не выбраны'];
      }

      foreach ($productIds as $pid) {
          $note->items()->create([
              'product_id' => $pid,
              'required_quantity' => 1,
              'completed_quantity' => 0
          ]);
      }

      return [
          'success' => true,
          'message' => 'Товары добавлены в заметку'
      ];
  }

  /**
   * AJAX: показать модалку выбора заметки для добавления товаров
   */
  public function onShowAddModal()
  {
      $selected = post('selected_products') ?: [];
      $html = $this->renderPartial('modals/add_to_note.htm', ['selected' => $selected]);
      return ['success' => true, 'modalContent' => $html];
  }


    /**
     * AJAX: добавить операцию к заметке и обновить статусы товаров
     */
    public function onAddOperationToNote()
    {
        $noteId = post('note_id');
        $operationId = post('operation_id');

        if (!$noteId || !$operationId) {
            return ['error' => 'Missing note_id or operation_id'];
        }

        // Загружаем заметку и операцию
        $note = WorkflowNote::with('items')->find($noteId);
        $operation = Operation::with('products')->find($operationId);

        if (!$note || !$operation) {
            return ['error' => 'Note or operation not found'];
        }

        $updatedItems = [];

        // Привязываем каждый товар заметки к операции через workflow_note_item_id
        foreach ($note->items as $noteItem) {
            // Ищем соответствующий товар в операции (в relation `products`, количество в pivot)
            $opProduct = $operation->products->first(fn($p) => $p->inventory_number === $noteItem->product->inventory_number);

            if ($opProduct) {
                $opQuantity = $opProduct->pivot->quantity ?? 0;
                // Обновляем количество выполненных
                $noteItem->completed_quantity += $opQuantity;
                if ($noteItem->completed_quantity >= $noteItem->required_quantity) {
                    $noteItem->completed_quantity = $noteItem->required_quantity;
                }
                $noteItem->save();

                // Привязка операции к товару заметки через pivot
                $note->operations()->attach($operation->id, [
                    'workflow_note_item_id' => $noteItem->id,
                ]);

                $updatedItems[] = [
                    'id' => $noteItem->id,
                    'completed_quantity' => $noteItem->completed_quantity,
                    'is_closed' => $noteItem->is_closed
                ];
            }
        }

        // Обновляем статус заметки
        $note->refresh();
        $note->is_completed = $note->items->every(fn($i) => $i->is_closed);
        $note->save();

        return [
            'success' => true,
            'note_id' => $note->id,
            'is_completed' => $note->is_completed,
            'items' => $updatedItems
        ];
    }

    /**
     * AJAX: показать модалку выбора операции для заметки
     */
    public function onShowAddOperationModal()
    {
        $noteId = post('note_id');
        $note = WorkflowNote::with('operations')->find($noteId);
        if (!$note) return ['error' => 'Заметка не найдена'];

        // Берем все операции (можно фильтровать по типу или дате)
        $operations = Operation::with('type')
            ->whereNotIn('id', $note->operations->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get();

        $html = $this->renderPartial('modals/add_operation.htm', [
            'note' => $note,
            'operations' => $operations
        ]);

        return ['success' => true, 'modalContent' => $html];
    }

}
