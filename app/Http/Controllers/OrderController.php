<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\OrderCollection;


class OrderController extends Controller
{
    private function validateRequest(Request $request)
    {
        $rules = [
            'offerId' => 'required',
            'quantity' => 'required|numeric',
            'orderDate' => 'date'
        ];
        $this->validate($request, $rules);
    }

    public function index()
    {
        $orders = Order::all();
        if (count($orders) === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found.',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => OrderCollection::collection($orders)
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        try {
            $offerId = $request->offerId;

            if (!is_numeric($offerId)) {
                $offerId = $this->getOfferNumericId($offerId);
                if (!is_numeric($offerId)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid offer id.',
                    ], 400);
                }
            }

            $offer = Offer::find($offerId);
            if (is_null($offer)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Offer not found.',
                ], 404);
            }

            $order = Order::create([
                'offer_id' => $offerId,
                'quantity' => (int) $request->quantity,
                'order_date' => Carbon::parse($request->orderDate)->format('Y-m-d') ?? Carbon::now()->format('Y-m-d')
            ]);
            return response()->json([
                'status' => 'success',
                'data' => OrderCollection::collection([$order])[0]
            ], 201);
        } catch (\Exception $e) {
            $errorCode = $this->errorCodeId();
            Log::error('OrderController@store - ' . $errorCode . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating order.',
                'errorCode' => $errorCode,
            ], 400);
        }
    }

    public function show($id)
    {
        if (!is_numeric($id)) {
            $id = $this->getNumericId($id);
            if (!is_numeric($id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid order id.',
                ], 400);
            }
        }

        $order = Order::where('id', $id)->get();
        if (is_null($order)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found.',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => OrderCollection::collection($order)[0]
        ], 200);
    }

    public function update(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = $this->getNumericId($id);
            if (!is_numeric($id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid order id.',
                ], 400);
            }
        }

        $this->validateRequest($request);
        try {
            $order = Order::find($id);

            if (is_null($order)) {
                return response()->json([
                    'message' => 'Order not found.',
                ], 404);
            }

            $offerId = $request->offerId;

            if (!is_numeric($offerId)) {
                $offerId = $this->getOfferNumericId($offerId);
                if (!is_numeric($offerId)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid offer id.',
                    ], 400);
                }
            }

            $offer = Offer::find($offerId);
            if (is_null($offer)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Offer not found.',
                ], 404);
            }

            $order->update([
                'offer_id' => $offerId,
                'quantity' => (int) $request->quantity,
                'order_date' => Carbon::parse($request->orderDate)->format('Y-m-d') ?? Carbon::now()->format('Y-m-d')
            ]);
            return response()->json([
                'status' => 'success',
                'data' => OrderCollection::collection([$order])[0]
            ], 200);
        } catch (\Exception $e) {
            $errorCode = $this->errorCodeId();
            Log::error('OrderController@update - ' . $errorCode . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating order.',
                'errorCode' => $errorCode,
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = $this->getNumericId($id);
                if (!is_numeric($id)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid order id.',
                    ], 400);
                }
            }

            $order = Order::find($id);
            if (is_null($order)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found.',
                ], 404);
            }
            $order->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            $errorCode = $this->errorCodeId();
            Log::error('OrderController@destroy - ' . $errorCode . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting order.',
                'errorCode' => $errorCode,
            ], 400);
        }
    }


    private function getNumericId($id)
    {
        return substr($id, 3);
    }

    private function getOfferNumericId($id)
    {
        return substr($id, 2);
    }
}
