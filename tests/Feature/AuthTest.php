<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login()
    {
        $payload = ['email' => 'seller2@example.com', 'password' => 'Seller_1234'];
        $this->json("POST", 'api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                "message",
                "access_token",
                "token_type",
            ]);
    }

    public function test_register()
    {
        $payload = [
            "name" => "John Smith",
            "email" => "seller@example.com",
            "roles" => "seller",
            "password" => "Seller_1234",
            "confirm_password" => "Seller_1234",
        ];
        $this->json("POST", 'api/register', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                "message",
                "data" => [
                    "name",
                    "email",
                    "roles",
                    "updated_at",
                    "created_at",
                    "id"
                ]
            ]);
    }

    public function test_logout()
    {
        $user = User::query()
            ->where('email', 'seller2@example.com')
            ->whereNotNull('email_verified_at')
            ->first();

        $token = $user->createToken('auth_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer $token"];
        $this->json('POST', 'api/logout', [], $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                "success"
            ]);
    }
}
