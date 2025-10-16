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
     * Создание нового продукта с quantity
     */
    public function onLoadAddProductWithQuantity()
    {
        $operationId = post('_operation_id');
        $operation = Operation::find($operationId);

        if (!$operation) {
            throw new ApplicationException('Операция не найдена');
        }

        $this->vars['operation'] = $operation;
        return $this->makePartial('add_product_with_quantity');
    }

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
     * 🔹 Открывает таблицу с существующими продуктами
     */
    public function onLoadSelectExistingProduct()
    {
        $operationId = post('_operation_id');
        $operation = Operation::find($operationId);

        if (!$operation) {
            throw new ApplicationException('Операция не найдена');
        }

        $this->vars['operation'] = $operation;
        $this->vars['products'] = Product::all();

        return $this->makePartial('select_existing_product'); // partial с таблицей
    }

    /**
     * 🔹 Добавление выбранных продуктов из таблицы
     */
    public function onSelectExistingProduct()
    {
        $operationId = post('_operation_id');
        $operation = Operation::find($operationId);

        if (!$operation) {
            throw new ApplicationException('Операция не найдена');
        }

        $productsData = post('products', []);

        if (empty($productsData)) {
            throw new ApplicationException('Не выбраны продукты');
        }

        foreach ($productsData as $item) {
            if (!isset($item['id'])) continue;

            $productId = $item['id'];
            $quantity = $item['quantity'] ?? 1;

            $product = Product::find($productId);
            if (!$product) continue;

            $operation->products()->attach($product->id, ['quantity' => $quantity]);
        }

        return [
            '#Form-field-Operation-products-group' => $this->relationRender('products')
        ];
    }

    /**
     * После добавления через RelationController — обновляем pivot
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

    public function relationExtendPivotData($relationName, $model, $pivotData)
    {
        if ($relationName !== 'products') return $pivotData;

        $pivotData['quantity'] = post('quantity') ?? 0;
        return $pivotData;
    }

    public function formExtendFields($form)
    {
        $operation = $this->formGetModel();
        if (!$operation || !$operation->exists) {
            $this->vars['infoMessage'] = 'Сначала сохраните операцию, чтобы добавлять продукты';
        }
    }

    public function formBeforeSave($model)
    {
        Log::info('Operation form POST', post());
    }
}
