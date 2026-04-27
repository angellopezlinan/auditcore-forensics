<?php

namespace Tests\Unit\Models;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_only_its_own_tenants(): void
    {
        $allowedTeam = Team::factory()->create();
        $forbiddenTeam = Team::factory()->create();
        $user = User::factory()->create([
            'current_team_id' => $allowedTeam->id,
        ]);

        $user->teams()->attach($allowedTeam);

        $this->assertTrue($user->canAccessTenant($allowedTeam));
        $this->assertFalse($user->canAccessTenant($forbiddenTeam));
    }
}
