<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            [
                'name' => 'Steam',
                'image' => 'https://static-00.iconduck.com/assets.00/steam-icon-2048x2048-rbyixh0f.png',
                'offical' => true,
                'short_name' => 'steam',
                'website' => 'https://store.steampowered.com',
            ],
            [
                'name' => 'Epic Games Store',
                'image' => 'https://static-00.iconduck.com/assets.00/epic-games-icon-512x512-7qpmojcd.png',
                'offical' => true,
                'short_name' => 'epic_games_store',
                'website' => 'https://store.epicgames.com',
            ],
            [
                'name' => 'GOG',
                'image' => 'https://cdn.icon-icons.com/icons2/2248/PNG/512/gog_icon_135545.png',
                'offical' => true,
                'short_name' => 'gog',
                'website' => 'https://www.gog.com',
            ],
        ];

        foreach($stores as $store) {
            Store::updateOrCreate(
                [ 'short_name' => $store['short_name'] ],
                $store
            );
        }
    }
}
