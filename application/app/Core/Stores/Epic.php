<?php

namespace App\Core\Stores;

use Illuminate\Support\Facades\Http;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\ProductPrice;
use App\Models\Currency;

class Epic
{
    private const API_ADDRESS = "https://store.epicgames.com/graphql";
    private const CATALOG_OFFER_QUERY = "?operationName=getCatalogOffer&variables=%7B%22locale%22:%22{@locale}%22,%22country%22:%22{@country}%22,%22offerId%22:%22{@offerId}%22,%22sandboxId%22:%22{@sandboxId}%22%7D&extensions=%7B%22persistedQuery%22:%7B%22version%22:1,%22sha256Hash%22:%22c4ad546ad2757b60ff13ace19ffaf134abb23cb663244de34771a0444abfdf33%22%7D%7D";

    private const SHORT_NAME = "epic_games_store";
    private $store;

    function __construct() {
        $this->store = Store::whereShortName(self::SHORT_NAME)->first();
    }

    private function fill_query_variables(string $query, array $variables) {
        return str_replace($variables[0], $variables[1], $query);
    }

    private function get_game_data(StoreProduct $store_product, Currency $currency) {
        $details = $store_product->details;
        $offer_id = $details['offer_id'];
        $sandbox_id = $details['sandbox_id'];
        $locale = $currency->region;
        $variables = [
            ['{@locale}', '{@country}', '{@offerId}', '{@sandboxId}'],
            [$locale, strtoupper($locale), $offer_id, $sandbox_id]
        ];

        $query = self::fill_query_variables(self::CATALOG_OFFER_QUERY, $variables);
        $url = self::API_ADDRESS.$query;

        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36'
        ])->get($url);

        return $response['data']['Catalog']['catalogOffer'];
    }

    private function update_price(ProductPrice $product_price, $price) {
        $new_price = ProductPrice::whereId($product_price->id)->update(['price' => $price]);

        return $new_price;
    }

    public function sync_all() {
        $products = StoreProduct::whereStoreId($this->store->id)->get();

        foreach ($products as $product) {
            foreach ($product->prices as $price) {
                $data = self::get_game_data($product, $price->currency);
                self::update_price($price, $data['price']['totalPrice']['originalPrice']);
            }
        }
    }
}
