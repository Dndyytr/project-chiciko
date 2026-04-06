<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::create([
            'name' => 'dandy',
            'email' => 'dandy.taufiqurrochman@gmail.com',
            'password' => Hash::make('Dandy123'),
            'email_verified_at' => now(),
        ]);

        $user->save();
    }

}
