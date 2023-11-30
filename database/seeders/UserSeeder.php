<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => "Admin",
            'email_verified_at' => Date::now(),
            'email' => "admin@example.com",
            'password' => "Admin_1234",
            'roles' => "admin"
        ]);

        User::create([
            'name' => "Buyer",
            'email_verified_at' => Date::now(),
            'email' => "buyer@example.com",
            'password' => "Buyer_1234",
            'roles' => "buyer"
        ]);

        User::create([
            'name' => "Seller 1",
            'email' => "seller1@example.com",
            'password' => "Seller_1234",
            'roles' => "seller"
        ]);

        User::create([
            'name' => "Seller 2",
            'email_verified_at' => Date::now(),
            'email' => "seller2@example.com",
            'password' => "Seller_1234",
            'roles' => "seller"
        ]);
    }
}
