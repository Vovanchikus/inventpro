<?php
require __DIR__.'/../bootstrap/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$files = [
    __DIR__.'/../plugins/samvol/inventory/updates/builder_table_create_samvol_inventory_notes.php',
    __DIR__.'/../plugins/samvol/inventory/updates/builder_table_create_samvol_inventory_note_products.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "File $file not found\n";
        continue;
    }
    require_once $file;
    $contents = file_get_contents($file);
    if (preg_match('/class\s+([A-Za-z0-9_]+)/', $contents, $m)) {
        $class = "Samvol\\Inventory\\Updates\\{$m[1]}";
        if (class_exists($class)) {
            try {
                $inst = new $class();
                $inst->up();
                echo "Ran migration class: $class\n";
            } catch (Exception $e) {
                echo "Error running $class: " . $e->getMessage() . "\n";
            }
        } else {
            echo "Class $class not found after require\n";
        }
    } else {
        echo "No class found in $file\n";
    }
}
