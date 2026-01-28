<?php
require __DIR__ . '/../bootstrap/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $count = DB::table('samvol_inventory_documents')->count();
    echo "DOCUMENT_COUNT=" . $count . PHP_EOL;
    $items = DB::table('samvol_inventory_documents')->limit(10)->get();
    echo json_encode($items, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) . PHP_EOL;
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
