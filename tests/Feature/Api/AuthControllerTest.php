<?php

namespace Tests\Feature\Api;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_log_in_and_previous_tokens_are_revoked(): void
    {
        [$user, $team] = $this->createUserWithTeam('secret123');
        $user->createToken('old-token');

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.teams.0.id', $team->id);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        [$user] = $this->createUserWithTeam('secret123');

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertUnauthorized()
            ->assertJson([
                'message' => 'Credenciales incorrectas',
            ]);
    }

    public function test_authenticated_user_endpoint_returns_teams(): void
    {
        [$user, $team] = $this->createUserWithTeam();
        $token = $user->createToken('api-test')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('id', $user->id)
            ->assertJsonPath('teams.0.id', $team->id);
    }

    public function test_logout_revokes_only_the_current_access_token(): void
    {
        [$user] = $this->createUserWithTeam();
        $currentToken = $user->createToken('current-token')->plainTextToken;
        $user->createToken('secondary-token');

        $this->withToken($currentToken)
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJson([
                'message' => 'Sesion cerrada correctamente',
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);
        $this->assertSame(
            'secondary-token',
            PersonalAccessToken::query()->sole()->name,
        );
    }

    public function test_logout_without_current_token_revokes_all_tokens(): void
    {
        [$user] = $this->createUserWithTeam();
        $user->createToken('first');
        $user->createToken('second');

        $this->actingAs($user)
            ->postJson('/api/logout')
            ->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    /**
     * @return array{0: User, 1: Team}
     */
    private function createUserWithTeam(string $password = 'password'): array
    {
        $team = Team::factory()->create();
        $user = User::factory()->create([
            'password' => $password,
            'current_team_id' => $team->id,
        ]);

        $user->teams()->attach($team);

        return [$user, $team];
    }
}
