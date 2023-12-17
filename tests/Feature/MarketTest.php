<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class MarketTest extends TestCase
{
    /** @test */
    public function last_update_returns_time_and_prices_for_authenticated_users()
    {
        // Create a new user and authenticate
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Send a request to the last update endpoint
        $response = $this->getJson('/api/market/last-update');

        // Assert the response has a 200 status code and the required structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'last_update',
                'prices' => [
                    '*' => ['symbol', 'bid', 'ask']
                ]
            ]);

        // Optionally, you can assert the time is close to now
        $responseData = $response->json();
        $this->assertEqualsWithDelta(time(), strtotime($responseData['last_update']), 60, 'The last update time should be within the last minute.');
    }

    /** @test */
    public function last_update_endpoint_is_protected()
    {
        // Send a request to the last update endpoint without authentication
        $response = $this->getJson('/api/market/last-update');

        // Assert the response has a 401 unauthorized status code
        $response->assertStatus(401);
    }

    /** @test */
    public function prices_always_have_five_decimal_places()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/market/last-update');

        $response->assertStatus(200);

        $prices = $response->json('prices');

        foreach ($prices as $price) {
            $this->assertMatchesRegularExpression('/^\d+\.\d{5}$/', $price['bid']);
            $this->assertMatchesRegularExpression('/^\d+\.\d{5}$/', $price['ask']);
        }
    }
}
