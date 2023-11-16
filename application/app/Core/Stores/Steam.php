<?php

namespace App\Core\Stores;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\ProductPrice;
use App\Models\Currency;

class Steam
{
    private const API_ADDRESS = "https://store.steampowered.com/api/appdetails";
    private const SHORT_NAME = "steam";
    private $store;

    function __construct() {
        $this->store = Store::whereShortName(self::SHORT_NAME)->first();
    }

    private function get_game_data(StoreProduct $store_product, Currency $currency) {
        Log::channel('sync')->info(
            "Fetching game data from steam api.",
            ['currency' => $currency->toArray(), 'product' => $store_product->toArray()]
        );
        $details = $store_product->details;
        $app_id = $details['app_id'];
        $currency_region = $currency->region;
        $url = self::API_ADDRESS."?appids=".$app_id."&cc=".$currency_region;
        $response = Http::get($url);
        Log::channel('sync')->info(
            "Fetching game data from Steam api.",
            ['currency' => $currency->toArray(), 'product' => $store_product->toArray()]
        );

        return $response[$app_id]['data'];
    }

    private function update_price(ProductPrice $product_price, $price) {
        Log::channel('sync')->info(
            "Product Price Updating.",
            ['New Price' => $price, 'Product Price' => $product_price->toArray()]
        );

        ProductPrice::whereId($product_price->id)->update(['price' => $price]);

        $updated_price = ProductPrice::whereId($product_price->id)->first();

        Log::channel('sync')->info(
            "Product Price Updated. " . json_encode($product_price) . ' to '. json_encode($updated_price)
        );
        return $updated_price;
    }

    public function sync_all() {

        $products = StoreProduct::whereStoreId($this->store->id)->get();

        Log::channel('sync')->info("Synchronization with Steam has been started", ['products' => count($products)]);

        foreach ($products as $product) {
            foreach ($product->prices as $price) {
                $data = self::get_game_data($product, $price->currency);
                self::update_price($price, $data['price_overview']['final']);
            }
        }

        Log::channel('sync')->info("Synchronization with Steam is complete.");
    }
}
