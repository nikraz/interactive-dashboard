<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use GuzzleHttp\Client;

class EmailValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }
//
//    /** @test */
//    public function test_send_validation_email_success()
//    {
//        $user = User::factory()->create();
//        Sanctum::actingAs($user);
//
//        $response = $this->postJson('/api/email/validate/send', ['email' => $user->email]);
//        $response->assertStatus(200);
//    }

//    /** @test */
//    public function test_send_validation_email_with_non_matching_email()
//    {
//        $user = User::factory()->create();
//        Sanctum::actingAs($user);
//
//        // Providing an email that doesn't match the authenticated user's email
//        $nonMatchingEmail = 'nonmatching@example.com';
//        $response = $this->postJson('/api/email/validate/send', ['email' => $nonMatchingEmail]);
//
//        $response->assertStatus(422)
//            ->assertJson(['message' => 'Invalid email address.']);
//    }

//    /** @test */
//    public function test_send_validation_email_unauthenticated()
//    {
//        $response = $this->postJson('/api/email/validate/send', ['email' => 'example@example.com']);
//        $response->assertStatus(401);
//    }

//    /** @test */
//    public function test_send_validation_email_invalid_data()
//    {
//        $user = User::factory()->create();
//        Sanctum::actingAs($user);
//
//        $response = $this->postJson('/api/email/validate/send', ['email' => 'invalid-email']);
//        $response->assertStatus(422);
//    }

    /** @test */
    public function test_validate_email_unauthenticated()
    {
        $response = $this->postJson('/api/email/validate', ['token' => 'some-token']);
        $response->assertStatus(401);
    }

    /** @test */
    public function test_validate_email_invalid_token()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/email/validate', ['token' => 'invalid-token']);
        $response->assertStatus(422);
    }

    /** @test */
    public function test_an_email_is_sent_when_requesting_email_validation()
    {
        //Invalid as mails are not send from tests at this point
        $this->markTestSkipped('This test has been skipped.');

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/email/validate/send', [
            'email' => $user->email,
        ]);

        $client = new Client();

        // Use MailHog API to check if the email was sent
        $response = $client->request('GET', 'http://mailhog:8025/api/v2/messages');
        $messages = json_decode($response->getBody(), true);

        $this->assertStringContainsString('example@example.com', $messages['items'][0]['Raw']['From']);
    }
}
