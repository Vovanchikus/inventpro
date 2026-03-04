<?php namespace Samvol\Inventory\Controllers;

use Backend\Classes\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use System\Models\File;

use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Document;
use Samvol\Inventory\Models\Category;
use Samvol\Inventory\Models\OperationType;
use Samvol\Inventory\Models\OperationProduct;
use Samvol\Inventory\Classes\DocumentTemplateSettings;
use Samvol\Inventory\Classes\OrganizationAccess;
use Samvol\Inventory\Classes\SettingsScopeResolver;

use Samvol\Inventory\Classes\Transformers\ProductTransformer;
use Samvol\Inventory\Classes\Transformers\OperationTransformer;
use Samvol\Inventory\Classes\Transformers\DocumentTransformer;
use Samvol\Inventory\Classes\Transformers\CategoryTransformer;
use Samvol\Inventory\Classes\Transformers\OperationProductTransformer;

class Api extends Controller
{
    public $implement = [];

    /*--------------------------
    | Products
    --------------------------*/
    public function products()
    {
        try {
            $items = Product::with(['images', 'category', 'category.children'])->get();
            return $this->success(
                $items->map(fn($p) => $this->mapProduct($p))
            );
        } catch (\Throwable $e) {
            \Log::error('API products error', ['message'=>$e->getMessage(),'trace'=>$e->getTraceAsString()]);
            return $this->error('Ошибка получения продуктов: '.$e->getMessage(), 500);
        }
    }

    public function product($id)
    {
        try {
            $item = Product::with(['images','category','category.children'])->find($id);
            if (!$item) return $this->error("Product not found", 404);

            return $this->success($this->mapProduct($item));
        } catch (\Throwable $e) {
            \Log::error('API product error', ['id'=>$id,'message'=>$e->getMessage()]);
            return $this->error('Ошибка получения продукта: '.$e->getMessage(), 500);
        }
    }

    /*--------------------------
    | Operations
    --------------------------*/
    public function operations()
    {
        $items = Operation::with('products', 'documents')->get();
        return $this->success(OperationTransformer::collection($items));
    }

    public function operation($id)
    {
        $item = Operation::with('products', 'documents')->find($id);
        if (!$item) return $this->error("Operation not found", 404);

        return $this->success(OperationTransformer::one($item));
    }

    /*--------------------------
    | Documents
    --------------------------*/
    public function documents()
    {
        return $this->success(DocumentTransformer::collection(Document::all()));
    }

    public function document($id)
    {
        $item = Document::find($id);
        if (!$item) return $this->error("Document not found", 404);

        return $this->success(DocumentTransformer::one($item));
    }

