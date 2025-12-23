<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // FRUITS (category_id: 1)
            [
                'category_id' => 1,
                'name' => 'Apel Fuji Segar',
                'description' => 'Apel Fuji pilihan dengan rasa manis dan tekstur renyah. Kaya akan serat dan vitamin.',
                'price' => 25000,

                'stock' => 50,
                'unit' => '1 kg',
                'images' => json_encode(['https://images.unsplash.com/photo-1560806887-1e4cd0b6bccb?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.8,
                'review_count' => 120,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'name' => 'Pisang Cavendish',
                'description' => 'Pisang Cavendish mulus tanpa noda, matang sempurna dan siap dikonsumsi.',
                'price' => 18000,

                'stock' => 100,
                'unit' => '1 kg',
                'images' => json_encode(['https://images.unsplash.com/photo-1571771894821-ad99026.jpg?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.5,
                'review_count' => 85,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'name' => 'Anggur Merah Tanpa Biji',
                'description' => 'Anggur merah manis tanpa biji, segar dan juicy.',
                'price' => 45000,

                'stock' => 30,
                'unit' => '500 gr',
                'images' => json_encode(['https://images.unsplash.com/photo-1537640538966-79f369b41f8f?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.9,
                'review_count' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'name' => 'Mangga Arumanis',
                'description' => 'Mangga Arumanis pilihan dengan aroma harum dan rasa sangat manis.',
                'price' => 30000,

                'stock' => 40,
                'unit' => '1 kg',
                'images' => json_encode(['https://images.unsplash.com/photo-1553279768-865429fa0078?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.7,
                'review_count' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // VEGETABLES (category_id: 2)
            [
                'category_id' => 2,
                'name' => 'Brokoli Segar',
                'description' => 'Brokoli hijau segar kaya akan nutrisi, baik untuk kesehatan keluarga.',
                'price' => 15000,

                'stock' => 25,
                'unit' => '250 gr',
                'images' => json_encode(['https://images.unsplash.com/photo-1459411621453-7b03977f4bfc?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.6,
                'review_count' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Wortel Organik',
                'description' => 'Wortel hasil pertanian organik, bersih dan manis.',
                'price' => 12000,

                'stock' => 60,
                'unit' => '500 gr',
                'images' => json_encode(['https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.4,
                'review_count' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Tomat Cherry',
                'description' => 'Tomat cherry merah merona, cocok untuk salad atau camilan sehat.',
                'price' => 20000,

                'stock' => 15,
                'unit' => '250 gr',
                'images' => json_encode(['https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.8,
                'review_count' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // DAIRY (category_id: 4)
            [
                'category_id' => 4,
                'name' => 'Susu Segar Full Cream',
                'description' => 'Susu sapi murni full cream, dipasteurisasi untuk menjaga kualitas.',
                'price' => 22000,

                'stock' => 40,
                'unit' => '1 Liter',
                'images' => json_encode(['https://images.unsplash.com/photo-1550583724-12558142ab46?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.9,
                'review_count' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Keju Cheddar Block',
                'description' => 'Keju cheddar berkualitas tinggi dengan rasa gurih yang khas.',
                'price' => 25000,

                'stock' => 50,
                'unit' => '175 gr',
                'images' => json_encode(['https://images.unsplash.com/photo-1486297678162-ad2a19b0584b?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.7,
                'review_count' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // BREAD (category_id: 6)
            [
                'category_id' => 6,
                'name' => 'Roti Gandum Utuh',
                'description' => 'Roti tawar gandum utuh, lebih sehat dan berserat.',
                'price' => 18000,

                'stock' => 20,
                'unit' => '1 Pack',
                'images' => json_encode(['https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.6,
                'review_count' => 95,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 6,
                'name' => 'Croissant Mentega',
                'description' => 'Croissant klasik dengan lapisan renyah dan rasa mentega yang kuat.',
                'price' => 12000,

                'stock' => 15,
                'unit' => '1 Pcs',
                'images' => json_encode(['https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.8,
                'review_count' => 110,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MEATS (category_id: 7)
            [
                'category_id' => 7,
                'name' => 'Daging Sapi Sirloin',
                'description' => 'Daging sapi bagian sirloin kualitas premium, empuk dan juicy.',
                'price' => 150000,

                'stock' => 10,
                'unit' => '500 gr',
                'images' => json_encode(['https://images.unsplash.com/photo-1603048297172-c92544798d5e?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.9,
                'review_count' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 7,
                'name' => 'Dada Ayam Tanpa Tulang',
                'description' => 'Fillet dada ayam segar, rendah lemak dan tinggi protein.',
                'price' => 45000,

                'stock' => 25,
                'unit' => '500 gr',
                'images' => json_encode(['https://images.unsplash.com/photo-1604503468506-a8da13d82791?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.7,
                'review_count' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // BEVERAGES (category_id: 8)
            [
                'category_id' => 8,
                'name' => 'Jus Jeruk Murni',
                'description' => 'Jus jeruk peras murni tanpa tambahan gula dan pengawet.',
                'price' => 28000,

                'stock' => 15,
                'unit' => '500 ml',
                'images' => json_encode(['https://images.unsplash.com/photo-1613478223719-2ab802602423?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.8,
                'review_count' => 55,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 8,
                'name' => 'Kopi Arabika Gayo',
                'description' => 'Biji kopi Arabika Gayo pilihan, aroma kuat dan rasa nikmat.',
                'price' => 85000,

                'stock' => 20,
                'unit' => '250 gr',
                'images' => json_encode(['https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=500&auto=format&fit=crop&q=60']),
                'is_available' => true,
                'rating' => 4.9,
                'review_count' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);
    }
}
