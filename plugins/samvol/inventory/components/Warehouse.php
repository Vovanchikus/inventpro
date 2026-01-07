<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\Category;
use DB;
use Input;
use Validator;
use ValidationException;
use Storage;

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

    public function onUploadProductImage()
    {
        $files = Input::file('images') ?: [Input::file('image')]; // поддержка нескольких файлов или одного
        $productId = post('product_id');
        $product = Product::find($productId);

        if (!$files || !$product) {
            return ['error' => 'Файл не выбран или товар не найден'];
        }

        $uploadedPaths = [];

        foreach ($files as $file) {
            try {
                $product->images()->create(['data' => $file]);
                $lastImage = $product->images->last();
                if ($lastImage) {
                    $uploadedPaths[] = $lastImage->getPath();
                }
            } catch (\Exception $e) {
                return ['error' => 'Ошибка при загрузке изображения'];
            }
        }

        return ['image_paths' => $uploadedPaths];
    }



    /* -----------------------------------------------------------------
     | Ajax
     |-----------------------------------------------------------------*/

    public function onAssignCategory()
    {
        $productIds = post('product_ids');
        $categoryId = post('category_id');

        if (!is_array($productIds) || empty($productIds)) {
            return $this->toast('Товары не выбраны', 'error');
        }

        if (!$categoryId) {
            return $this->toast('Категория не выбрана', 'error');
        }

        $category = Category::withCount('children')->find($categoryId);

        if (!$category) {
            return $this->toast('Категория не найдена', 'error');
        }

        if ($category->children_count > 0) {
            return $this->toast('В родительскую категорию нельзя добавлять товары', 'error');
        }

        DB::transaction(function () use ($productIds, $categoryId) {
            Product::whereIn('id', $productIds)
                ->update(['category_id' => $categoryId]);
        });

        return $this->toast('Товары добавлены в категорию', 'success');
    }



    /* -----------------------------------------------------------------
     | Page
     |-----------------------------------------------------------------*/

    public function onRun()
    {
        $this->products = Product::query()
            ->select([
                'id',
                'name',
                'slug',
                'inv_number',
                'unit',
                'price',
                'category_id',
            ])
            ->with('category.parent.parent.parent') // подгружаем родителей до нужной глубины
            ->orderBy('name')
            ->get();

        $this->categories = Category::query()
            ->select(['id', 'name', 'desc', 'parent_id', 'nest_left'])
            ->with('children')
            ->whereNull('parent_id')
            ->orderBy('nest_left')
            ->get();

        $this->page['products']   = $this->products;
        $this->page['categories'] = $this->categories;
    }


    /* -----------------------------------------------------------------
     | Helpers
     |-----------------------------------------------------------------*/

    protected function toast($message, $type = 'success')
    {
        return [
            'toast' => [
                'message'  => $message,
                'type'     => $type,
                'timeout'  => 4000,
                'position' => 'top-center'
            ]
        ];
    }

    public function defineProperties()
    {
        return [];
    }
}
