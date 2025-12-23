<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FavoriteController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $favorites = $request->user()->favorites()
                ->with('product')
                ->orderBy('created_at', 'desc')
                ->paginate(request('limit', 10));

            return $this->paginatedResponse($favorites, 'Favorites retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve favorites: ' . $e->getMessage(), [], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);

            // Check if already favorited
            $existing = Favorite::where('user_id', $request->user()->id)
                ->where('product_id', $validated['product_id'])
                ->first();

            if ($existing) {
                return $this->errorResponse('Product already in favorites', [], 400);
            }

            $favorite = Favorite::create([
                'user_id' => $request->user()->id,
                'product_id' => $validated['product_id'],
            ]);

            $favorite->load('product');

            return $this->createdResponse($favorite, 'Product added to favorites');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to add favorite: ' . $e->getMessage(), [], 500);
        }
    }

    public function destroy(Request $request, $productId)
    {
        try {
            $favorite = Favorite::where('user_id', $request->user()->id)
                ->where('product_id', $productId)
                ->firstOrFail();

            $favorite->delete();

            return $this->successResponse(null, 'Product removed from favorites');

        } catch (\Exception $e) {
            return $this->notFoundResponse('Favorite not found');
        }
    }
}
