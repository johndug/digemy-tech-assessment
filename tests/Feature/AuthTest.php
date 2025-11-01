<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_can_login_and_logout(): void
    {
        $response = $this->post('/api/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $this->assertAuthenticated();

        $response = $this->actingAs($this->user)->post('/api/logout');
        $response->assertStatus(200);
    }

    public function test_can_get_user(): void
    {
        $response = $this->actingAs($this->user)->get('/api/me');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'user' => [
                    'name',
                    'email',
                ],
            ],
        ]);
        $response->assertJsonFragment([
            'name' => $this->user->name,
            'email' => $this->user->email,
        ]);
    }
}
