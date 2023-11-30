<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 10; $i++) {
            Product::query()->create([
                'user_id' => 3,
                'name' => $faker->word(),
                'price' => $faker->numberBetween(10000, 20000000),
                'description' => $faker->paragraph
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            Product::query()->create([
                'user_id' => 4,
                'name' => $faker->word(),
                'price' => $faker->numberBetween(10000, 20000000),
                'description' => $faker->paragraph
            ]);
        }
    }
}
