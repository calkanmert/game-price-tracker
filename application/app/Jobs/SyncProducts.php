<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Core\Product;

class SyncProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $store;

    /**
     * Create a new job instance.
     */
    public function __construct($store)
    {
        $this->store = $store;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        switch ($this->store) {
            case 'steam':
                Product::SyncWithSteam();
                break;
            case 'epic_games_store':
                Product::SyncWithEpic();
                break;
            case 'gog':
                Product::SyncWithGog();
                break;
            default:
                Product::SyncAll();
        }
    }
}
