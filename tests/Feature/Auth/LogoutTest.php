<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

//Most tests cases are invalid due to: https://laracasts.com/discuss/channels/testing/tdd-with-sanctum-issue-with-user-logout-case?page=1&replyId=797893
//Postman collection is provided!
//https://github.com/laravel/framework/pull/35692 changed and reverted back
class LogoutTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Auth::shouldUse('api');

        $this->withHeaders([
            'Accept' => 'application/json'
        ]);
    }


    /**
     * Log in and get token.
     *
     * @return string
     */
    private function getLoginToken(): string
    {
        Auth::shouldUse('web');

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        return $response->json('token');
    }


    /** @test */
    public function a_user_can_logout_successfully()
    {
        $token = $this->getLoginToken();

        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $logoutResponse->assertStatus(200);
        $logoutResponse->assertJson(['message' => 'Logout successful']);
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
    public function a_user_cannot_logout_with_a_token_of_non_existing_user()
    {
        $token = 'some_generated_token';

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
