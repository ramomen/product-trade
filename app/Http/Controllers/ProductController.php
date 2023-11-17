<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
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
            'category' => 'required|min:3'
        ];
        $this->validate($request, $rules);
    }


    public function index()
    {
        $products = Product::all();
        if (count($products) === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => ProductCollection::collection($products)
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        try {
            $product = Product::create($request->all());
            return response()->json([
                'status' => 'success',
                'data' => ProductCollection::collection([$product])[0]
            ], 201);
        } catch (\Exception $e) {
            $errorCode = $this->errorCodeId();
            Log::error('ProductController@store - ' . $errorCode . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Product creation failed',
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
                    'message' => 'Invalid product id.',
                ], 400);
            }
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => ProductCollection::collection([$product])[0]
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validateRequest($request);

        try {

            if (!is_numeric($id)) {
                $id = $this->getNumericId($id);
                if (!is_numeric($id)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid product id.',
                    ], 400);
                }
            }
            $product = Product::find($id);
            if (is_null($product)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found.',
                ], 404);
            }
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'category' => $request->category
            ]);
            return response()->json([
                'status' => 'success',
                'data' => ProductCollection::collection([$product])[0]
            ], 200);
        } catch (\Exception $e) {
            $errorCode = $this->errorCodeId();
            Log::error('ProductController@update - ' . $errorCode . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Product update failed',
                'errorCode' => $errorCode
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
                        'message' => 'Invalid product id.',
                    ], 400);
                }
            }

            $product = Product::find($id);
            if (is_null($product)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found.',
                ], 404);
            }
            $product->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            $errorCode = $this->errorCodeId();
            Log::error('ProductController@destroy - ' . $errorCode . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Product deletion failed',
                'errorCode' => $errorCode,
            ], 400);
        }
    }

    private function getNumericId($id)
    {
        return substr($id, 1);
    }
}
