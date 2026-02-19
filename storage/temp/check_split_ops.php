<?php
$root = dirname(__DIR__, 2);
require $root . '/vendor/autoload.php';
$app = require $root . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$inv = '111300000000091911';
$p = Samvol\Inventory\Models\Product::where('inv_number', $inv)->first();
if (!$p) {
    echo "NO_BASE_PRODUCT\n";
    exit(0);
}

echo "PRODUCT_ID={$p->id}\n";
$rows = Illuminate\Support\Facades\DB::table('samvol_inventory_operation_products as op')
    ->join('samvol_inventory_operations as o', 'o.id', '=', 'op.operation_id')
    ->join('samvol_inventory_operation_types as t', 't.id', '=', 'o.type_id')
    ->where('op.product_id', $p->id)
    ->select('op.operation_id', 'op.quantity', 'op.sum', 't.name as type')
    ->orderBy('op.operation_id')
    ->get();

echo 'OPS_TOTAL=' . $rows->count() . "\n";
foreach ($rows as $r) {
    echo $r->operation_id . '|' . $r->type . '|' . $r->quantity . '|' . $r->sum . "\n";
}
