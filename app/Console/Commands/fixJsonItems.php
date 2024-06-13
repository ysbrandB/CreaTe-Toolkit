<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;

class fixJsonItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-json-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get all of the items and remove the 0 values from the json_items column.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = Item::query()->without('attributes')->select('id', 'json_items')->get();
        foreach ($items as $item){
            if($item->json_items){
                $new = array_values(array_filter($item->json_items, function ($value){
                    return $value!==0;
                }));
                $item->json_items = $new;
                $item->save();
            }
        }
    }
}
