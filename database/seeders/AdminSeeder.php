<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::firstOrCreate(
            ['name' => env('TAHBISAN_ADMIN_NAME', 'Admin')],
            ['password' => env('TAHBISAN_ADMIN_PASSWORD', 'admin123')]
        );
    }
}
