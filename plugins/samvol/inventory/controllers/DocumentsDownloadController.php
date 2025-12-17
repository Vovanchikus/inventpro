<?php namespace Samvol\Inventory\Controllers;

use Illuminate\Routing\Controller;
use Samvol\Inventory\Models\Document;

class DocumentsDownloadController extends Controller
{
    public function download($id)
    {
        $doc = Document::find($id);

        if (!$doc || !$doc->doc_file) {
            abort(404, 'Документ или файл не найден');
        }

        return response()->file(
            $doc->doc_file->getLocalPath(),
            [
                'Content-Disposition' => 'inline; filename="'.$doc->doc_file->file_name.'"'
            ]
        );
    }
}
