<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $query = Product::with('category')->where('is_available', true);

            // Filter by category
            if ($request->has('category')) {
                $query->where('category_id', $request->category);
            }

            // Filter by price range
            if ($request->has('minPrice')) {
                $query->where('price', '>=', $request->minPrice);
            }
            if ($request->has('maxPrice')) {
                $query->where('price', '<=', $request->maxPrice);
            }

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Sorting
            $sort = $request->input('sort', 'newest');
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('review_count', 'desc')->orderBy('rating', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }

            $products = $query->paginate($request->input('limit', 10));

            return $this->paginatedResponse($products, 'Products retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve products: ' . $e->getMessage(), [], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::with(['category', 'reviews.user'])->findOrFail($id);
            return $this->successResponse($product, 'Product retrieved successfully');
        } catch (\Exception $e) {
            return $this->notFoundResponse('Product not found');
        }
    }

    public function search(Request $request)
    {
        try {
            $keyword = $request->input('search', '');

            $products = Product::where('is_available', true)
                ->where(function($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")
                          ->orWhere('description', 'like', "%{$keyword}%");
                })
                ->paginate($request->input('limit', 10));

            return $this->paginatedResponse($products, 'Search results retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Search failed: ' . $e->getMessage(), [], 500);
        }
    }

    public function popular()
    {
        try {
            $products = Product::where('is_available', true)
                ->orderBy('review_count', 'desc')
                ->orderBy('rating', 'desc')
                ->limit(10)
                ->get();

            return $this->successResponse($products, 'Popular products retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve popular products: ' . $e->getMessage(), [], 500);
        }
    }

    public function deals()
    {
        try {
            // Since discount_price is removed, there are no deals based on it.
            // Returning empty list or could return low price items.
            // For now, let's return empty to stay true to "no discount".
            $products = Product::where('is_available', true)
                ->whereRaw('0 = 1') // Force empty
                ->paginate(request('limit', 10));

            return $this->paginatedResponse($products, 'No deals available at the moment');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve deals: ' . $e->getMessage(), [], 500);
        }
    }
}
