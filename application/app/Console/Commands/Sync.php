<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncProducts;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $stores = ['steam', 'epic_games_store', 'gog'];

        foreach ($stores as $store) {
            SyncProducts::dispatch($store);
        }
    }
}
