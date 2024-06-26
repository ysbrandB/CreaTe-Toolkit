<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;

class example_code_to_markdown extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:example_code_to_markdown';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        foreach (Item::all() as $item) {
            $item->example_code = $this->convertCodeToMarkdown($item->example_code);
            $item->save();
        }
    }

    private function convertCodeToMarkdown(string $code): string
    {
        return '```
'.$code.'
```';
    }
}
