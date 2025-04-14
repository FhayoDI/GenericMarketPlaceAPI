<?php

namespace Database\Seeders;

use App\Models\User;
    use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('senha123'), // Pode trocar por outra senha
                'is_admin' => true, // Ou 'role' => 'admin', depende do seu schema
            ]
        );
    }
}
