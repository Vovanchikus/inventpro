<?php namespace Samvol\Inventory\Console;

use Illuminate\Console\Command;
use DB;
use Log;

class DumpNoteProducts extends Command
{
    protected $name = 'samvol:dump-note-products';
    protected $description = 'Dump sample rows from samvol_inventory_note_products and counts';

    public function handle()
    {
        $count = DB::table('samvol_inventory_note_products')->count();
        $this->info("note_products count: {$count}");
        Log::info("[samvol] note_products count: {$count}");

        $rows = DB::table('samvol_inventory_note_products as np')
            ->leftJoin('samvol_inventory_products as p', 'np.product_id', '=', 'p.id')
            ->select('np.*', 'p.name as product_name', 'p.inv_number as product_inv')
            ->orderBy('np.id', 'desc')
            ->limit(50)
            ->get();

        foreach ($rows as $r) {
            $line = sprintf("id=%s note_id=%s product_id=%s inv=%s name=%s qty=%s sum=%s",
                $r->id, $r->note_id, $r->product_id, $r->product_inv ?? '-', $r->product_name ?? '-', $r->quantity, $r->sum);
            $this->line($line);
            Log::info("[samvol] " . $line);
        }

        $this->info('Distinct product_ids in pivot:');
        $ids = DB::table('samvol_inventory_note_products')->distinct()->pluck('product_id')->toArray();
        foreach ($ids as $pid) {
            $exists = DB::table('samvol_inventory_products')->where('id', $pid)->exists();
            $pname = $exists ? DB::table('samvol_inventory_products')->where('id', $pid)->value('name') : null;
            $this->line("product_id={$pid} exists=" . ($exists ? 'yes' : 'no') . " name=" . ($pname ?? '-'));
            Log::info("[samvol] product_id={$pid} exists=" . ($exists ? 'yes' : 'no') . " name=" . ($pname ?? '-'));
        }

        $this->info('Distinct note_ids in pivot:');
        $nids = DB::table('samvol_inventory_note_products')->distinct()->pluck('note_id')->toArray();
        foreach ($nids as $nid) {
            $exists = DB::table('samvol_inventory_notes')->where('id', $nid)->exists();
            $title = $exists ? DB::table('samvol_inventory_notes')->where('id', $nid)->value('title') : null;
            $this->line("note_id={$nid} exists=" . ($exists ? 'yes' : 'no') . " title=" . ($title ?? '-'));
            Log::info("[samvol] note_id={$nid} exists=" . ($exists ? 'yes' : 'no') . " title=" . ($title ?? '-'));
        }

        return 0;
    }
}