    public function documentFile($id)
    {
        $doc = Document::find($id);
        if (!$doc || !$doc->doc_file) abort(404);

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

    /*--------------------------
    | Categories
    --------------------------*/
    public function categories()
    {
        return $this->success(
            CategoryTransformer::collection(
                Category::whereNull('parent_id')->get()
            )
        );
    }

    public function category($id)
    {
        $cat = Category::find($id);
        if (!$cat) return $this->error("Category not found", 404);

        return $this->success(CategoryTransformer::one($cat));
    }

    /*--------------------------
    | Operation Types
    --------------------------*/
    public function operationTypes()
    {
        return $this->success(
            OperationType::all()->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->name
            ])
        );
    }

    public function operationType($id)
    {
        $type = OperationType::find($id);
        if (!$type) return $this->error("Operation type not found", 404);

        return $this->success([
            'id' => $type->id,
            'name' => $type->name
        ]);
    }

    /*--------------------------
    | Warehouse Products
    --------------------------*/
    public function warehouseProducts()
    {
        $products = Product::with(['category.children', 'images'])->get();

        return $this->success(
            $products->map(fn($p) => $this->mapProduct($p))
        );
    }

    /*--------------------------
    | History
    --------------------------*/
    public function history(Request $request)
    {
        $query = OperationProduct::with([
            'product',
            'operation.type',
            'operation.documents'
        ])->whereDoesntHave('operation', fn($q) =>
            $q->whereIn('type_id', [6, 7])
        );

        if ($slug = $request->get('slug')) {
            if ($product = Product::where('slug', $slug)->first()) {
                $query->where('product_id', $product->id);
            }
        }

        return $this->success(
            OperationProductTransformer::collection($query->get())
        );
    }

    /*--------------------------
    | Upload Image
    --------------------------*/
    public function upload(Request $request)
    {
        $product = Product::find($request->get('product_id'));
        if (!$product) return $this->error('Product not found', 404);

        $clientId = $request->get('client_id');
        if (!$clientId) return $this->error('client_id required', 400);

        // 🔐 ИДЕМПОТЕНТНОСТЬ
        $existing = $product->images()
            ->where('description', $clientId)
            ->first();

        if ($existing) {
            return $this->success([
                'id' => $existing->id,
                'serverUrl' => url($existing->getPath()),
                'duplicate' => true,
            ]);
        }

        if (!$request->hasFile('file')) {
            return $this->error('No file uploaded', 400);
        }

        $file = new File();
        $file->data = $request->file('file');
        $file->field = 'images';
        $file->description = $clientId; // 🔑 сохраняем
        $file->is_public = 1;
        $file->save();

        $product->images()->add($file);

        return $this->success([
            'id' => $file->id,
            'serverUrl' => url($file->getPath()),
            'duplicate' => false,
        ]);
    }

    /*--------------------------
    | Check Image
    --------------------------*/
    public function checkImage(Request $request)
    {
        $url = $request->get('url');
        if (!$url) return $this->error('URL not provided', 400);

        $path = public_path(parse_url($url, PHP_URL_PATH));

        return $this->success([
            'exists' => file_exists($path)
        ]);
    }

    /*--------------------------
    | Document Template Settings
    --------------------------*/
    public function documentTemplateSettings()
    {
        if (!$this->authorizeAdmin()) {
            return $this->error('Недостаточно прав', 403);
        }

        return $this->success(DocumentTemplateSettings::get($this->resolveSettingsScope()));
    }

    public function saveDocumentTemplateField(Request $request)
    {
        if (!$this->authorizeAdmin()) {
            return $this->error('Недостаточно прав', 403);
        }

        try {
            $key = trim((string)$request->input('key', ''));
            $value = $request->input('value', '');

            if ($key === '') {
                return $this->error('Не указано поле', 422);
            }

            $settings = DocumentTemplateSettings::saveField($key, $value, $this->resolveSettingsScope());
            return $this->success($settings);
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\Throwable $e) {
            \Log::error('Document template save field error', [
                'message' => $e->getMessage(),
            ]);
            return $this->error('Помилка збереження налаштування', 500);
        }
    }

    public function addDocumentTemplatePerson(Request $request)
    {
        if (!$this->authorizeAdmin()) {
            return $this->error('Недостаточно прав', 403);
        }

        try {
            $roleKey = trim((string)$request->input('role_key', ''));
            $name = trim((string)$request->input('name', ''));
            $position = trim((string)$request->input('position', ''));

            $settings = DocumentTemplateSettings::addPerson($roleKey, $name, $position, $this->resolveSettingsScope());
            return $this->success($settings);
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\Throwable $e) {
            \Log::error('Document template add person error', [
                'message' => $e->getMessage(),
            ]);
            return $this->error('Помилка додавання картки', 500);
        }
    }

    public function updateDocumentTemplatePerson(Request $request)
    {
        if (!$this->authorizeAdmin()) {
            return $this->error('Недостаточно прав', 403);
        }

        try {
            $roleKey = trim((string)$request->input('role_key', ''));
            $personId = trim((string)$request->input('person_id', ''));
            $name = trim((string)$request->input('name', ''));
            $position = trim((string)$request->input('position', ''));

            $settings = DocumentTemplateSettings::updatePerson($roleKey, $personId, $name, $position, $this->resolveSettingsScope());
            return $this->success($settings);
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\Throwable $e) {
            \Log::error('Document template update person error', [
                'message' => $e->getMessage(),
            ]);
            return $this->error('Помилка оновлення картки', 500);
        }
    }

    public function deleteDocumentTemplatePerson(Request $request)
    {
        if (!$this->authorizeAdmin()) {
            return $this->error('Недостаточно прав', 403);
        }

        try {
            $roleKey = trim((string)$request->input('role_key', ''));
            $personId = trim((string)$request->input('person_id', ''));

            $settings = DocumentTemplateSettings::deletePerson($roleKey, $personId, $this->resolveSettingsScope());
            return $this->success($settings);
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\Throwable $e) {
            \Log::error('Document template delete person error', [
                'message' => $e->getMessage(),
            ]);
            return $this->error('Помилка видалення картки', 500);
        }
    }

    public function selectDocumentTemplatePerson(Request $request)
    {
        if (!$this->authorizeAdmin()) {
            return $this->error('Недостаточно прав', 403);
        }

        try {
            $roleKey = trim((string)$request->input('role_key', ''));
            $personId = trim((string)$request->input('person_id', ''));

            $settings = DocumentTemplateSettings::selectPerson($roleKey, $personId, $this->resolveSettingsScope());
            return $this->success($settings);
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\Throwable $e) {
            \Log::error('Document template select person error', [
                'message' => $e->getMessage(),
            ]);
            return $this->error('Помилка вибору картки', 500);
        }
    }

    /*--------------------------
    | Helpers
    --------------------------*/
    private function mapProduct(Product $p): array
    {
        return [
            'id'          => $p->id,
            'name'        => $p->name,
            'unit'        => $p->unit,
            'inv_number'  => $p->inv_number,
            'price'       => $p->price,
            'quantity'    => $p->calculated_quantity,
            'sum'         => $p->calculated_sum,
            'category_id' => $p->category_id,
            'category'    => $p->category
                ? ProductTransformer::category($p->category)
                : null,
            'images'      => $p->images->map(fn($img) => [
                'id'   => $img->id,
                'url'  => url($img->getPath()),
                'name' => $img->file_name,
                'size' => $img->file_size,
            ])->values(),
            'created_at'  => $p->created_at?->toDateTimeString(),
            'updated_at'  => $p->updated_at?->toDateTimeString(),
        ];
    }

    private function success($data)
    {
        return Response::json([
            'success' => true,
            'data'    => $data,
            'error'   => null,
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    private function error($message, $code = 400)
    {
        return Response::json([
            'success' => false,
            'data'    => null,
            'error'   => $message,
        ], $code);
    }

    private function resolveUser()
    {
        try {
            $frontendUser = \Auth::getUser();
            if ($frontendUser) {
                return $frontendUser;
            }
        } catch (\Throwable $e) {
        }

        try {
            $defaultUser = \Auth::user();
            if ($defaultUser) {
                return $defaultUser;
            }
        } catch (\Throwable $e) {
        }

        try {
            if (class_exists(\Backend\Facades\BackendAuth::class)) {
                $backendUser = \Backend\Facades\BackendAuth::getUser();
                if ($backendUser) {
                    return $backendUser;
                }
            }
        } catch (\Throwable $e) {
        }

        return null;
    }

    private function authorizeAdmin(): bool
    {
        $user = $this->resolveUser();
        if (!$user) {
            return false;
        }

        if (OrganizationAccess::isOrganizationAdmin($user)) {
            return true;
        }

        if (method_exists($user, 'isInGroup') && $user->isInGroup('admin')) {
            return true;
        }

        if (property_exists($user, 'is_superuser') && (bool)$user->is_superuser) {
            return true;
        }

        return false;
    }

    private function resolveSettingsScope(): string
    {
        return SettingsScopeResolver::resolveScopeKey($this->resolveUser());
    }
}
