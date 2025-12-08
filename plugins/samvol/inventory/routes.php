<?php

use Samvol\Inventory\Models\Document;
use Illuminate\Support\Facades\Response;

// Безопасная раздача документов
Route::get('/document/{id}', function ($id) {
    $doc = Document::find($id);

    if (!$doc || !$doc->doc_file) {
        abort(404, 'Документ не найден');
    }

    $file = $doc->doc_file;

    $path = $file->getLocalPath();

    if (!file_exists($path)) {
        abort(404, 'Файл отсутствует');
    }

    return Response::make(
        file_get_contents($path),
        200,
        [
            'Content-Type'        => $file->content_type ?: 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file->file_name . '"',
            'Content-Length'      => filesize($path),
        ]
    );
});
