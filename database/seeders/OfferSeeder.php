<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://run.mocky.io/v3/5a4b809b-c72a-4ab2-9acd-f63dc95a9755');
        if ($response->successful()) {
            $offers = $response->json()['offers'];

            foreach ($offers as $item) {
                $id = substr($item['offerId'], 2);
                $productId = substr($item['productId'], 1);
                $sellerId = substr($item['sellerId'], 1);

                $offer = new Offer();
                $offer->id = (int) $id;
                $offer->product_id = (int) $productId;
                $offer->seller_id =  $sellerId;
                $offer->price = (float) $item['price'];
                $offer->condition = $this->condition($item['condition']);
                $offer->availability = $this->availability($item['availability']);




                $offer->save();
            }
        } else {
            Log::error('OfferSeeder: API request failed.' . $response->body());
        }
    }

    // condition => new, used
    public function condition($condition)
    {
        switch ($condition) {
            case 'Yeni':
                return 'new';
                break;
            case 'Kullanılmış':
                return 'used';
                break;
            default:
                return 'new';
                break;
        }
    }

    // availability => inStock, outOfStock
    public function availability($availability)
    {
        switch ($availability) {
            case 'Stokta':
                return 'in stock';
                break;
            case 'Stokta Yok':
                return 'out of stock';
                break;
            default:
                return 'inStock';
                break;
        }
    }
}
