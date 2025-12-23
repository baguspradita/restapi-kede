<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Buah Segar Hari Ini - Diskon 20% untuk Pesanan Pertama',
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=800&auto=format&fit=crop&q=60',
                'link' => '/categories/1',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sayuran Organik - Langsung dari Petani Lokal',
                'image' => 'https://images.unsplash.com/photo-1510627489930-0c1b0ba0fa3e?w=800&auto=format&fit=crop&q=60',
                'link' => '/categories/2',
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Produk Susu & Dairy - Segar dan Menyehatkan',
                'image' => 'https://images.unsplash.com/photo-1528498033053-34a815fa9f6e?w=800&auto=format&fit=crop&q=60',
                'link' => '/categories/4',
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Promo Spesial Akhir Pekan - Hemat hingga 30%',
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=800&auto=format&fit=crop&q=60',
                'link' => '/products',
                'order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('banners')->insert($banners);
    }
}
