<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = Http::get('https://run.mocky.io/v3/1d4edd1f-ecea-4972-9afe-713d78b6f534');

        if ($response->successful()) {
            $products = $response->json()['products'];

            foreach ($products as $item) {
                $id = substr($item['id'], 1);

                $product = new Product();
                $product->id = (int) $id;
                $product->name = $item['name'];
                $product->category = $item['category'];
                $product->price = (float) $item['price'];

                $product->save();
            }
        } else {
            Log::error('ProductSeeder: API request failed.' . $response->body());
        }
    }
}
