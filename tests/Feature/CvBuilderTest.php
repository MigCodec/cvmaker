<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Cv;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CvBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_cv_with_selected_items(): void
    {
        $user = User::factory()->create();
        $exp = Experience::create([
            'user_id' => $user->id,
            'title' => 'Backend Dev',
            'company' => 'ACME',
            'start_date' => Carbon::now()->subYear(),
            'current' => true,
        ]);
        $edu = Education::create([
            'user_id' => $user->id,
            'degree' => 'BSc',
            'institution' => 'Uni',
            'start_date' => Carbon::now()->subYears(4),
            'end_date' => Carbon::now()->subYears(2),
        ]);
        $cert = Certificate::create([
            'user_id' => $user->id,
            'name' => 'AWS',
            'date' => Carbon::now()->subMonths(6),
        ]);
        $skill = Skill::create([
            'user_id' => $user->id,
            'name' => 'Laravel',
        ]);

        $payload = [
            'name' => 'CV Backend',
            'target_role' => 'Backend Engineer',
            'summary' => 'Enfoque en APIs',
            'template' => 'default',
            'experiences' => [$exp->id],
            'educations' => [$edu->id],
            'certificates' => [$cert->id],
            'skills' => [$skill->id],
        ];

        $response = $this->actingAs($user)->post(route('cvs.store'), $payload);

        $response->assertRedirect(route('cvs.index'));
        $this->assertDatabaseHas('cvs', [
            'user_id' => $user->id,
            'name' => 'CV Backend',
            'target_role' => 'Backend Engineer',
        ]);
        $cv = Cv::first();
        $this->assertNotNull($cv);
        $this->assertTrue($cv->experiences->contains('id', $exp->id));
        $this->assertTrue($cv->educations->contains('id', $edu->id));
        $this->assertTrue($cv->certificates->contains('id', $cert->id));
        $this->assertTrue($cv->skills->contains('id', $skill->id));
    }

    public function test_preview_uses_selected_items_only(): void
    {
        $user = User::factory()->create();
        $expIncluded = Experience::create([
            'user_id' => $user->id,
            'title' => 'Incluida',
            'start_date' => Carbon::now()->subYears(2),
            'end_date' => Carbon::now()->subYear(),
        ]);
        $expExcluded = Experience::create([
            'user_id' => $user->id,
            'title' => 'Excluida',
            'start_date' => Carbon::now()->subYears(3),
            'end_date' => Carbon::now()->subYears(2),
        ]);

        $cv = Cv::create([
            'user_id' => $user->id,
            'name' => 'CV Target',
            'target_role' => 'Role',
            'template' => 'default',
        ]);
        $cv->experiences()->sync([$expIncluded->id]);

        $response = $this->actingAs($user)->get(route('cv.preview', $cv));

        $response->assertOk();
        $response->assertSee('Incluida');
        $response->assertDontSee('Excluida');
    }
}
