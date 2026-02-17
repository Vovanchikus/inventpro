<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\OperationProduct;
use Samvol\Inventory\Models\OperationType;
use Samvol\Inventory\Models\Document;
use Samvol\Inventory\Models\Product;
use Carbon\Carbon;
use DB;

class History extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'История операций',
            'description' => 'История операций (общая или по товару)'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Slug товара',
                'description' => 'Slug товара из URL (необязательный)',
                'type'        => 'string',
                'default'     => null,
            ],
        ];
    }

    public function onRun()
    {
        /*
         |-------------------------------------------------------
         | 1. Товар (ЕСЛИ есть slug)
         |-------------------------------------------------------
         */
        $product = null;

        if ($this->property('slug')) {
            $product = Product::where('slug', $this->property('slug'))->first();
        }

        $this->page['product'] = $product;

        /*
         |-------------------------------------------------------
         | 2. Фильтры
         |-------------------------------------------------------
         */
        $filterType         = get('type');
        $filterCounteragent = get('counteragent');
        $filterYear         = get('year');

        /*
         |-------------------------------------------------------
         | 3. Базовый запрос истории
         |-------------------------------------------------------
         */
        $documentsMetaSub = Document::query()
            ->selectRaw('operation_id, MIN(id) as first_doc_id, MAX(doc_date) as latest_doc_date')
            ->groupBy('operation_id');

        $query = OperationProduct::query()
        ->select([
            'samvol_inventory_operation_products.*',
            DB::raw('first_doc.doc_date as first_doc_date'),
            DB::raw('doc_meta.latest_doc_date as latest_doc_date'),
        ])
        ->leftJoinSub($documentsMetaSub, 'doc_meta', function ($join) {
            $join->on('doc_meta.operation_id', '=', 'samvol_inventory_operation_products.operation_id');
        })
        ->leftJoin('samvol_inventory_documents as first_doc', 'first_doc.id', '=', 'doc_meta.first_doc_id')
        ->with([
            'product:id,name,unit,inv_number,price',
            'operation:id,type_id',
            'operation.type:id,name',
        ])
        ->whereDoesntHave('operation', function ($q) {
            $q->whereIn('type_id', [6, 7]);
        });

        /*
         |-------------------------------------------------------
         | 4. Ограничение по товару (ТОЛЬКО если есть slug)
         |-------------------------------------------------------
         */
        if ($product) {
            $query->where('product_id', $product->id);
        }

        /*
         |-------------------------------------------------------
         | 5. Фильтры (как у тебя)
         |-------------------------------------------------------
         */
        if ($filterType) {
            $query->whereHas('operation', function ($q) use ($filterType) {
                $q->where('type_id', $filterType);
            });
        }

        if ($filterCounteragent) {
            $query->where('counteragent', $filterCounteragent);
        }

        if ($filterYear) {
            $query->whereHas('operation.documents', function ($q) use ($filterYear) {
                $q->whereYear('doc_date', $filterYear);
            });
        }

        // 6. Получаем историю в порядке последних документов
        $histories = $query
            ->orderByDesc('latest_doc_date')
            ->get();

        // 7. Дата документа (первый документ операции)
        $histories->each(function ($item) {
            $item->doc_date = $item->first_doc_date
                ? Carbon::parse($item->first_doc_date)->format('d.m.Y')
                : null;
        });

        /*
         |-------------------------------------------------------
         | 8. Данные в шаблон
         |-------------------------------------------------------
         */
        $this->page['histories']      = $histories;
        $this->page['filteredCount'] = $histories->count();

        $this->page['types'] = OperationType::whereIn('id', [1, 2, 3, 4])->get();

        /*
         |-------------------------------------------------------
         | 9. Контрагенты
         |-------------------------------------------------------
         */
        $counteragentsQuery = OperationProduct::whereNotNull('counteragent');

        if ($product) {
            $counteragentsQuery->where('product_id', $product->id);
        }

        $this->page['counteragents'] = $counteragentsQuery
            ->distinct()
            ->pluck('counteragent');

        /*
         |-------------------------------------------------------
         | 10. Годы документов
         |-------------------------------------------------------
         */
        $this->page['years'] = Document::whereNotNull('doc_date')
            ->selectRaw('YEAR(doc_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');
    }
}
