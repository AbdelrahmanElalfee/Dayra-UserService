<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthApiTest extends TestCase
{

    use RefreshDatabase;

    protected string $uri = 'api/auth';

    public function test_login() {
        $user = User::factory()->create();

        $response = $this->json(
            'post',
            $this->uri . '/' . 'login',
            ['email' => $user->email, 'password' => 'password']
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'user' => [
                    'email' => $user->email,
                    'name' => $user->name,
                ]
            ]);
        $this->assertArrayHasKey('access_token', $response->json(), 'Token not found');
        $this->assertTrue(
            (count(explode('.', $response->json()['access_token'])) === 3),
            'Failed to validate token'
        );
    }

    public function test_register() {
        $user = User::factory()->make();
        $user = $user->toArray();
        $user['password'] = 'password';
        $user['password_confirmation'] = 'password';

        $response = $this->json(
            'post',
            $this->uri . '/' . 'register',
            $user
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'user' => [
                    'email' => $user['email'],
                    'name' => $user['name'],
                ]
            ]);

        $this->assertArrayHasKey('access_token', $response->json(), 'Token not found');
        $this->assertTrue(
            (count(explode('.', $response->json()['access_token'])) === 3),
            'Failed to validate token'
        );
    }
}
