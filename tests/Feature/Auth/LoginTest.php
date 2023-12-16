<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @test */
    public function a_user_can_login_with_correct_credentials()
    {
        // Assuming you have a user in your seeder with these credentials
        $email = 'test@example.com';
        $password = 'password'; // The plaintext password used in the seeder

        $user = User::where('email', $email)->first();

        $response = $this->postJson('/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function a_user_cannot_login_with_incorrect_credentials()
    {
        // Use the seeded user's email and an incorrect password
        $email = 'test@example.com';
        $wrongPassword = 'wrong-password';

        // Make a post request to the login endpoint with incorrect credentials
        $response = $this->postJson('/api/login', [
            'email' => $email,
            'password' => $wrongPassword,
        ]);

        // Assert that the response status is 401 Unauthorized
        $response->assertStatus(401);
        $this->assertGuest(); // Assert the user is not authenticated
    }

    /** @test */
    public function a_user_cannot_login_with_invalid_email_format()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid-email',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function a_user_cannot_login_without_email()
    {
        $response = $this->postJson('/api/login', [
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function a_user_cannot_login_without_password()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
        $this->assertGuest();
    }

    /** @test */
    public function a_user_cannot_login_with_unregistered_email()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistentemail@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401);
        $this->assertGuest();
    }

    /** @test */
    public function a_user_cannot_login_with_empty_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
        $this->assertGuest();
    }
}
