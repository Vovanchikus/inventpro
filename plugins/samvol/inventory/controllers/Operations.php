<?php namespace Samvol\Inventory\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Log;
use ApplicationException;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;

class Operations extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samvol.Inventory', 'inventory', 'operations');
    }

    /**
     * Открывает modal с формой создания продукта с quantity
     */
    public function onLoadAddProductWithQuantity()
    {
        $operationId = post('_operation_id');
        $operation = Operation::find($operationId);

        if (!$operation) {
            throw new ApplicationException('Операция не найдена');
        }

        $this->vars['operation'] = $operation;
        return $this->makePartial('add_product_with_quantity'); // partial с формой
    }

    /**
     * Создание нового продукта и добавление в pivot с quantity
     */
    public function onAddProductWithQuantity()
    {
        $operationId = post('_operation_id');
        $operation = Operation::find($operationId);

        if (!$operation) {
            throw new ApplicationException('Операция не найдена');
        }

        $data = post();

        $product = new Product();
        $product->name = $data['name'];
        $product->unit = $data['unit'];
        $product->save();

        $operation->products()->attach($product->id, ['quantity' => $data['quantity']]);

        return [
            '#Form-field-Operation-products-group' => $this->relationRender('products')
        ];
    }

    /**
     * Для RelationController: после добавления существующего продукта можно обновить pivot
     */
    public function relationAfterAdd($relationName, $relationModel)
    {
        if ($relationName !== 'products') return;

        $operationId = post('_operation_id');
        $operation = Operation::find($operationId);

        if (!$operation) {
            throw new ApplicationException('Операция не найдена');
        }

        $quantity = post('quantity') ?? 0;

        $operation->products()->updateExistingPivot($relationModel->id, ['quantity' => $quantity]);

        Log::info('Product added to operation pivot', [
            'operation_id' => $operation->id,
            'product_id'   => $relationModel->id,
            'quantity'     => $quantity
        ]);
    }

    /**
     * Для RelationController: берем quantity из POST при добавлении в pivot
     */
    public function relationExtendPivotData($relationName, $model, $pivotData)
    {
        if ($relationName !== 'products') return $pivotData;

        $pivotData['quantity'] = post('quantity') ?? 0;
        return $pivotData;
    }

    public function formExtendFields($form)
    {
        $operation = $this->formGetModel();
        if (!$operation || !$operation->exists)
        {
            $this->vars['infoMessage'] = 'Сначала сохраните операцию, что бы добавлять продукты';
        }
    }

    public function formBeforeSave($model)
    {
        Log::info('Operation form POST', post());
    }
}
