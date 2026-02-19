<?php
$root = dirname(__DIR__, 2);
require $root . '/vendor/autoload.php';
$app = require $root . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$invList = ['111300000000091911','111300000000091911/5','111300000000091911/6'];
foreach ($invList as $inv) {
    $products = Samvol\Inventory\Models\Product::where('inv_number', $inv)->get();
    echo "INV={$inv}; COUNT=" . $products->count() . "\n";
    foreach ($products as $p) {
        echo "  ID={$p->id}; NAME={$p->name}; PRICE={$p->price}\n";
    }
}
