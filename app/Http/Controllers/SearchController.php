<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{

    public function productSearch(Request $request)
    {
        $search = $request->get('search');
        // Validate the search term
        if (!$search) {
            return response()->json([
                'message' => 'Search term is required',
            ], 400);
        }

        $cacheKey = 'products_search_' . $search;
        $products = Cache::remember($cacheKey, 60, function () use ($search) {

            $rawQuery = "to_tsvector('english', name || ' ' || category) @@ plainto_tsquery(?)";

            return Product::whereRaw($rawQuery, [$search])
                ->select(
                    'id',
                    'name',
                    'category',
                    'price',
                )
                ->simplePaginate(5);
        });
        $products = collect($products->items());

        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Products not found',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => ProductCollection::collection($products)
        ], 200);
    }
}
