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

        return [
            'image_paths' => $uploadedPaths,
            'images' => $this->formatProductImages($product),
        ];
    }

    public function onDeleteProductImage()
    {
        $productId = post('product_id');
        $imageId = post('image_id');

        $product = Product::find($productId);

        if (!$product || !$imageId) {
            return ['error' => 'Некорректные данные для удаления изображения'];
        }

        $image = $product->images()->where('id', $imageId)->first();

        if (!$image) {
            return ['error' => 'Изображение не найдено'];
        }

        try {
            $image->delete();
        } catch (\Exception $e) {
            return ['error' => 'Ошибка при удалении изображения'];
        }

        return [
            'images' => $this->formatProductImages($product),
        ];
    }

    public function onReorderProductImages()
    {
        $productId = post('product_id');
        $order = post('order');

        if (!is_array($order) && is_string($order)) {
            $decoded = json_decode($order, true);
            if (is_array($decoded)) {
                $order = $decoded;
            } else {
                $order = array_filter(array_map('trim', explode(',', $order)), function ($value) {
                    return $value !== '';
                });
            }
        }

        $product = Product::find($productId);

        if (!$product || !is_array($order)) {
            return ['error' => 'Некорректные данные для сортировки изображений'];
        }

        $validIds = $product->images()->pluck('id')->map(function ($id) {
            return (int) $id;
        })->all();

        $filteredOrder = array_values(array_filter(array_map(function ($id) {
            return (int) $id;
        }, $order), function ($id) use ($validIds) {
            return in_array($id, $validIds, true);
        }));

        if (empty($filteredOrder)) {
            return ['error' => 'Список изображений для сортировки пуст'];
        }

        foreach ($filteredOrder as $index => $fileId) {
            $image = $product->images()->where('id', $fileId)->first();
            if (!$image) {
                continue;
            }

            $image->sort_order = $index + 1;
            $image->save();
        }

        return [
            'images' => $this->formatProductImages($product),
        ];
    }

    protected function formatProductImages(Product $product)
    {
        return $product->images()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'path' => $image->getPath(),
                    'width' => $image->width,
                    'height' => $image->height,
                ];
            })
            ->values()
            ->all();
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
            ->orderBy('name')
            ->get();

        if ($this->products->isNotEmpty()) {
            $productIds = $this->products->pluck('id')->all();

            $balances = DB::table('samvol_inventory_operation_products as op')
                ->join('samvol_inventory_operations as o', 'op.operation_id', '=', 'o.id')
                ->join('samvol_inventory_operation_types as t', 'o.type_id', '=', 't.id')
                ->whereIn('op.product_id', $productIds)
                ->groupBy('op.product_id')
                ->selectRaw(
                    "op.product_id,
                    GREATEST(SUM(CASE
                        WHEN LOWER(t.name) = 'приход' THEN op.quantity
                        WHEN LOWER(t.name) = 'передача' THEN -op.quantity
                        WHEN LOWER(t.name) = 'списание' THEN -op.quantity
                        WHEN LOWER(t.name) = 'импорт' THEN op.quantity
                        WHEN LOWER(t.name) = 'импорт приход' THEN op.quantity
                        WHEN LOWER(t.name) = 'импорт расход' THEN -op.quantity
                        ELSE 0
                    END), 0) as calculated_quantity,
                    GREATEST(SUM(CASE
                        WHEN LOWER(t.name) = 'приход' THEN op.sum
                        WHEN LOWER(t.name) = 'передача' THEN -op.sum
                        WHEN LOWER(t.name) = 'списание' THEN -op.sum
                        WHEN LOWER(t.name) = 'импорт' THEN op.sum
                        WHEN LOWER(t.name) = 'импорт приход' THEN op.sum
                        WHEN LOWER(t.name) = 'импорт расход' THEN -op.sum
                        ELSE 0
                    END), 0) as calculated_sum"
                )
                ->get()
                ->keyBy('product_id');

            $this->products->each(function (Product $product) use ($balances) {
                $balance = $balances->get($product->id);

                $product->setAttribute('calculated_quantity', $balance ? (float) $balance->calculated_quantity : 0);
                $product->setAttribute('calculated_sum', $balance ? (float) $balance->calculated_sum : 0);
            });
        }

        $this->categories = Category::query()
            ->select(['id', 'name', 'desc', 'parent_id', 'nest_left', 'nest_depth'])
            ->with([
                'children' => function ($query) {
                    $query->select(['id', 'name', 'desc', 'parent_id', 'nest_left'])
                        ->orderBy('nest_left');
                },
                'children.children' => function ($query) {
                    $query->select(['id', 'name', 'desc', 'parent_id', 'nest_left'])
                        ->orderBy('nest_left');
                },
            ])
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
