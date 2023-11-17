<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfferCollection;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfferController extends Controller
{
    private function validateRequest(Request $request)
    {
        $rules = [
            'productId' => 'required',
            'sellerId' => 'required',
            'price' => 'required',
            'condition' => 'required|in:new,used',
            'availability' => 'required|in:in stock,out of stock'
        ];
        $this->validate($request, $rules);
    }

    public function index()
    {
        $offers = Offer::with('product')->get();
        if (count($offers) === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Offers not found',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => OfferCollection::collection($offers)
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        try {
            $product = Product::find($request->productId);
            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found',
                ], 404);
            }

            $offer = Offer::create([
                'product_id' => $request->productId,
                'seller_id' => $request->sellerId,
                'price' => (float) $request->price,
                'condition' => $request->condition,
                'availability' => $request->availability,
            ]);
            return response()->json($offer, 201);
        } catch (\Exception $e) {
            $errorCode = $this->errorCodeId();
            Log::error('OfferController@store - ' . $errorCode . ' - ' . $e->getMessage());
            return response()->json([
                'message' => 'Offer creation failed: ',
                'errorCode' => $errorCode,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function show($id)
    {
        dd($id);
        if (!is_numeric($id)) {
            $id = $this->getNumericId($id);
            if (!is_numeric($id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid offer id',
                ], 400);
            }
        }
        $offer = Offer::where('id', $id)
            ->with('product')
            ->get();
        if (!$offer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Offer not found',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => OfferCollection::collection([$offer])[0]
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validateRequest($request);

        if (!is_numeric($id)) {
            $id = $this->getNumericId($id);
            if (!is_numeric($id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid offer id',
                ], 400);
            }
        }
        try {
            $offer = Offer::find($id);
            if (!$offer) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Offer not found',
                ], 404);
            }
            $productId = $request->productId;
            if (!is_numeric($productId)) {
                $productId = $this->getProductNumericId($request->productId);
                if (!is_numeric($productId)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid product id',
                    ], 400);
                }
            }
            if (!Product::find($productId)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found',
                ], 404);
            }

            $offer->update([
                'product_id' => $productId,
                'seller_id' => $request->sellerId,
                'price' => (float) $request->price,
                'condition' => $request->condition,
                'availability' => $request->availability,
            ]);
            return response()->json([
                'status' => 'success',
                'data' => OfferCollection::collection([$offer])[0]
            ], 200);
        } catch (\Exception $e) {
            $errorCode = $this->errorCodeId();
            Log::error('OfferController@update - ' . $errorCode . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Offer update failed',
                'errorCode' => $errorCode,
            ], 400);
        }
    }

    public function destroy($id)
    {
        if (!is_numeric($id)) {
            $id = $this->getNumericId($id);
            if (!is_numeric($id)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid offer id',
                ], 400);
            }
        }

        try {
            $offer = Offer::find($id);

            if (!$offer) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Offer not found',
                ], 404);
            }

            $offer->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            $errorCode = $this->errorCodeId();
            Log::error('OfferController@destroy - ' . $errorCode . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Offer deletion failed',
                'errorCode' => $errorCode,
            ], 400);
        }
    }

    private function getNumericId($id)
    {
        return substr($id, 2);
    }

    private function getProductNumericId($id)
    {
        return substr($id, 1);
    }
}
