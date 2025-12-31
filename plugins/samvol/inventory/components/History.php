<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\OperationProduct;
use Samvol\Inventory\Models\OperationType;
use Samvol\Inventory\Models\Document;
use Samvol\Inventory\Models\Product;
use Carbon\Carbon;

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
        $query = OperationProduct::with([
            'product:id,name,unit,inv_number,price',
            'operation:id,type_id',
            'operation.type:id,name',
            'operation.documents:id,operation_id,doc_date,doc_name,doc_num'
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

        // 6. Получаем историю и сортируем по последнему документу
        $histories = $query->get()->sortByDesc(function($item) {
            $lastDoc = $item->operation->documents->sortByDesc('doc_date')->first();
            return $lastDoc ? $lastDoc->doc_date : null;
        });

        // 7. Дата документа (первый документ операции)
        $histories->each(function ($item) {
            $firstDoc = $item->operation->documents->sortBy('id')->first();
            $item->doc_date = $firstDoc && $firstDoc->doc_date
                ? Carbon::parse($firstDoc->doc_date)->format('d.m.Y')
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
