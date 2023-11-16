<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use App\Models\Store;
use App\Models\Currency;
use App\Models\Game;
use App\Models\ProductPrice;
use App\Models\StoreProduct;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $games = [
            [
                'name' => 'The Last of Us™ Part I',
                'description' => "200'ün üzerinde Yılın Oyunu ödülü alan The Last of Us™'taki duygu yüklü hikâye anlatımına ve unutulmaz karakterlere tanıklık et.",
                'image' => "https://cdn.akamai.steamstatic.com/steam/apps/1888930/header.jpg?t=1697567304",
                'video' => "https://www.youtube.com/embed/WxjeV10H1F0?si=FVA8YoEntMdJDIk9",
                'products' => [
                    [
                        'store' => 'steam',
                        'details' => [
                            'app_id' => '1888930',
                        ],
                        'url' => 'https://store.steampowered.com/app/1888930/The_Last_of_Us_Part_I/',
                        'currencies' => ['TRY', 'USD'],
                    ],
                    [
                        'store' => 'epic_games_store',
                        'details' => [
                            'sandbox_id' => '0c40923dd1174a768f732a3b013dcff2',
                            'offer_id' => '0f0fe55f8a464f3f992fa31c0e2810d7',
                        ],
                        'url' => 'https://store.epicgames.com/en-US/p/the-last-of-us-part-1',
                        'currencies' => ['TRY', 'USD']
                    ]
                ]
            ],
            [
                'name' => 'Dying Light 2 Stay Human',
                'description' => "Virüs kazandı ve medeniyet Karanlık Çağ'a geri döndü. İnsanlığın son yerleşkelerinden biri olan Şehir, yıkılmanın eşiğinde. Hayatta kalmak için çevikliğini ve dövüş yeteneklerini kullan ve dünyayı yeniden şekillendir. Seçimlerin herkesin kaderini belirleyecek.",
                'image' => "https://cdn.akamai.steamstatic.com/steam/apps/534380/header.jpg?t=1697567304",
                'video' => "https://www.youtube.com/embed/68bZ1LKKh7Q?si=opUcPmDA0kN3Xs7X",
                'products' => [
                    [
                        'store' => 'steam',
                        'details' => [
                            'app_id' => '534380',
                        ],
                        'url' => 'https://store.steampowered.com/app/534380/Dying_Light_2_Stay_Human',
                        'currencies' => ['TRY', 'USD']
                    ],
                    [
                        'store' => 'epic_games_store',
                        'details' => [
                            'sandbox_id' => '87b7846d2eba4bc49eead0854323aba8',
                            'offer_id' => '307ac9c720d249a8b96c2f1de5970f50',
                        ],
                        'url' => 'https://store.epicgames.com/en-US/p/dying-light-2-stay-human',
                        'currencies' => ['TRY', 'USD']
                    ]
                ]
            ],
            [
                'name' => 'Days Gone',
                'description' => "Salgın sonrası ölümün kol gezdiği Amerika'da motosiklet sür ve savaş. Bu açık dünya aksiyon-macera oyununda, yaşamak için bir neden arayan ve hayatta kalmak için savaşarak Bozuk Yol'da motosiklet süren bir başıboş ve ganimet avcısı Deacon St. John olarak oyna.",
                'image' => "https://cdn.akamai.steamstatic.com/steam/apps/1259420/header.jpg?t=1698779323",
                'video' => "https://www.youtube.com/embed/FKtaOY9lMvM?si=9ReC3hO12IKNkD68",
                'products' => [
                    [
                        'store' => 'gog',
                        'details' => [
                            'id' => '1127395101',
                        ],
                        'url' => 'https://www.gog.com/en/game/days_gone',
                        'currencies' => ['USD']
                    ],
                ]
            ]
        ];

        $stores = Store::all();
        $currencies = Currency::all()->toArray();

        foreach($games as $game) {
            $created_game = Game::updateOrCreate(
                [
                    'name' => $game['name']
                ],
                array_diff_key($game, array('products' => ''))
            );

            foreach($game['products'] as $product) {
                $store = Arr::first($stores, function ($value) use ($product) {
                    return $value->short_name === $product['store'];
                });

                    $currencies = Arr::where($currencies, function ($value, $key) use ($product) {
                        return array_search($value['code'], $product['currencies']) !== false;
                    });

                $created_product = StoreProduct::updateOrCreate(
                    [
                        'store_id' => $store->id,
                        'game_id' => $created_game->id
                    ],
                    [
                        'store_id' => $store->id,
                        'game_id' => $created_game->id,
                        'details' => $product['details'],
                        'url' => $product['url']
                    ]
                );

                foreach($currencies as $currency) {
                    ProductPrice::updateOrCreate(
                        [
                            'store_product_id' => $created_product->id,
                            'currency_id' => $currency['id'],
                        ],
                        [
                            'store_product_id' => $created_product->id,
                            'currency_id' => $currency['id'],
                            'price' => 0000,
                        ]
                    );
                }
            }
        }
    }
}
