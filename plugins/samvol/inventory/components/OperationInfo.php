<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\OperationProduct;
use Samvol\Inventory\Models\OperationType;
use Samvol\Inventory\Models\Document;
use Carbon\Carbon;
use DB;

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
        $slug = $this->param('slug');

        if ($slug) {
            $this->operation_item = Operation::with(['products', 'documents', 'documents.doc_file', 'type', 'note'])
                ->where('slug', $slug)
                ->first();
            $this->page['operation_item'] = $this->operation_item;

            return;
        }

        $status = get('status') ?: 'final';

        $filterType = get('type');
        $filterCounteragent = get('counteragent');
        $filterYear = get('year');

        $documentsMetaSub = Document::query()
            ->selectRaw('operation_id, MIN(id) as first_doc_id, MAX(doc_date) as latest_doc_date')
            ->groupBy('operation_id');

        $firstCounteragentSub = OperationProduct::query()
            ->selectRaw('operation_id, MIN(id) as first_operation_product_id')
            ->groupBy('operation_id');

        $query = Operation::query()
            ->select([
                'samvol_inventory_operations.id',
                'samvol_inventory_operations.slug',
                'samvol_inventory_operations.type_id',
                'samvol_inventory_operations.is_draft',
                'samvol_inventory_operations.note_id',
                'samvol_inventory_operations.draft_products',
                'samvol_inventory_operations.created_at',
                DB::raw('first_doc.doc_num as card_document_number'),
                DB::raw('first_doc.doc_purpose as card_document_purpose'),
                DB::raw('first_doc.doc_date as card_document_date_raw'),
                DB::raw('doc_meta.latest_doc_date as latest_doc_date'),
                DB::raw('first_op.counteragent as first_counteragent'),
            ])
            ->leftJoinSub($documentsMetaSub, 'doc_meta', function ($join) {
                $join->on('doc_meta.operation_id', '=', 'samvol_inventory_operations.id');
            })
            ->leftJoin('samvol_inventory_documents as first_doc', 'first_doc.id', '=', 'doc_meta.first_doc_id')
            ->leftJoinSub($firstCounteragentSub, 'first_counteragent_meta', function ($join) {
                $join->on('first_counteragent_meta.operation_id', '=', 'samvol_inventory_operations.id');
            })
            ->leftJoin('samvol_inventory_operation_products as first_op', 'first_op.id', '=', 'first_counteragent_meta.first_operation_product_id')
            ->with([
                'type:id,name',
                'note:id,title',
            ])
            ->withCount('products')
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

        $this->operations = $query
            ->orderByRaw('COALESCE(latest_doc_date, samvol_inventory_operations.created_at) DESC')
            ->get()
            ->each(function ($operation) {
                $operation->setAttribute('items_count', (int) ($operation->products_count ?? 0));

                if (!empty($operation->card_document_date_raw)) {
                    $operation->setAttribute(
                        'card_document_date',
                        Carbon::parse($operation->card_document_date_raw)->format('d.m.Y')
                    );
                } else {
                    $operation->setAttribute('card_document_date', null);
                }
            })
            ->values();
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

        $this->page['operation_item'] = null;
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
