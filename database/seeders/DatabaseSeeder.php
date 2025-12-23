<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed data dalam urutan yang benar (karena foreign key constraints)
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            BannerSeeder::class,
        ]);

        $this->command->info('✅ All seeders completed successfully!');
        $this->command->newLine();
        $this->command->info('📊 Database Summary:');
        $this->command->info('- Users: ' . \App\Models\User::count());
        $this->command->info('- Categories: ' . \App\Models\Category::count());
        $this->command->info('- Products: ' . \App\Models\Product::count());
        $this->command->info('- Banners: ' . \App\Models\Banner::count());
        $this->command->newLine();
        $this->command->info('🔐 Test Login Credentials:');
        $this->command->info('Email: admin@kede.com | Password: password');
        $this->command->info('Email: test@kede.com | Password: test123');
    }
}
