<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $response = Http::get('https://run.mocky.io/v3/7f64b9df-190d-4d95-b829-7452575fa528');

        if ($response->successful()) {
            $orders = $response->json()['orders'];

            foreach ($orders as $item) {
                $id = substr($item['orderId'], 3);
                $offerId = substr($item['offerId'], 2);

                $order = new Order();
                $order->id = (int) $id;
                $order->offer_id = (int) $offerId;
                $order->quantity = $item['quantity'];
                $order->order_date = $item['orderDate'];

                $order->save();
            }
        } else {
            Log::error('OrderSeeder: API request failed.' . $response->body());
        }
    }
}
