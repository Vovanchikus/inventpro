<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\OperationProduct;
use Samvol\Inventory\Models\OperationType;

class History extends ComponentBase
{
    public $histories;
    public $types;
    public $counteragents;

    public function componentDetails()
    {
        return [
            'name'        => 'История операций',
            'description' => 'Вывод информации об истории операций'
        ];
    }

    // Основной метод — выполняется при загрузке страницы
    public function onRun()
    {
        // Получаем значения фильтров из GET-параметров
        $filterType = get('type');
        $filterCounteragent = get('counteragent');

        // Базовый запрос: исключаем типы 6 и 7
        $query = OperationProduct::whereDoesntHave('operation', function($q) {
            $q->whereIn('type_id', ['6', '7']);
        });

        // Фильтр по типу операции
        if (!empty($filterType)) {
            $query->whereHas('operation', function($q) use ($filterType) {
                $q->where('type_id', $filterType);
            });
        }

        // Фильтр по контрагенту
        if (!empty($filterCounteragent)) {
            $query->where('counteragent', $filterCounteragent);
        }

        // Получаем отфильтрованные продукты
        $this->histories = $query->get();
        $this->page['histories'] = $this->histories;

        // Подсчет количества подходящих продуктов
        $this->page['filteredCount'] = $this->histories->count();

        // Список всех типов для фильтра
        $this->types = OperationType::whereIn('id', ['1','2','3','4'])->get();
        $this->page['types'] = $this->types;

        // Список всех контрагентов для фильтра
        $this->counteragents = OperationProduct::distinct()->pluck('counteragent');
        $this->page['counteragents'] = $this->counteragents;
    }


    public function defineProperties()
    {
        return [];
    }
}
