<?php

namespace App\Http\Controllers;

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
            'condition' => 'required',
            'availability' => 'required'
        ];
        $this->validate($request, $rules);
    }

    public function index()
    {
        $offers = Offer::with('product')->get();
        if (count($offers) === 0) {
            return response()->json([
                'message' => 'Offers not found',
            ], 404);
        }
        return response()->json($offers, 200);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);
        try {
            $product = Product::find($request->productId);
            if (!$product) {
                return response()->json([
                    'message' => 'Product not found',
                ], 404);
            }

            $offer = Offer::create($request->all());
            return response()->json($offer, 201);
        } catch (\Exception $e) {
            Log::error('OfferController@store - ' . $this->errorCodeId() . ' - ' . $e->getMessage());
            return response()->json([
                'message' => 'Offer creation failed: ',
                'errorCode' => $this->errorCodeId(),
            ], 400);
        }
    }

    public function show($id)
    {
        $offer = Offer::where('offerId', $id)
            ->with('product')
            ->get();
        if (!$offer) {
            return response()->json([
                'message' => 'Offer not found',
            ], 404);
        }
        return response()->json($offer, 200);
    }

    public function update(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'message' => 'Invalid offer id',
            ], 400);
        }

        try {
            $offer = Offer::find($id);
            if (!$offer) {
                return response()->json([
                    'message' => 'Offer not found',
                ], 404);
            }
            $offer->update($request->all());
            return response()->json($offer, 200);
        } catch (\Exception $e) {
            $errorCode = $this->errorCodeId();
            Log::error('OfferController@update - ' . $errorCode . ' - ' . $e->getMessage());
            return response()->json([
                'message' => 'Offer update failed: ',
                'errorCode' => $errorCode,
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $offer = Offer::find($id);

            if (!$offer) {
                return response()->json([
                    'message' => 'Offer not found',
                ], 404);
            }

            $offer->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('OfferController@destroy - ' . $this->errorCodeId() . ' - ' . $e->getMessage());
            return response()->json([
                'message' => 'Offer deletion failed',
                'errorCode' => $this->errorCodeId(),
            ], 400);
        }
    }
}
