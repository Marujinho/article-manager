<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Database\Factories\UserFactory;

class UserAuthControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */

// test register
    public function test_register_success()
    {
        $email = 'johndoe+' . rand(1000, 9999) . '@example.com';
        $data = [
            'name' => 'John Doe',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/user/register', $data);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'access_token',
                    'token_type',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                    ],
                ])
                
                ->assertJsonFragment([
                    'name' => 'John Doe',
                    'email' => $email, 
                ]);
    }

    public function test_register_error_invalid_password_format()
    {
        $email = 'johndoe+' . rand(1000, 9999) . '@example.com';
        $data = [
            'name' => 'John Doe',
            'email' => $email,
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $response = $this->postJson('/api/user/register', $data);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['password']);
    }

    public function test_register_error_invalid_email_format()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/user/register', $data);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    public function test_register_error_missing_required_field()
    {
        $data = [
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/user/register', $data);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }


// test login
    public function test_login_success()
    {
        $user = UserFactory::new()->create([
            'password' => Hash::make('validpassword'),
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'validpassword',
        ];

        $response = $this->postJson('/api/user/login', $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
        ]);

        $response->assertJsonFragment([
            'email' => $user->email,
        ]);
    }

    public function test_login_error_incorrect_password()
    {
        $user = UserFactory::new()->create([
            'password' => Hash::make('validpassword'),
        ]);

        $data = [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/user/login', $data);

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Invalid credentials',
        ]);
    }

    public function test_login_error_email_not_registered()
    {
        $data = [
            'email' => 'unregistered@example.com',
            'password' => 'somepassword',
        ];

        $response = $this->postJson('/api/user/login', $data);

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Invalid credentials',
        ]);
    }


// test logout
    public function test_logout_success()
    {
        $user = UserFactory::new()->create([
            'password' => Hash::make('validpassword'),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                        ->postJson('/api/user/logout');

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Logged out successfully',
        ]);

        $this->assertCount(0, $user->tokens);
    }

    public function test_logout_failure_no_token()
    {
        $response = $this->postJson('/api/user/logout');

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }
}
