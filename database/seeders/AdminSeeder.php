<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'vicki240593@gmail.com'],
            [
                'name' => 'Admin Lapor Sarpras',
                'password' => bcrypt('admin12345'),
            ]
        );

        $user->assignRole('admin');
    }
}
