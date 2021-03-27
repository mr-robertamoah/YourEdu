<?php

namespace Tests\Feature;

use App\Events\DeleteAssessmentEvent;
use App\Events\UpdateAssessmentEvent;
use App\Notifications\AssessmentNotification;
use App\User;
use App\YourEdu\Admin;
use App\YourEdu\Assessment;
use App\YourEdu\AssessmentSection;
use App\YourEdu\Course;
use App\YourEdu\Discussion;
use App\YourEdu\Payment;
use App\YourEdu\Professional;
use App\YourEdu\Question;
use App\YourEdu\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AssessmentTest extends TestCase
{
    use WithFaker;

    private $user;

    public function setUp():void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'api');

        DB::table('assessments')->delete();
        DB::table('assessment_sections')->delete();
        DB::table('questions')->truncate();
        DB::table('possible_answers')->delete();
        DB::table('learners')->delete();
        DB::table('facilitators')->delete();
        DB::table('parent_models')->delete();
        DB::table('admins')->delete();
        DB::table('schools')->delete();
        DB::table('professionals')->delete();
    }
    
    public function test_validation_of_required_data_for_creating_assessment()
    {
        $data = [

        ];

        $response = $this->postJson("/api/assessment", $data);

        $response
            ->dump()
            ->assertJsonValidationErrors([
                'name', 'assessmentSections', 'account', 'accountId'
            ]);
    }
    
    public function test_can_create_assessment()
    {
        Notification::fake();

        $account = Professional::factory()
            ->state(['name' => $this->faker->name])
            ->for($this->user)
            ->create();
        $number = 0;
        
        $data = [
            'name' => $this->faker->word,
            'account' => $account->accountType,
            'accountId' => $account->id,
            'assessmentSections' => json_encode([
                (object) [
                    'name' => $this->faker->word,
                    'random' => true,
                    'maxQuestions' => 1,
                    'instruction' => $this->faker->word,
                    'position' => $number++,
                    'questions' => [
                        (object) [
                            'body' => $this->faker->sentence,
                            'scoreOver' => $this->faker->numberBetween(5,100),
                        ],
                        (object) [
                            'body' => $this->faker->sentence,
                            'scoreOver' => $this->faker->numberBetween(5,100),
                        ]
                    ]
                ],
                (object) [
                    'name' => $this->faker->word,
                    'instruction' => $this->faker->word,
                    'position' => $number++,
                    'questions' => [
                        (object) [
                            'body' => $this->faker->sentence,
                            'possibleAnswers' => [
                                (object) [
                                    'option' => true,
                                    'position' => 1,
                                ],
                                (object) [
                                    'option' => false,
                                    'position' => 2,
                                ]
                            ],
                            'scoreOver' => $this->faker->numberBetween(5,100),
                        ],
                        (object) [
                            'body' => $this->faker->sentence,
                            'possibleAnswers' => [
                                (object) [
                                    'option' => true,
                                    'position' => 1,
                                ],
                                (object) [
                                    'option' => false,
                                    'position' => 2,
                                ]
                            ],
                            'scoreOver' => $this->faker->numberBetween(5,100),
                        ]
                    ]
                ],
            ]),
            'discussionData' => json_encode(
                (object) [
                    'title' => $this->faker->sentence,
                    'preamble' => $this->faker->sentence,
                ]
            ),
            'discussionFiles' => [
                UploadedFile::fake()->image('new_image.png')
            ],
        ];

        $response = $this->postJson("/api/assessment", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson(
                ['message' => 'successful']
            );

        $this->assertDatabaseCount('assessments',1);
        $this->assertEquals(1,$response['assessment']['discussions']);
        $this->assertDatabaseCount('assessment_sections',2);
        $this->assertDatabaseCount('questions',4);
        $this->assertDatabaseCount('possible_answers',4);

    }
    
    public function test_validation_of_required_data_for_updating_assessment()
    {
        $data = [

        ];

        $response = $this->putJson("/api/assessment/1", $data);

        $response
            ->dump()
            ->assertJsonValidationErrors([
                'name', 'account', 'accountId'
            ])
            ->assertJsonMissingValidationErrors([
                'assessmentSections',
            ]);
    }
    
    public function test_can_update_assessment_with_notification()
    {
        Notification::fake();

        $account = School::factory()
            ->state(['company_name' => $this->faker->company])
            ->for($this->user, 'owner')
            ->create();
        
        $assessment = Assessment::factory()
            ->for($account, 'addedby')
            ->has(AssessmentSection::factory()
                ->count(2)
                ->has(Question::factory()
                    ->for($account, 'addedby')
                    ->count(3))
            )
            ->create();

        $assessmentSections = AssessmentSection::factory()
            ->count(2)
            ->has(Question::factory()
                ->for($account, 'addedby')->count(3))
            ->for($assessment)
            ->create();

        $questions = Question::factory()->count(2)->create();
        
        $assessmentSections->first()->questions()->saveMany($questions);

        $assessment->assessmentSections()
            ->saveMany($assessmentSections);

        $courses = Course::factory()->count(2)->create();
        $professional = Professional::factory()
            ->state(['name' => $this->faker->name])
            ->for(User::factory()->create())
            ->create();
        $professional->addedCourses()->saveMany($courses);
        $professional->ownedCourses()->saveMany($courses);

        $assessment->courses()->attach($courses->first());
        $assessment->save();

        $number = 0;
        
        $data = [
            'name' => 'edited',
            'account' => $account->accountType,
            'accountId' => $account->id,
            'editedAssessmentSections' => json_encode([
                (object) [
                    'assessmentSectionId' => $assessmentSections->first()->id,
                    'name' => 'edited assessment section',
                    'random' => true,
                    'maxQuestions' => 1,
                    'instruction' => $this->faker->word,
                    'position' => $number++,
                    'editedQuestions' => [
                        (object) [
                            'body' => "how are you?",
                            'scoreOver' => 10,
                            'questionId' => $questions->first()->id
                        ],
                    ],
                    'removedQuestions' => [
                        (object) [
                            'questionId' => $questions->last()->id
                        ],
                    ]
                ],
            ]),
            'removedAssessmentSections' => json_encode([
                (object) [
                    'assessmentSectionId' => $assessmentSections->last()->id,
                ]
            ]),
            'unattachedItems' => json_encode([
                (object) [
                    'item' => 'course',
                    'itemId' => $courses->first()->id,
                ]
            ]),
            'attachedItems' => json_encode([
                (object) [
                    'item' => 'course',
                    'itemId' => $courses->last()->id,
                ]
            ])
        ];

        $response = $this->putJson("/api/assessment/{$assessment->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson(
                ['message' => 'successful']
            );

        $this->assertDatabaseCount('assessments',1);
        $this->assertDatabaseCount('assessment_sections',4);
        $this->assertSoftDeleted('assessment_sections',[
            'id' => $assessmentSections->last()->id
        ]);
        $this->assertSoftDeleted('questions',[
            'id' => $questions->last()->id
        ]);
        $this->assertDatabaseCount('questions',14);

        Notification::assertTimesSent(1, AssessmentNotification::class);

    }
    
    public function test_validation_of_required_data_for_deleting_assessment()
    {
        $data = [

        ];

        $response = $this->deleteJson("/api/assessment/1", $data);

        $response
            ->dump()
            ->assertJsonValidationErrors([
                'account', 'accountId'
            ])
            ->assertJsonMissingValidationErrors([
                'assessmentSections', 'name'
            ]);
    }
    
    public function test_can_delete_assessment_by_changing_state_by_usedByAnother()
    {
        Notification::fake();
        Event::fake();

        $account = School::factory()
            ->state(['company_name' => $this->faker->company])
            ->for($this->user, 'owner')
            ->create();
        
        $assessment = Assessment::factory()
            ->for($account, 'addedby')
            ->has(AssessmentSection::factory()
                ->count(2)
                ->has(Question::factory()
                    ->for($account, 'addedby')
                    ->count(3))
            )
            ->create();

        $courses = Course::factory()->count(2)->create();
        $professional = Professional::factory()
            ->state(['name' => $this->faker->name])
            ->for(User::factory()->create())
            ->create();
        $professional->addedCourses()->saveMany($courses);
        $professional->ownedCourses()->saveMany($courses);

        $assessment->courses()->attach($courses->first());
        $assessment->save();
        
        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->deleteJson("/api/assessment/{$assessment->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson(
                ['message' => 'successful']
            );

        Event::assertDispatched(UpdateAssessmentEvent::class);
    }
    
    public function test_can_delete_assessment_by_changing_state_by_paymentMadeFor()
    {
        Notification::fake();
        Event::fake();

        $account = School::factory()
            ->state(['company_name' => $this->faker->company])
            ->for($this->user, 'owner')
            ->create();
        
        $assessment = Assessment::factory()
            ->for($account, 'addedby')
            ->has(AssessmentSection::factory()
                ->count(2)
                ->has(Question::factory()
                    ->for($account, 'addedby')
                    ->count(3))
            )
            ->create();

        $payments = Payment::factory()->count(2)->create();

        $assessment->payments()->saveMany($payments);
        $assessment->save();
        
        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->deleteJson("/api/assessment/{$assessment->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson(
                ['message' => 'successful']
            );

        Event::assertDispatched(UpdateAssessmentEvent::class);
    }
    
    public function test_can_delete_assessment()
    {
        Notification::fake();
        Event::fake();

        $account = School::factory()
            ->state(['company_name' => $this->faker->company])
            ->for($this->user, 'owner')
            ->create();
        
        $assessment = Assessment::factory()
            ->for($account, 'addedby')
            ->has(AssessmentSection::factory()
                ->count(2)
                ->has(Question::factory()
                    ->for($account, 'addedby')
                    ->count(3))
            )
            ->create();
        
        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $account::class,
                'raisedby_id' => $account->id,
            ])->create();

        $assessment->discussions()->save($discussion);

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->deleteJson("/api/assessment/{$assessment->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson(
                ['message' => 'successful']
            );

        $this->assertEquals(null, Discussion::find($discussion->id));
        $this->assertSoftDeleted('assessments',[
            'id' => $assessment->id
        ]);
    }

    public function test_validate_can_take_assessment()
    {
        
    }

    public function test_can_take_assessment()
    {
        
    }
}
