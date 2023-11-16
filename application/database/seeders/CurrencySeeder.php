<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Enums\CurrencySymbolPosition;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'name' => 'US Dollar',
                'symbol' => '$',
                'symbol_position' => CurrencySymbolPosition::LEFT,
                'decimal_digits' => 2,
                'code' => 'USD',
                'region' => 'us',
            ],
            [
                'name' => 'Turkish Lira',
                'symbol' => 'TL',
                'symbol_position' => CurrencySymbolPosition::RIGHT,
                'decimal_digits' => 2,
                'code' => 'TRY',
                'region' => 'tr'
            ],
        ];

        foreach($currencies as $currency) {
            Currency::updateOrCreate(
                [ 'code' => $currency['code'] ],
                $currency
            );
        }
    }
}

