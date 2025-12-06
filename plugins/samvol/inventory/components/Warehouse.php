<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\Category;
use Log;

class Warehouse extends ComponentBase
{
    public $products;
    public $categories;


    public function componentDetails()
    {
        return [
            'name'        => 'Склад',
            'description' => 'Все данные со склада'
        ];
    }


    public function onAssignCategory()
    {
        $productIds = post('product_ids');
        $categoryId = post('category_id');

        if (!$productIds || !is_array($productIds)) {
            return [
                'toast' => [
                    'message' => 'Товары не выбраны',
                    'type' => 'error',
                    'timeout' => 4000,
                    'position' => 'top-center'
                ]
            ];
        }

        if (!$categoryId) {
            return [
                'toast' => [
                    'message' => 'Категория не выбрана',
                    'type' => 'error',
                    'timeout' => 4000,
                    'position' => 'top-center'
                ]
            ];
        }

        // Загружаем категорию вместе с дочерними
        $category = Category::with('children')->find($categoryId);

        if (!$category) {
            return [
                'toast' => [
                    'message' => 'Категория не найдена',
                    'type' => 'error',
                    'timeout' => 4000,
                    'position' => 'top-center'
                ]
            ];
        }

        // ❗ Запрещаем добавлять товары в родительские категории
        if ($category->children->count() > 0) {
            return [
                'toast' => [
                    'message' => 'В родительскую категорию нельзя добавлять товары',
                    'type' => 'error',
                    'timeout' => 4000,
                    'position' => 'top-center'
                ]
            ];
        }

        // Обновление категории товаров
        Product::whereIn('id', $productIds)->update([
            'category_id' => $categoryId
        ]);

        return [
            'toast' => [
                'message' => 'Товары добавлены в категорию',
                'type' => 'success',
                'timeout' => 4000,
                'position' => 'top-center'
            ]
        ];
    }





    public function onRun(){
        $this->products = Product::all();
        $this->page['products'] = $this->products;

        $this->categories = Category::withCount('children')
            ->orderBy('nest_left')
            ->get();

        $this->page['categories'] = $this->categories;
    }

    /**
     * Returns the properties provided by the component
     */
    public function defineProperties()
    {
        return [];
    }
}
