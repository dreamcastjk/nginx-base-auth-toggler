<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BaseUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory([
            'name' => 'mksdm',
            'email' => 'mksdm@email.com',
            'password' => Hash::make('mksdm'),
            'is_allowed' => true,
        ])->create();

        User::factory([
            'name' => 'mkssdm',
            'email' => 'mkssdm@email.com',
            'password' => Hash::make('mkssdm'),
            'is_allowed' => false,
        ])->create();
    }
}
