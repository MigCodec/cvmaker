<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_cv_steps(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Armemos tu CV paso a paso');
        $response->assertSee('Experiencia');
        $response->assertSee('Educacion');
        $response->assertSee('Certificados');
        $response->assertSee('Habilidades');
    }
}
