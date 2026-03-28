<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@nidan.com'],
            [
                'name'     => 'NIDAN Admin',
                'password' => 'NidanAdmin2026!',
                'is_admin' => true,
            ],
        );
    }
}
