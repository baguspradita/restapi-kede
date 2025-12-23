<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $cart = $this->getOrCreateCart($request->user());
            $cart->load(['items.product']);

            return $this->successResponse($cart, 'Cart retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve cart: ' . $e->getMessage(), [], 500);
        }
    }

    public function addItem(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($validated['product_id']);

            if (!$product->is_available || $product->stock < $validated['quantity']) {
                return $this->errorResponse('Product not available or insufficient stock', [], 400);
            }

            $cart = $this->getOrCreateCart($request->user());

            // Check if item already exists in cart
            $cartItem = $cart->items()->where('product_id', $validated['product_id'])->first();

            if ($cartItem) {
                $cartItem->quantity += $validated['quantity'];
                $cartItem->subtotal = $cartItem->quantity * $cartItem->price;
                $cartItem->save();
            } else {
                $price = $product->price;
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                    'price' => $price,
                    'subtotal' => $validated['quantity'] * $price,
                ]);
            }

            $cart->updateTotal();
            $cart->load(['items.product']);

            return $this->successResponse($cart, 'Item added to cart successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to add item: ' . $e->getMessage(), [], 500);
        }
    }

    public function updateItem(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $cart = $this->getOrCreateCart($request->user());
            $cartItem = $cart->items()->findOrFail($id);

            if ($cartItem->product->stock < $validated['quantity']) {
                return $this->errorResponse('Insufficient stock', [], 400);
            }

            $cartItem->quantity = $validated['quantity'];
            $cartItem->subtotal = $cartItem->quantity * $cartItem->price;
            $cartItem->save();

            $cart->updateTotal();
            $cart->load(['items.product']);

            return $this->successResponse($cart, 'Cart item updated successfully');

        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->notFoundResponse('Cart item not found');
        }
    }

    public function removeItem(Request $request, $id)
    {
        try {
            $cart = $this->getOrCreateCart($request->user());
            $cartItem = $cart->items()->findOrFail($id);
            $cartItem->delete();

            $cart->updateTotal();
            $cart->load(['items.product']);

            return $this->successResponse($cart, 'Item removed from cart successfully');

        } catch (\Exception $e) {
            return $this->notFoundResponse('Cart item not found');
        }
    }

    public function clear(Request $request)
    {
        try {
            $cart = $this->getOrCreateCart($request->user());
            $cart->items()->delete();
            $cart->updateTotal();

            return $this->successResponse($cart, 'Cart cleared successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to clear cart: ' . $e->getMessage(), [], 500);
        }
    }

    private function getOrCreateCart($user)
    {
        $cart = $user->cart;

        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }

        return $cart;
    }
}
