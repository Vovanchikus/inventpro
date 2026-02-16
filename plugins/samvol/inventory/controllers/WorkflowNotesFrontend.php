<?php namespace Samvol\Inventory\Controllers;

use Cms\Classes\Page;
use Backend\Classes\Controller;
use Samvol\Inventory\Models\WorkflowNote;

class WorkflowNotesFrontend extends Controller
{
    public function onRun()
    {
        $this->page['notes'] = WorkflowNote::with(['items', 'operations'])->get();
    }
}
