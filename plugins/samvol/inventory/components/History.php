<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\OperationProduct;
use Samvol\Inventory\Models\OperationType;
use Samvol\Inventory\Models\Document;
use Carbon\Carbon;

class History extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'История операций',
            'description' => 'Оптимизированная история операций'
        ];
    }

    public function onRun()
    {
        $filterType = get('type');
        $filterCounteragent = get('counteragent');
        $filterYear = get('year');

        $query = OperationProduct::with([
            'product:id,name,unit,inv_number,price',
            'operation:id,type_id',
            'operation.type:id,name',
            'operation.documents:id,operation_id,doc_date'
        ])->whereDoesntHave('operation', function ($q) {
            $q->whereIn('type_id', ['6', '7']);
        });

        if ($filterType) {
            $query->whereHas('operation', fn($q) => $q->where('type_id', $filterType));
        }

        if ($filterCounteragent) {
            $query->where('counteragent', $filterCounteragent);
        }

        if ($filterYear) {
            $query->whereHas('operation.documents', fn($q) =>
                $q->whereYear('doc_date', $filterYear)
            );
        }

        $histories = $query->get();

        /*
         |-------------------------------------------------------
         | Вычисляем дату ОДИН РАЗ, без аксессоров
         |-------------------------------------------------------
         */
        $histories->each(function ($item) {
            $doc = $item->operation->documents->sortBy('id')->first();
            $item->doc_date = $doc && $doc->doc_date
                ? Carbon::parse($doc->doc_date)->format('d.m.Y')
                : null;
        });

        $this->page['histories'] = $histories;
        $this->page['filteredCount'] = $histories->count();

        $this->page['types'] = OperationType::whereIn('id', [1,2,3,4])->get();

        $this->page['counteragents'] = OperationProduct::whereNotNull('counteragent')
            ->distinct()->pluck('counteragent');

        $this->page['years'] = Document::whereNotNull('doc_date')
            ->selectRaw('YEAR(doc_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');
    }
}
