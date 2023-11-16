<?php

namespace App\Core\Stores;

use Illuminate\Support\Facades\Http;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\ProductPrice;
use App\Models\Currency;

class Gog
{
    private const API_ADDRESS = "https://api.gog.com/products/prices";
    private const PRICE_QUERY = "?ids={@ids}&countryCode={@country_code}&currency={@currency}";
    private const SHORT_NAME = "gog";
    private $store;

    function __construct() {
        $this->store = Store::whereShortName(self::SHORT_NAME)->first();
    }

    private function fill_query_variables(string $query, array $variables) {
        return str_replace($variables[0], $variables[1], $query);
    }

    private function get_price_data(StoreProduct $store_product, Currency $currency) {
        $details = $store_product->details;
        $app_id = $details['id'];
        $locale = $currency->region;
        $currency_code = strtoupper($currency->code);

        $variables = [
            ['{@ids}', '{@country_code}', '{@currency}'],
            [$app_id, $locale, $currency_code]
        ];
        $query = self::fill_query_variables(self::PRICE_QUERY, $variables);

        $url = self::API_ADDRESS.$query;
        $response = Http::get($url);
        $finalPrice = $response['_embedded']['items'][0]['_embedded']['prices'][0]['finalPrice'];
        $delete_currency_from_price = str_replace(' '.$currency_code, '', $finalPrice);
        return $delete_currency_from_price;
    }

    private function update_price(ProductPrice $product_price, $price) {
        $new_price = ProductPrice::whereId($product_price->id)->update(['price' => $price]);

        return $new_price;
    }

    public function sync_all() {
        $products = StoreProduct::whereStoreId($this->store->id)->get();

        foreach ($products as $product) {
            foreach ($product->prices as $price) {
                $data = self::get_price_data($product, $price->currency);
                self::update_price($price, $data);
            }
        }
    }
}
