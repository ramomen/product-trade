<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    private function validateRequest(Request $request)
    {
        $rules = [
            'offerId' => 'required',
            'quantity' => 'required',
            'orderDate' => 'datetime'
        ];
        $this->validate($request, $rules);
    }

    public function index()
    {
        $orders = Order::all();
        return response()->json($orders, 200);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        try {
            $offer = Offer::find($request->offerId);
            if (is_null($offer)) {
                return response()->json([
                    'message' => 'Offer not found.',
                ], 404);
            }
            $order = Order::create([
                'offerId' => $request->offerId,
                'quantity' => $request->quantity,
                'orderDate' => Carbon::parse($request->orderDate)->format('Y-m-d H:i:s') ?? Carbon::now()->format('Y-m-d H:i:s')
            ]);
            return response()->json($order, 201);
        } catch (\Exception $e) {
            Log::error('OrderController@store - ' . $this->errorCodeId() . ' - ' . $e->getMessage());
            return response()->json($e->getMessage(), 400);
        }
    }

    public function show($id)
    {
        $order = Order::find($id);
        if (is_null($order)) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }
        return response()->json($order, 200);
    }

    public function update(Request $request, $id)
    {
        $this->validateRequest($request);
        try {
            $order = Order::find($id);
            if (is_null($order)) {
                return response()->json([
                    'message' => 'Order not found.',
                ], 404);
            }
            $offer = Offer::find($request->offerId);
            if (is_null($offer)) {
                return response()->json([
                    'message' => 'Offer not found.',
                ], 404);
            }
            $order->update([
                'offerId' => $request->offerId,
                'quantity' => $request->quantity,
                'orderDate' => Carbon::parse($request->orderDate)->format('Y-m-d H:i:s') ?? Carbon::now()->format('Y-m-d H:i:s')
            ]);
            return response()->json($order, 200);
        } catch (\Exception $e) {
            Log::error('OrderController@update - ' . $this->errorCodeId() . ' - ' . $e->getMessage());
            return response()->json($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::find($id);
            if (is_null($order)) {
                return response()->json([
                    'message' => 'Order not found.',
                ], 404);
            }
            $order->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('OrderController@destroy - ' . $this->errorCodeId() . ' - ' . $e->getMessage());
            return response()->json($e->getMessage(), 400);
        }
    }
}
