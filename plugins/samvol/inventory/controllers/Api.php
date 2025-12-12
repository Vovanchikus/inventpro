<?php namespace Samvol\Inventory\Controllers;

use Backend\Classes\Controller;
use Illuminate\Support\Facades\Response;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Document;
use Samvol\Inventory\Models\Category;
use Samvol\Inventory\Models\OperationType;

use Samvol\Inventory\Classes\Transformers\ProductTransformer;
use Samvol\Inventory\Classes\Transformers\OperationTransformer;
use Samvol\Inventory\Classes\Transformers\DocumentTransformer;

class Api extends Controller
{
    public $implement = [];

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */
    public function products()
    {
        $items = Product::all();

        return $this->success(ProductTransformer::collection($items));
    }

    public function product($id)
    {
        $item = Product::find($id);

        if (!$item)
            return $this->error("Product not found", 404);

        return $this->success(ProductTransformer::one($item));
    }

    /*
    |--------------------------------------------------------------------------
    | Operations
    |--------------------------------------------------------------------------
    */
    public function operations()
    {
        $items = Operation::with('products', 'documents')->get();

        return $this->success(OperationTransformer::collection($items));
    }

    public function operation($id)
    {
        $item = Operation::with('products', 'documents')->find($id);

        if (!$item)
            return $this->error("Operation not found", 404);

        return $this->success(OperationTransformer::one($item));
    }

    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */
    public function documents()
    {
        $items = Document::all();

        return $this->success(DocumentTransformer::collection($items));
    }

    public function document($id)
    {
        $item = Document::find($id);

        if (!$item)
            return $this->error("Document not found", 404);

        return $this->success(DocumentTransformer::one($item));
    }

    public function documentFile($id)
    {
        $doc = Document::find($id);

        if (!$doc || !$doc->doc_file)
            abort(404, "Document file not found");

        $file = $doc->doc_file;
        $path = $file->getLocalPath();

        return Response::make(
            file_get_contents($path),
            200,
            [
                'Content-Type'        => $file->content_type ?: 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$file->file_name.'"',
                'Content-Length'      => filesize($path),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */
    // Список всех категорий
    public function categories()
    {
        $categories = Category::with('children')->get();

        return $this->success(
            $categories->map(fn($cat) => [
                'id'         => $cat->id,
                'name'       => $cat->name,
                'parent_id'  => $cat->parent_id,
                'slug'       => $cat->slug,
                'children'   => $cat->children->map(fn($c) => [
                    'id'   => $c->id,
                    'name' => $c->name,
                    'slug' => $c->slug
                ]),
            ])
        );
    }

    // Конкретная категория
    public function category($id)
    {
        $cat = Category::with('children')->find($id);

        if (!$cat)
            return $this->error("Category not found", 404);

        return $this->success([
            'id'         => $cat->id,
            'name'       => $cat->name,
            'parent_id'  => $cat->parent_id,
            'slug'       => $cat->slug,
            'children'   => $cat->children->map(fn($c) => [
                'id'   => $c->id,
                'name' => $c->name,
                'slug' => $c->slug
            ]),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Operation Types
    |--------------------------------------------------------------------------
    */
    public function operationTypes()
    {
        $items = OperationType::all();

        return $this->success(
            $items->map(fn($type) => [
                'id'   => $type->id,
                'name' => $type->name,
            ])
        );
    }

    public function operationType($id)
    {
        $type = OperationType::find($id);

        if (!$type)
            return $this->error("Operation type not found", 404);

        return $this->success([
            'id'   => $type->id,
            'name' => $type->name,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Warehouse Products (с категориями)
    |--------------------------------------------------------------------------
    */
    public function warehouseProducts()
    {
        $products = Product::with('category')->get();

        return $this->success(
            $products->map(fn($p) => [
                'id'          => $p->id,
                'name'        => $p->name,
                'unit'        => $p->unit,
                'inv_number'  => $p->inv_number,
                'price'       => $p->price,
                'quantity'    => $p->calculated_quantity,
                'sum'         => $p->calculated_sum,
                'category_id' => $p->category_id,
                'category'    => $p->category ? [
                    'id'   => $p->category->id,
                    'name' => $p->category->name,
                    'slug' => $p->category->slug
                ] : null,
            ])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    private function success($data)
    {
        return Response::json([
            'success' => true,
            'data'    => $data,
            'error'   => null,
        ]);
    }

    private function error($message, $code = 400)
    {
        return Response::json([
            'success' => false,
            'data'    => null,
            'error'   => $message,
        ], $code);
    }

    /*
    |--------------------------------------------------------------------------
    | Counteragents
    |--------------------------------------------------------------------------
    */

    // Список всех уникальных контрагентов
    public function counteragents()
    {
        $counteragents = \Samvol\Inventory\Models\OperationProduct::select('counteragent')
            ->whereNotNull('counteragent')
            ->distinct()
            ->orderBy('counteragent')
            ->pluck('counteragent');

        return $this->success($counteragents);
    }

    // Получить контрагента по имени (или id, если будет поле)
    public function counteragent($name)
    {
        $exists = \Samvol\Inventory\Models\OperationProduct::where('counteragent', $name)->exists();

        if (!$exists)
            return $this->error("Counteragent not found", 404);

        return $this->success(['name' => $name]);
    }

}
