<?php

namespace Tests\Feature;

use App\User;
use App\YourEdu\Assessment;
use App\YourEdu\Course;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WorkTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'api');

        DB::table('facilitators')->delete();
        DB::table('learners')->delete();
        DB::table('assessments')->delete();
        DB::table('courses')->delete();

    }

    public function test_item_learner_can_access_item_assessment()
    {
        $facilitator = Facilitator::factory()
            ->for(User::factory())
            ->create();
        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();

        $course = Course::factory()
            ->state([
                'ownedby_type' => $facilitator::class,
                'ownedby_id' => $facilitator->id,
            ])
            ->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])
            ->create();
        $course->learners()->attach($learner);
        $course->save();
        $assessment->courses()->attach($course);
        $assessment->save();

        $response = $this->get("api/assessment/{$assessment->id}/work");

        $response
            ->dump()
            ->assertStatus(200);
    }

    public function test_item_learner_cannot_access_item_assessment()
    {
        $facilitator = Facilitator::factory()
            ->for(User::factory())
            ->create();
        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();

        $course = Course::factory()
            ->state([
                'ownedby_type' => $facilitator::class,
                'ownedby_id' => $facilitator->id,
            ])
            ->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])
            ->create();
        $assessment->courses()->attach($course);
        $assessment->save();

        $response = $this->get("api/assessment/{$assessment->id}/work");

        $response
            ->dump()
            ->assertStatus(200);
    }
}
