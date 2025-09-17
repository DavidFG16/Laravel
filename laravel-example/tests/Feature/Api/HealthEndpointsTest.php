<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AuthTestCase;
use App\Models\User;
use Laravel\Passport\Passport;


class HealthEndpointsTest extends AuthTestCase
{
    use RefreshDatabase;
    /**
     * Verificar el endpoint publico Health
     */
    public function test_health_publico_response_ok(): void
    {
        $this->getJson('/api/health')
            ->assertOk()
            ->assertJson(['ok' => true]);
    }

    public function test_any_auth_require_authentication_y_gate_view_health():void
    {
        $viewer = User::factory()->withRole('viewer')->create();

        Passport::actingAs($viewer, ['posts.read']);

        $this->getJson('/api/health-any-auth')
            ->assertOk()
            ->assertJson(['ok' => true]);
    }


    public function test_any_auth_require_authentication_y_gate_view_health_unauthorize():void
    {
        $viewer = User::factory()->withRole('viewer')->create();
        
        Passport::actingAs($viewer, ['posts.read']);

        $this->getJson('/api/health-admin')
            ->assertForbidden()
            ->assertJsonStructure(['status', 'message', 'errors']);
    }
}
