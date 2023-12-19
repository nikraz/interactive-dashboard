<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AuthService;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthServiceTest extends TestCase
{
    protected AuthService $authService;
    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->mock(UserRepository::class);
        $this->authService = new AuthService($this->userRepository);
        $this->user = User::where('email', 'test@example.com')->firstOrFail();

    }

    public function test_attempt_login_with_valid_credentials()
    {
        $user = $this->user;

        $this->userRepository->shouldReceive('getUserByEmail')
            ->with('test@example.com')
            ->andReturn($user);

        Auth::shouldReceive('attempt')->with(['email' => 'test@example.com', 'password' => 'password'])
            ->once()
            ->andReturn(true);

        $result = $this->authService->attemptLogin(['email' => 'test@example.com', 'password' => 'password']);

        $this->assertIsArray($result);
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertIsString($result['token']);
    }

    public function test_attempt_login_with_invalid_credentials()
    {
        Auth::shouldReceive('attempt')->withAnyArgs()->once()->andReturn(false);

        $result = $this->authService->attemptLogin(['email' => 'wrong@example.com', 'password' => 'wrongpass']);

        $this->assertFalse($result);
    }

    public function test_logout()
    {
        $user = User::factory()->create();

        $this->authService->logout($user);

        $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $user->id]);
    }
}
