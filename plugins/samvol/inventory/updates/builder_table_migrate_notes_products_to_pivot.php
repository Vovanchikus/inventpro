<?php namespace Samvol\Inventory\Updates;

use DB;
use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableMigrateNotesProductsToPivot extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('samvol_inventory_notes') || !Schema::hasTable('samvol_inventory_note_products')) {
            return;
        }

        $notes = DB::table('samvol_inventory_notes')->whereNotNull('products')->get();

        foreach ($notes as $note) {
            $json = $note->products;
            if (empty($json)) {
                continue;
            }

            $items = json_decode($json, true);
            if (!is_array($items)) {
                continue;
            }

            foreach ($items as $item) {
                $productId = null;
                if (!empty($item['product_id'])) {
                    $productId = (int)$item['product_id'];
                } elseif (!empty($item['inv_number'])) {
                    $p = DB::table('samvol_inventory_products')->where('inv_number', $item['inv_number'])->first();
                    if ($p) {
                        $productId = $p->id;
                    }
                }

                $quantity = isset($item['quantity']) ? (float)$item['quantity'] : (float)($item['qty'] ?? 0);
                $sum = isset($item['sum']) ? (float)$item['sum'] : (isset($item['price']) && $quantity ? (float)$item['price'] * $quantity : null);

                // Insert pivot row
                DB::table('samvol_inventory_note_products')->insert([
                    'note_id' => $note->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'sum' => $sum,
                    'counteragent' => $item['counteragent'] ?? null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    public function down()
    {
        // no-op
    }
}
