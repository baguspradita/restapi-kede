<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    use ApiResponse;

    public function index($productId)
    {
        try {
            $reviews = Review::with('user')
                ->where('product_id', $productId)
                ->orderBy('created_at', 'desc')
                ->paginate(request('limit', 10));

            return $this->paginatedResponse($reviews, 'Reviews retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve reviews: ' . $e->getMessage(), [], 500);
        }
    }

    public function store(Request $request, $orderId)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Check if order belongs to user and is delivered
            $order = $request->user()->orders()->findOrFail($orderId);

            if ($order->order_status !== 'Delivered') {
                return $this->errorResponse('Can only review delivered orders', [], 400);
            }

            // Check if product is in the order
            $orderItem = $order->items()->where('product_id', $validated['product_id'])->first();
            if (!$orderItem) {
                return $this->errorResponse('Product not found in this order', [], 400);
            }

            // Check if already reviewed
            $existingReview = Review::where('order_id', $orderId)
                ->where('product_id', $validated['product_id'])
                ->where('user_id', $request->user()->id)
                ->first();

            if ($existingReview) {
                return $this->errorResponse('You have already reviewed this product', [], 400);
            }

            // Handle image uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('review-images', 'public');
                    $imagePaths[] = $path;
                }
            }

            $review = Review::create([
                'product_id' => $validated['product_id'],
                'user_id' => $request->user()->id,
                'order_id' => $orderId,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'images' => $imagePaths,
            ]);

            // Update product rating
            $this->updateProductRating($validated['product_id']);

            return $this->createdResponse($review, 'Review created successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create review: ' . $e->getMessage(), [], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $review = Review::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $validated = $request->validate([
                'rating' => 'sometimes|required|integer|min:1|max:5',
                'comment' => 'nullable|string',
            ]);

            $review->update($validated);

            // Update product rating
            $this->updateProductRating($review->product_id);

            return $this->successResponse($review, 'Review updated successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->notFoundResponse('Review not found');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $review = Review::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

            $productId = $review->product_id;

            // Delete images
            if ($review->images) {
                foreach ($review->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $review->delete();

            // Update product rating
            $this->updateProductRating($productId);

            return $this->successResponse(null, 'Review deleted successfully');

        } catch (\Exception $e) {
            return $this->notFoundResponse('Review not found');
        }
    }

    private function updateProductRating($productId)
    {
        $product = Product::findOrFail($productId);

        $reviews = Review::where('product_id', $productId)->get();
        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0 ? $reviews->avg('rating') : 0;

        $product->update([
            'rating' => round($averageRating, 2),
            'review_count' => $reviewCount,
        ]);
    }
}
