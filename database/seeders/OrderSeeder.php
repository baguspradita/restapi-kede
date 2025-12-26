<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            // Create a shipping address for the user if they don't have one
            $address = Address::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'label' => 'Home',
                    'recipient_name' => $user->name,
                    'phone' => '08123456789',
                    'full_address' => 'Jl. Merdeka No. ' . rand(1, 100),
                    'city' => 'Jakarta',
                    'province' => 'DKI Jakarta',
                    'postal_code' => '12345',
                    'is_default' => true,
                ]
            );

            // Create 1-2 orders for each user
            $orderCount = rand(1, 2);
            for ($i = 0; $i < $orderCount; $i++) {
                $subtotal = 0;
                $orderItems = [];
                
                // Select 2-4 random products
                $randomProducts = $products->random(rand(2, 4));
                
                foreach ($randomProducts as $product) {
                    $qty = rand(1, 5);
                    $price = $product->price;
                    $itemSubtotal = $qty * $price;
                    $subtotal += $itemSubtotal;
                    
                    $orderItems[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $qty,
                        'price' => $price,
                        'subtotal' => $itemSubtotal,
                    ];
                }

                $deliveryFee = 10000;
                $totalAmount = $subtotal + $deliveryFee;

                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => Order::generateOrderNumber(),
                    'address_id' => $address->id,
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'discount' => 0,
                    'total_amount' => $totalAmount,
                    'payment_method' => 'Bank Transfer',
                    'payment_status' => 'Paid',
                    'order_status' => ['Pending', 'Processing', 'Shipped', 'Delivered'][rand(0, 3)],
                    'notes' => 'Tolong packing yang rapi ya.',
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);

                foreach ($orderItems as $item) {
                    $item['order_id'] = $order->id;
                    OrderItem::create($item);
                }
            }
        }
    }
}
