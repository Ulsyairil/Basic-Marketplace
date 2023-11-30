<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Faker\Factory as Faker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    private function login()
    {
        $user = User::query()
            ->where('email', 'seller2@example.com')
            ->whereNotNull('email_verified_at')
            ->first();

        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];
        return $headers;
    }

    public function test_pagination()
    {
        $payload = [
            "page" => 1,
            "limit" => 10,
            "trash" => false,
            "key" => "id",
            "order" => true,
            "search" => ""
        ];
        $this->json("POST", "api/products", $payload, $this->login())
            ->assertStatus(200)
            ->assertJsonStructure([
                "current_page",
                "data",
                "first_page_url",
                "from",
                "last_page",
                "last_page_url",
                "links",
                "next_page_url",
                "path",
                "per_page",
                "prev_page_url",
                "to",
                "total",
            ]);
    }

    public function test_get()
    {
        $payload = [
            "id" => 11
        ];
        $this->json("GET", "api/product", $payload, $this->login())
            ->assertStatus(200)
            ->assertJsonStructure([
                "message",
                "data"
            ]);
    }

    public function test_create()
    {
        $faker = Faker::create('id_ID');

        $payload = [
            'name' => $faker->word(),
            'price' => $faker->numberBetween(10000, 20000000),
            'description' => $faker->paragraph
        ];
        $this->json("POST", "api/product", $payload, $this->login())
            ->assertStatus(200)
            ->assertJsonStructure([
                "message",
                "data"
            ]);
    }

    public function test_update()
    {
        $faker = Faker::create('id_ID');

        $user = User::query()
            ->where('email', 'seller2@example.com')
            ->whereNotNull('email_verified_at')
            ->first();

        $product = Product::query()->create([
            'user_id' => $user->id,
            'name' => $faker->word(),
            'price' => $faker->numberBetween(10000, 20000000),
            'description' => $faker->paragraph
        ]);

        $payload = [
            'id' => $product->id,
            'name' => $faker->word(),
            'price' => $faker->numberBetween(10000, 20000000),
            'description' => $faker->paragraph
        ];

        $this->json("PUT", "api/product", $payload, $this->login())
            ->assertStatus(200)
            ->assertJsonStructure([
                "message",
                "data"
            ]);
    }

    public function test_delete()
    {
        $faker = Faker::create('id_ID');

        $user = User::query()
            ->where('email', 'seller2@example.com')
            ->whereNotNull('email_verified_at')
            ->first();

        $product = Product::query()->create([
            'user_id' => $user->id,
            'name' => $faker->word(),
            'price' => $faker->numberBetween(10000, 20000000),
            'description' => $faker->paragraph
        ]);

        $payload = [
            "id" => $product->id
        ];

        $this->json("PUT", "api/product/delete", $payload, $this->login())
            ->assertStatus(200)
            ->assertJsonStructure([
                "message",
                "data"
            ]);
    }

    public function test_restore()
    {
        $faker = Faker::create('id_ID');

        $user = User::query()
            ->where('email', 'seller2@example.com')
            ->whereNotNull('email_verified_at')
            ->first();

        $product = Product::query()->create([
            'user_id' => $user->id,
            'name' => $faker->word(),
            'price' => $faker->numberBetween(10000, 20000000),
            'description' => $faker->paragraph
        ]);

        Product::query()->where('id', $product->id)->delete();

        $payload = [
            "id" => $product->id
        ];

        $this->json("PUT", "api/product/restore", $payload, $this->login())
            ->assertStatus(200)
            ->assertJsonStructure([
                "message",
                "data"
            ]);
    }

    public function test_destroy()
    {
        $faker = Faker::create('id_ID');

        $user = User::query()
            ->where('email', 'seller2@example.com')
            ->whereNotNull('email_verified_at')
            ->first();

        $product = Product::query()->create([
            'user_id' => $user->id,
            'name' => $faker->word(),
            'price' => $faker->numberBetween(10000, 20000000),
            'description' => $faker->paragraph
        ]);

        $payload = [
            "id" => $product->id
        ];

        $this->json("DELETE", "api/product", $payload, $this->login())
            ->assertStatus(200)
            ->assertJsonStructure([
                "success"
            ]);
    }
}
