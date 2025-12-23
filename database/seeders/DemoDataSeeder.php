<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo user
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
            'phone' => '08123456789',
        ]);

        // Create cart for user
        Cart::create(['user_id' => $user->id]);

        // Create categories
        $categories = [
            [
                'name' => 'Vegetables',
                'description' => 'Fresh vegetables',
                'is_active' => true,
            ],
            [
                'name' => 'Fruits',
                'description' => 'Fresh fruits',
                'is_active' => true,
            ],
            [
                'name' => 'Meat & Fish',
                'description' => 'Fresh meat and fish',
                'is_active' => true,
            ],
            [
                'name' => 'Dairy',
                'description' => 'Dairy products',
                'is_active' => true,
            ],
            [
                'name' => 'Beverages',
                'description' => 'Drinks and beverages',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Create products for each category
            for ($i = 1; $i <= 5; $i++) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $category->name . ' Product ' . $i,
                    'description' => 'This is a high quality ' . strtolower($category->name) . ' product.',
                    'price' => rand(10000, 100000),

                    'unit' => ['kg', 'pcs', 'liter', 'pack'][rand(0, 3)],
                    'stock' => rand(10, 100),
                    'images' => json_encode([
                        'https://via.placeholder.com/400x400?text=Product+' . $i,
                    ]),
                    'is_available' => true,
                    'rating' => rand(30, 50) / 10,
                    'review_count' => rand(0, 50),
                ]);
            }
        }

        // Create banners
        $banners = [
            [
                'title' => 'Summer Sale',
                'image' => 'https://via.placeholder.com/800x400?text=Summer+Sale',
                'link' => null,
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'Fresh Vegetables',
                'image' => 'https://via.placeholder.com/800x400?text=Fresh+Vegetables',
                'link' => null,
                'is_active' => true,
                'order' => 2,
            ],
            [
                'title' => 'New Arrivals',
                'image' => 'https://via.placeholder.com/800x400?text=New+Arrivals',
                'link' => null,
                'is_active' => true,
                'order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Demo User Credentials:');
        $this->command->info('Email: demo@example.com');
        $this->command->info('Password: password');
    }
}
