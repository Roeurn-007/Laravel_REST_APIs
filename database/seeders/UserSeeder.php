<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::updateOrCreate(
            ['email' => 'roeurn.ros@student.passerellesnumeriques.org'],
            [ 
                'name' => 'Administrator', 
                'password' => Hash::make('12345678'), 
                'is_admin' => true,
            ]
        ); 

         User::updateOrCreate(
            ['email' => 'user@example.com'],
            [ 
                'name' => 'John Customer', 
                'password' => Hash::make('password'), 
                'is_admin' => false,
            ]
        ); 
    }
}
