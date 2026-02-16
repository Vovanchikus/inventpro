<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\OperationProduct;
use Samvol\Inventory\Models\OperationType;
use Samvol\Inventory\Models\Document;

class OperationInfo extends ComponentBase
{
    public $operations;
    public $operation_item;

    public function componentDetails()
    {
        return [
            'name'        => 'OperationInfo',
            'description' => 'Полная информация об операции'
        ];
    }

    public function onRun() {
        $status = get('status') ?: 'final';

        $filterType = get('type');
        $filterCounteragent = get('counteragent');
        $filterYear = get('year');

        $query = Operation::with(['products', 'documents', 'documents.doc_file', 'type', 'note'])
            ->whereIn('type_id', [1,2,3]);

        if ($status === 'draft') {
            $query->where('is_draft', true);
        } else {
            $query->where('is_draft', false)
                ->whereHas('documents', function($q){
                    $q->whereHas('doc_file');
                });
        }

        if ($filterType) {
            $query->where('type_id', $filterType);
        }

        if ($filterCounteragent) {
            $query->whereHas('products', function ($q) use ($filterCounteragent) {
                $q->where('samvol_inventory_operation_products.counteragent', $filterCounteragent);
            });
        }

        if ($filterYear) {
            $query->whereHas('documents', function ($q) use ($filterYear) {
                $q->whereYear('doc_date', $filterYear);
            });
        }

        $this->operations = $query->get();
        $this->page['operations'] = $this->operations;
        $this->page['status'] = $status;

        $this->page['draftCount'] = Operation::whereIn('type_id', [1,2,3])
            ->where('is_draft', true)
            ->count();

        $this->page['finalCount'] = Operation::whereIn('type_id', [1,2,3])
            ->where('is_draft', false)
            ->whereHas('documents', function($q){
                $q->whereHas('doc_file');
            })
            ->count();

        $this->page['types'] = OperationType::whereIn('id', [1,2,3])->get();

        $counteragentsQuery = OperationProduct::whereNotNull('counteragent')
            ->whereHas('operation', function ($q) use ($status) {
                $q->whereIn('type_id', [1,2,3]);

                if ($status === 'draft') {
                    $q->where('is_draft', true);
                } else {
                    $q->where('is_draft', false)
                        ->whereHas('documents', function($q){
                            $q->whereHas('doc_file');
                        });
                }
            });

        $this->page['counteragents'] = $counteragentsQuery
            ->distinct()
            ->pluck('counteragent');

        $this->page['years'] = Document::whereNotNull('doc_date')
            ->selectRaw('YEAR(doc_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $slug = $this->param('slug');
        $this->operation_item = Operation::with(['products', 'documents', 'documents.doc_file', 'type', 'note'])
            ->where('slug', $slug)
            ->first();
        $this->page['operation_item'] = $this->operation_item;
    }

    public function onDeleteDraft()
    {
        $user = \Auth::getUser();

        if (!$user || !$user->isInGroup('admin')) {
            throw new \ApplicationException('У вас нет прав на удаление операций!');
        }

        $id = post('id');
        if (!$id) {
            return [
                'toast' => [
                    'message' => 'Не указан ID операции',
                    'type' => 'error',
                    'timeout' => 5000,
                    'position' => 'top-center'
                ]
            ];
        }

        $operation = Operation::with(['products', 'documents'])->find($id);
        if (!$operation || !$operation->is_draft) {
            return [
                'toast' => [
                    'message' => 'Черновик не найден',
                    'type' => 'error',
                    'timeout' => 5000,
                    'position' => 'top-center'
                ]
            ];
        }

        try {
            if ($operation->products) {
                $operation->products()->detach();
            }
            if ($operation->documents) {
                $operation->documents()->delete();
            }
            $operation->delete();

            return [
                'toast' => [
                    'message' => 'Черновик удалён',
                    'type' => 'success',
                    'timeout' => 4000,
                    'position' => 'top-center'
                ]
            ];
        } catch (\Exception $e) {
            return [
                'toast' => [
                    'message' => 'Ошибка при удалении: ' . $e->getMessage(),
                    'type' => 'error',
                    'timeout' => 6000,
                    'position' => 'top-center'
                ]
            ];
        }
    }

    public function defineProperties()
    {
        return [];
    }
}
