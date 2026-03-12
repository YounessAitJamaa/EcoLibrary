<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class AuthTest extends TestCase
{
    use RefreshDatabase;

        public function test_user_can_register()
        {
            $response = $this->postJson('/api/register', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertStatus(201)
                ->assertJsonStructure(['user', 'token', 'message']);

            $this->assertDatabaseHas('users', [
                'email' => 'test@example.com',
                'role' => 'lecteur',
            ]);
        }

    public function test_user_cannot_register_as_admin()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Hacker', 
            'email' => 'hacker@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_admin' => true,
        ]);

        $response->assertStatus(201);


        $this->assertDatabaseHas('users', [
            'email' => 'hacker@example.com',
            'role' => 'lecteur',
            'is_admin' => 0,
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'token', 'message']);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logout Successful']);
    }
}
