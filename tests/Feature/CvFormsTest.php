<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CvFormsTest extends TestCase
{
    use RefreshDatabase;

    public function test_experience_form_creates_entry_and_can_chain(): void
    {
        $user = User::factory()->create();
        $payload = [
            'title' => 'Backend Developer',
            'company' => 'ACME',
            'location' => 'Remote',
            'start_date' => Carbon::now()->subYears(2)->format('Y-m-d'),
            'end_date' => Carbon::now()->subYear()->format('Y-m-d'),
            'description' => 'Built APIs',
            'add_another' => '1',
        ];

        $response = $this->actingAs($user)->post(route('cv.experiences.store'), $payload);

        $response->assertRedirect(route('cv.experiences.create'));
        $this->assertDatabaseHas('experiences', [
            'user_id' => $user->id,
            'title' => 'Backend Developer',
            'company' => 'ACME',
        ]);
    }

    public function test_certificate_form_creates_entry_and_returns_to_index(): void
    {
        $user = User::factory()->create();
        $payload = [
            'name' => 'AWS',
            'issuer' => 'Amazon',
            'date' => Carbon::now()->subMonths(6)->format('Y-m-d'),
            'credential_id' => '123',
            'credential_url' => 'http://example.com/cert',
        ];

        $response = $this->actingAs($user)->post(route('cv.certificates.store'), $payload);

        $response->assertRedirect(route('cv.certificates.index'));
        $this->assertDatabaseHas('certificates', [
            'user_id' => $user->id,
            'name' => 'AWS',
            'issuer' => 'Amazon',
        ]);
    }

    public function test_education_form_handles_current_flag_and_chain(): void
    {
        $user = User::factory()->create();
        $payload = [
            'degree' => 'BSc',
            'institution' => 'Uni',
            'field' => 'CS',
            'location' => 'City',
            'start_date' => Carbon::now()->subYears(4)->format('Y-m-d'),
            'end_date' => Carbon::now()->subYears(2)->format('Y-m-d'),
            'current' => '1',
            'add_another' => '1',
        ];

        $response = $this->actingAs($user)->post(route('cv.educations.store'), $payload);

        $response->assertRedirect(route('cv.educations.create'));
        $this->assertDatabaseHas('education', [
            'user_id' => $user->id,
            'degree' => 'BSc',
            'institution' => 'Uni',
            'end_date' => null,
        ]);
    }

    public function test_skill_form_creates_entry_and_can_order(): void
    {
        $user = User::factory()->create();
        $payload = [
            'name' => 'Laravel',
            'level' => 'Avanzado',
            'order' => 1,
        ];

        $response = $this->actingAs($user)->post(route('cv.skills.store'), $payload);

        $response->assertRedirect(route('cv.skills.index'));
        $this->assertDatabaseHas('skills', [
            'user_id' => $user->id,
            'name' => 'Laravel',
            'level' => 'Avanzado',
            'order' => 1,
        ]);
    }
}
