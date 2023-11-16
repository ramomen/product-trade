<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    private function validateRequest(Request $request)
    {
        $rules = [
            'name' => 'required|min:3',
            'price' => 'required|numeric',
            'description' => 'required|min:10'
        ];
        $this->validate($request, $rules);
    }


    public function index()
    {
        $products = Product::all();
        if (count($products) === 0) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }
        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        try {
            $product = Product::create($request->all());
            return response()->json($product, 201);
        } catch (\Exception $e) {
            $errorCodeId = $this->errorCodeId();
            Log::error('ProductController@store - ' . $errorCodeId . ' - ' . $e->getMessage());
            return response()->json($e->getMessage(), 400);
        }
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        }
        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        $this->validateRequest($request);

        try {
            $product = Product::find($id);
            if (is_null($product)) {
                return response()->json([
                    'message' => 'Product not found.',
                ], 404);
            }
            $product->update($request->all());
            return response()->json($product, 200);
        } catch (\Exception $e) {
            $errorCodeId = $this->errorCodeId();
            Log::error('ProductController@update - ' . $errorCodeId . ' - ' . $e->getMessage());
            return response()->json([
                'message' => 'Product update failed',
                'errorCode' => $this->errorCodeId(),
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            if (is_null($product)) {
                return response()->json([
                    'message' => 'Product not found.',
                ], 404);
            }
            $product->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            $errorCodeId = $this->errorCodeId();
            Log::error('ProductController@destroy - ' . $errorCodeId . ' - ' . $e->getMessage());
            return response()->json([
                'message' => 'Product deletion failed',
                'errorCode' => $this->errorCodeId(),
            ], 400);
        }
    }
}
