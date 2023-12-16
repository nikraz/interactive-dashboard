<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;
use GuzzleHttp\Client;

class ResetPasswordTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function a_user_can_request_password_reset_link()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/password/email', [
            'email' => $user->email,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'We have emailed your password reset link.']);
    }

    /** @test */
    public function a_user_can_reset_password_with_valid_token()
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->postJson('/api/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Your password has been reset.']);
    }

    /** @test */
    public function a_user_cannot_request_password_reset_with_invalid_email_format()
    {
        $response = $this->postJson('/api/password/email', [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function a_user_cannot_request_password_reset_with_nonexistent_email()
    {
        $response = $this->postJson('/api/password/email', [
            'email' => 'nonexistent@example.com',
        ]);

        // Even though the email doesn't exist, for security reasons, the response should still indicate success.
        $response->assertStatus(200);
    }
    /** @test */
    public function a_user_cannot_reset_password_with_invalid_token()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/password/reset', [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['email' => 'This password reset token is invalid.']);
    }

    /** @test */
    public function a_user_cannot_reset_password_with_mismatched_password_confirmation()
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->postJson('/api/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function a_user_cannot_reset_password_with_short_password()
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $response = $this->postJson('/api/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function an_email_is_sent_when_requesting_password_reset()
    {
        $user = User::factory()->create();

        $this->postJson('/api/password/email', [
            'email' => $user->email,
        ]);

        // Create a new Guzzle HTTP client
        $client = new Client();

        // Use MailHog API to check if the email was sent
        $response = $client->request('GET', 'http://localhost:8025/api/v2/messages');
        $messages = json_decode($response->getBody(), true);

        $this->assertCount(1, $messages['items']);
        $this->assertStringContainsString($user->email, $messages['items'][0]['Raw']['From']);
    }
}
