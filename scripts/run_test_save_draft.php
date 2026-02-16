<?php
require __DIR__.'/../bootstrap/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\OperationType;

try {
    $type = OperationType::first();
    if (!$type) {
        $type = new OperationType();
        $type->name = 'приход';
        $type->save();
        echo "Created OperationType id={$type->id}\n";
    }

    $op = new Operation();
    $op->type_id = $type->id;
    $op->is_draft = 1;
    $op->is_posted = 0;
    $op->draft_products = [
        ['name' => 'AutoTest product', 'inv_number' => 'AT-1', 'unit' => 'шт', 'price' => 12.5, 'quantity' => 2, 'sum' => 25]
    ];
    $op->save();

    echo "Saved operation id={$op->id}\n";
    $reloaded = Operation::find($op->id);
    echo "Reloaded draft_products: ";
    var_export($reloaded->draft_products);
    echo "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
