<?php

namespace Tests\Feature;

use App\Exceptions\DashboardException;
use App\Services\FacilitationService;
use App\User;
use App\YourEdu\ClassModel;
use App\YourEdu\Course;
use App\YourEdu\Extracurriculum;
use App\YourEdu\Facilitator;
use App\YourEdu\Lesson;
use App\YourEdu\Professional;
use App\YourEdu\Subject;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use WithFaker;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'api');

        DB::table('courses')->delete();
        DB::table('class_models')->delete();
        DB::table('extracurriculums')->delete();
        DB::table('subjects')->delete();
        DB::table('programs')->delete();
        DB::table('lessons')->delete();
        DB::table('facilitators')->delete();
        DB::table('professionals')->delete();
    }
    
    public function test_can_request_for_specific_items_for_lesson()
    {
        $account = Facilitator::factory()
            ->state(['name' => $this->faker->name])
            ->for($this->user)
            ->create();

        $subjects = Subject::factory()
            ->state(new Sequence(
                ['name' => 'mathematics'],
                ['name' => 'english language'],
            ))->count(2)->create();
        $account->uniqueSubjectsAdded()->saveMany($subjects);
        
        $classes = ClassModel::factory()->count(2)->create();
        $account->addedClasses()->saveMany($classes);
        $classes->each(function($class) use ($subjects) {
            $class->classSubjects()->saveMany($subjects);
        });

        FacilitationService::addFacilitationDetailsWithModels(
            $classes->random(1)->first(),
            $subjects->random(1)->first(),
            $account
        );
        
        $courses = Course::factory()->count(2)->create();
        $account->addedCourses()->saveMany($courses);
        $account->ownedCourses()->saveMany($courses);

        $lessons = Lesson::factory()
            ->state([
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])->count(2)->create();
        $account->ownedLessons()->saveMany($lessons);
        
        $extracurriculums = Extracurriculum::factory()->count(2)->create();
        $account->addedExtracurriculums()->saveMany($extracurriculums);
        $account->ownedExtracurriculums()->saveMany($extracurriculums);

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'for' => "assessment",
            'items' => [
                'classes', 'courses', 'extracurriculums', 'lessons'
            ]
        ];

        $response = $this->json('GET','/api/dashboard/account/specific/items', $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'data', 'links', 'meta'
            ]);
    }

    public function test_cannot_request_for_specific_items_for_insufficient_data()
    {
        $account = Professional::factory()
            ->state(['name' => $this->faker->name])
            ->for($this->user)
            ->create();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'for' => "",
        ];

        $response = $this->json('GET', '/api/dashboard/account/specific/items', $data);
        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'there is insufficient data for this request.'
            ]);
    }
}
