<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_logout_successfully()
    {
        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Logout successful']);
    }

    /** @test */
    public function a_user_cannot_logout_with_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token',
        ])->postJson('/api/logout');

        $response->assertStatus(401);
    }

    /** @test */
    public function a_user_cannot_logout_without_a_token()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }

    /** @test */
    public function a_token_cannot_be_used_after_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/protected-route');
        // TODO replace with an actual protected route

        $response->assertStatus(401);
    }

    /** @test */
    public function a_user_cannot_logout_with_a_token_of_non_existing_user()
    {
        $token = 'some_generated_token';

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
