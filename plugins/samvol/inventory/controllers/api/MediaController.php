<?php namespace Samvol\Inventory\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Samvol\Inventory\Classes\Api\ApiPolicy;
use Samvol\Inventory\Classes\Api\ImageOptimizer;
use Samvol\Inventory\Models\Document;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;
use System\Models\File;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class MediaController extends BaseApiController
{
    public function __construct(private ImageOptimizer $imageOptimizer, private ApiPolicy $apiPolicy)
    {
        parent::__construct();
    }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:8192',
            'product_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation failed', 422, $validator->errors()->toArray());
        }

        $product = Product::query()->find((int) $request->input('product_id'));
        if (!$product) {
            return $this->fail('Product not found', 404);
        }

        $uploadedFile = $request->file('file');
        $optimizedPath = $this->imageOptimizer->optimize($uploadedFile);

        $fileModel = new File();
        if ($optimizedPath && is_file($optimizedPath)) {
            $optimizedFile = new SymfonyUploadedFile(
                $optimizedPath,
                pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME) . '.jpg',
                'image/jpeg',
                null,
                true
            );
            $fileModel->data = $optimizedFile;
        } else {
            $fileModel->data = $uploadedFile;
        }

        $fileModel->field = 'images';
        $fileModel->is_public = true;
        $fileModel->save();
        $product->images()->add($fileModel);
        $this->bumpCacheVersion('products.index');
        $this->bumpCacheVersion('products.show');

        return $this->ok([
            'id' => (int) $fileModel->id,
            'url' => url($fileModel->getPath()),
            'name' => (string) $fileModel->file_name,
            'size' => (int) $fileModel->file_size,
            'storage_path' => (string) $fileModel->disk_name,
        ], 201);
    }

    public function uploadDocument(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:20480',
            'operation_id' => 'required|integer',
            'doc_name' => 'nullable|string|max:255',
            'doc_num' => 'nullable|string|max:120',
            'doc_date' => 'nullable|date',
            'doc_purpose' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation failed', 422, $validator->errors()->toArray());
        }

        $operationQuery = Operation::query()->where('id', (int) $request->input('operation_id'));
        $operation = $this->apiPolicy->constrainByOrganization($operationQuery, $request)->first();
        if (!$operation) {
            return $this->fail('Operation not found', 404);
        }

        $document = new Document();
        $document->operation_id = (int) $request->input('operation_id');
        $document->organization_id = $operation->organization_id;
        $document->doc_name = (string) $request->input('doc_name', $request->file('file')->getClientOriginalName());
        $document->doc_num = (string) $request->input('doc_num', '');
        $document->doc_date = $request->input('doc_date');
        $document->doc_purpose = (string) $request->input('doc_purpose', '');
        $document->mime_type = (string) ($request->file('file')->getMimeType() ?? 'application/octet-stream');
        $document->file_size = (int) ($request->file('file')->getSize() ?? 0);
        $document->save();

        $file = new File();
        $file->data = $request->file('file');
        $file->field = 'doc_file';
        $file->is_public = true;
        $file->save();

        $document->doc_file = $file;
        $document->save();
        $document->refresh();
        $this->bumpCacheVersion('documents.index');
        $this->bumpCacheVersion('documents.show');

        return $this->ok([
            'id' => (int) $document->id,
            'file_url' => $document->doc_file ? url($document->doc_file->getPath()) : null,
            'mime_type' => (string) $document->mime_type,
            'file_size' => (int) $document->file_size,
        ], 201);
    }
}
