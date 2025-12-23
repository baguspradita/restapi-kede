<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fruits',
                'description' => 'Buah-buahan segar dan organik langsung dari kebun',
                'image' => 'https://images.unsplash.com/photo-1619566636858-adb3ef26402b?w=500&auto=format&fit=crop&q=60',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vegetables',
                'description' => 'Sayuran hijau dan segar untuk gaya hidup sehat',
                'image' => 'https://images.unsplash.com/photo-1566385101042-1a000c12659b?w=500&auto=format&fit=crop&q=60',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mushroom',
                'description' => 'Berbagai jenis jamur segar pilihan',
                'image' => 'https://images.unsplash.com/photo-1504672281656-e4981d70414b?w=500&auto=format&fit=crop&q=60',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dairy',
                'description' => 'Produk olahan susu, keju, dan mentega berkualitas',
                'image' => 'https://images.unsplash.com/photo-1528498033053-34a815fa9f6e?w=500&auto=format&fit=crop&q=60',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Oats',
                'description' => 'Sereal dan gandum sehat untuk sarapan Anda',
                'image' => 'https://images.unsplash.com/photo-1586439702132-4757049402d2?w=500&auto=format&fit=crop&q=60',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bread',
                'description' => 'Roti segar yang dipanggang setiap hari',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500&auto=format&fit=crop&q=60',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Meats',
                'description' => 'Daging segar dan berkualitas tinggi',
                'image' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=500&auto=format&fit=crop&q=60',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Beverages',
                'description' => 'Minuman segar dan menyehatkan',
                'image' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=500&auto=format&fit=crop&q=60',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
