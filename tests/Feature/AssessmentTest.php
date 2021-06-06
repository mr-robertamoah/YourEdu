<?php

namespace Tests\Feature;

use App\Events\DeleteAssessmentEvent;
use App\Events\NewAssessmentMarker;
use App\Events\NewAssessmentParticipant;
use App\Events\NewAssessmentPendingParticipant;
use App\Events\RemoveAssessmentMarker;
use App\Events\RemoveAssessmentParticipant;
use App\Events\RemoveAssessmentPendingParticipant;
use App\Events\UpdateAssessmentEvent;
use App\Events\UpdateAssessmentParticipant;
use App\Notifications\AssessmentInvitationNotification;
use App\Notifications\AssessmentInvitationResponseNotification;
use App\Notifications\AssessmentJoinNotification;
use App\Notifications\AssessmentJoinRequestNotification;
use App\Notifications\AssessmentJoinResponseNotification;
use App\Notifications\AssessmentNotification;
use App\Notifications\RemoveAssessmentMarkerNotification;
use App\Notifications\RemoveAssessmentParticipantNotification;
use App\Notifications\UpdateAssessmentParticipantNotification;
use App\User;
use App\YourEdu\Admin;
use App\YourEdu\Assessment;
use App\YourEdu\AssessmentSection;
use App\YourEdu\Course;
use App\YourEdu\Discussion;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\Lesson;
use App\YourEdu\Lessonable;
use App\YourEdu\Message;
use App\YourEdu\ParentModel;
use App\YourEdu\Participant;
use App\YourEdu\Payment;
use App\YourEdu\Professional;
use App\YourEdu\Question;
use App\YourEdu\Request;
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
        DB::table('participants')->delete();
        DB::table('messages')->delete();
        DB::table('requests')->delete();
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
        Event::fake();

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
        Event::fake();
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
        $account->ownedCourses()->saveMany($courses);

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
        Event::assertDispatched(UpdateAssessmentEvent::class);
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

    // public function test_validate_can_take_assessment()
    // {
        
    // }

    public function test_can_take_assessment_through_course()
    {
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name
            ])
            ->for(User::factory())
            ->create();
        
        $learner = Learner::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();
            
        //facilitator create course
        $course = Course::factory()->create();
        $account->ownedCourses()->save($course);
        $account->addedCourses()->save($course);

        $course->learners()->attach($learner);
        $course->save();

        $assessment = Assessment::factory()->create();
        $lesson = Lesson::factory()->state([
            'addedby_type' => $account::class,
            'addedby_id' => $account->id,
        ])->create();
        $lesson->save();
        // $course->lessons()->attach($lesson);
        // $course->save();
        $assessment->lessons()->attach($lesson);
        $assessment->save();

        $lessonable = Lessonable::factory()->state([
            'lesson_id' => $lesson->id
        ])->create();

        $lessonable->lessonable()->associate($course);
        $lessonable->save();

        $data = [
            'assessmentId' => $assessment->id
        ];

        $response = $this->json('GET', '/api/assessment/work', $data);

        $response
            ->dump()
            ->assertSuccessful();
    }

    public function test_can_accept_invitation_request()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitators = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->count(2)->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitators->first()::class,
                'addedby_id' => $facilitators->first()->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitators->last()::class,
                'accountable_id' => $facilitators->last()->id,
                'state' => "ADMIN",
                'user_id' => $facilitators->last()->user_id,
            ])->create();
        $participant = $assessment->participants()->create([
            'state' => 'PENDING',
            'user_id' => $facilitator->user_id
        ]);
        $participant->accountable()->associate($facilitator);
        $participant->save();

        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestfrom_type' => $assessment->addedby::class,
                'requestfrom_id' => $assessment->addedby->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/assessment/invitation/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertNotNull($response['participant']);
        $this->assertEquals('ACCEPTED', Request::find($request->id)->state);
        $this->assertEquals('ACTIVE', $participant->refresh()->state);
        Event::assertDispatched(NewAssessmentParticipant::class);
        Notification::assertSentTo(
            $facilitators->first()->user, 
            AssessmentInvitationResponseNotification::class
        );
    }

    public function test_can_accept_invitation_marker_request()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitators = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->count(2)->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitators->first()::class,
                'addedby_id' => $facilitators->first()->id,
            ])->create();

        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestfrom_type' => $assessment->addedby::class,
                'requestfrom_id' => $assessment->addedby->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
                'state' => "PENDING",
                "data" => 'marker'
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/assessment/invitation/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertNull($response['participant']);
        $this->assertTrue($assessment->refresh()->isMarker($facilitator->user_id));
        $this->assertEquals('ACCEPTED', Request::find($request->id)->state);
        Event::assertDispatched(NewAssessmentMarker::class);
        Notification::assertSentTo(
            $facilitators->first()->user, 
            AssessmentInvitationResponseNotification::class
        );
    }

    public function test_can_decline_invitation_request()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory() 
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitators = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->count(2)->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitators->first()::class,
                'addedby_id' => $facilitators->first()->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitators->last()::class,
                'accountable_id' => $facilitators->last()->id,
                'state' => "ADMIN",
                'user_id' => $facilitators->last()->user_id,
            ])->create();
        $participant = $assessment->participants()->create([
            'state' => 'PENDING',
            'user_id' => $facilitator->user_id
        ]);
        $participant->accountable()->associate($facilitator);
        $participant->save();

        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestfrom_type' => $assessment->addedby::class,
                'requestfrom_id' => $assessment->addedby->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'declined'
        ];

        $response = $this->postJson("api/assessment/invitation/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertNull($response['participant']);
        $this->assertNotNull($participant->refresh()->deleted_at);
        $this->assertEquals('DECLINED', Request::find($request->id)->state);
        Event::assertNotDispatched(NewAssessmentParticipant::class);
        Notification::assertSentTo(
            $facilitators->first()->user, 
            AssessmentInvitationResponseNotification::class
        );
    }

    public function test_cannot_respond_to_invitation_participation_request_with_no_participant()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory() 
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitators = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->count(2)->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitators->first()::class,
                'addedby_id' => $facilitators->first()->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitators->last()::class,
                'accountable_id' => $facilitators->last()->id,
                'state' => "ADMIN",
                'user_id' => $facilitators->last()->user_id,
            ])->create();

        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestfrom_type' => $assessment->addedby::class,
                'requestfrom_id' => $assessment->addedby->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'declined'
        ];

        $response = $this->postJson("api/assessment/invitation/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, there is no pending participant for this request.",
            ]);

        // $this->assertNotNull($request->refresh()->deleted_at);
        Event::assertNotDispatched(NewAssessmentParticipant::class);
        Notification::assertNotSentTo(
            $facilitators->first()->user,
            AssessmentInvitationResponseNotification::class
        );
    }

    public function test_cannot_respond_to_invitation_request_with_invalid_action()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitators = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->count(2)->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitators->first()::class,
                'addedby_id' => $facilitators->first()->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitators->last()::class,
                'accountable_id' => $facilitators->last()->id,
                'state' => "ADMIN",
                'user_id' => $facilitators->last()->user_id,
            ])->create();

        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestfrom_type' => $assessment->addedby::class,
                'requestfrom_id' => $assessment->addedby->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'wait'
        ];

        $response = $this->postJson("api/assessment/invitation/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, wait is not a valid response",
            ]);
    }

    public function test_cannot_respond_to_invitation_request_when_not_addressed_to_an_authorized_account()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitators = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->count(2)->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitators->first()::class,
                'addedby_id' => $facilitators->first()->id,
            ])->create();

        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitators->last()::class,
                'requestto_id' => $facilitators->last()->id,
                'requestfrom_type' => $assessment->addedby::class,
                'requestfrom_id' => $assessment->addedby->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/assessment/invitation/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you are not authorized to respond to this request"
            ]);

        Event::assertNotDispatched(NewDiscussionParticipant::class);
        Notification::assertNothingSent();
    }

    public function test_can_accept_a_join_assessment_request()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])
            ->hasParticipants([
                'accountable_type' => $learner::class,
                'accountable_id' => $learner->id,
                'state' => 'PENDING',
                'user_id' => $learner->user_id,
            ])->create();
        
        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestfrom_type' => $learner::class,
                'requestfrom_id' => $learner->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/assessment/join/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertEquals('ACCEPTED', Request::find($request->id)->state);
        $this->assertEquals('ACTIVE', $assessment->getParticipantUsingAccount($learner)?->state);
        Event::assertDispatched(RemoveAssessmentPendingParticipant::class);
        Event::assertDispatched(NewAssessmentParticipant::class);
        Notification::assertSentTo($learner->user, AssessmentJoinResponseNotification::class);
    }

    public function test_can_accept_a_join_assessment_marker_request()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->forUser()->create();

        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $learner::class,
                'addedby_id' => $learner->id,
            ])->create();
        
        $request = Request::factory()
            ->state([
                'requestto_type' => $learner::class,
                'requestto_id' => $learner->id,
                'requestfrom_type' => $facilitator::class,
                'requestfrom_id' => $facilitator->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
                'data' => 'marker'
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/assessment/join/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant', 'marker'
            ]);

        $this->assertEquals('ACCEPTED', Request::find($request->id)->state);
        $this->assertNull($assessment->getParticipantUsingAccount($facilitator));
        $this->assertTrue($assessment->refresh()->isMarker($facilitator->user_id));
        Event::assertDispatched(NewAssessmentMarker::class);
        Notification::assertSentTo($facilitator->user, AssessmentJoinNotification::class);
    }

    public function test_can_decine_a_join_assessment_request()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])
            ->hasParticipants([
                'accountable_type' => $learner::class,
                'accountable_id' => $learner->id,
                'state' => 'PENDING',
                'user_id' => $learner->user_id,
            ])->create();
        
        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestfrom_type' => $learner::class,
                'requestfrom_id' => $learner->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'declined'
        ];

        $response = $this->postJson("api/assessment/join/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertEquals('DECLINED', Request::find($request->id)->state);
        $this->assertNull($assessment->getParticipantUsingAccount($learner));
        Event::assertDispatched(RemoveAssessmentPendingParticipant::class);
        Event::assertNotDispatched(NewAssessmentParticipant::class);
        Notification::assertSentTo($learner->user, AssessmentJoinResponseNotification::class);
    }

    public function test_can_decine_a_join_assessment_marker_request()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->forUser()->create();

        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $learner::class,
                'addedby_id' => $learner->id,
            ])->create();
        
        $request = Request::factory()
            ->state([
                'requestto_type' => $learner::class,
                'requestto_id' => $learner->id,
                'requestfrom_type' => $facilitator::class,
                'requestfrom_id' => $facilitator->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
                'data' => 'marker'
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'declined'
        ];

        $response = $this->postJson("api/assessment/join/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertEquals('DECLINED', Request::find($request->id)->state);
        $this->assertNull($assessment->getParticipantUsingAccount($facilitator));
        $this->assertFalse($assessment->refresh()->isMarker($facilitator->user_id));
        Event::assertNotDispatched(RemoveAssessmentPendingParticipant::class);
        Event::assertNotDispatched(NewAssessmentParticipant::class);
        Event::assertNotDispatched(NewAssessmentMarker::class);
        Notification::assertSentTo($facilitator->user, AssessmentJoinResponseNotification::class);
    }

    public function test_cannot_respond_to_a_join_assessment_request_with_invalid_action()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])
            ->hasParticipants([
                'accountable_type' => $learner::class,
                'accountable_id' => $learner->id,
                'state' => 'PENDING',
                'user_id' => $learner->user_id,
            ])->create();
        
        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestfrom_type' => $learner::class,
                'requestfrom_id' => $learner->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'wait'
        ];

        $response = $this->postJson("api/assessment/join/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, wait is not a valid response",
            ]);

        $this->assertEquals('PENDING', Request::find($request->id)->state);
        $this->assertEquals('PENDING', $assessment->getParticipantUsingAccount($learner)?->state);
        Event::assertNotDispatched(RemoveAssessmentPendingParticipant::class);
        Event::assertNotDispatched(NewAssessmentParticipant::class);
        Notification::assertNotSentTo($learner->user, AssessmentJoinResponseNotification::class);
    }

    public function test_cannot_respond_to_a_join_assessment_request_which_is_not_a_assessment_request()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $facilitator::class,
                'messageable_id' => $facilitator->id,
            ])->create();
        
        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestfrom_type' => $learner::class,
                'requestfrom_id' => $learner->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/assessment/join/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "request with id {$request->id} is not related to a assessment",
            ]);

        $this->assertEquals('PENDING', Request::find($request->id)->state);
        Event::assertNotDispatched(RemoveAssessmentPendingParticipant::class);
        Event::assertNotDispatched(NewAssessmentParticipant::class);
        Notification::assertNotSentTo($learner->user, AssessmentJoinResponseNotification::class);
    }

    public function test_cannot_respond_to_a_join_assessment_if_not_owner()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])
            ->hasParticipants([
                'accountable_type' => $learner::class,
                'accountable_id' => $learner->id,
                'state' => 'PENDING',
                'user_id' => $learner->user_id,
            ])->create();
        
        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestfrom_type' => $learner::class,
                'requestfrom_id' => $learner->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/assessment/join/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'you are not authorized to perform this action on this assessment',
            ]);

        $this->assertEquals('PENDING', Request::find($request->id)->state);
        $this->assertEquals('PENDING', $assessment->getParticipantUsingAccount($learner)?->state);
        Event::assertNotDispatched(RemoveAssessmentPendingParticipant::class);
        Event::assertNotDispatched(NewAssessmentParticipant::class);
        Notification::assertNotSentTo($learner->user, AssessmentJoinResponseNotification::class);
    }

    public function test_cannot_respond_to_a_join_assessment_request_without_a_pending_participant()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])->create();
        
        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestfrom_type' => $learner::class,
                'requestfrom_id' => $learner->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/assessment/join/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "no participant was found for this request."
            ]);

        $this->assertEquals('PENDING', Request::find($request->id)->state);
        Event::assertNotDispatched(RemoveAssessmentPendingParticipant::class);
        Event::assertNotDispatched(NewAssessmentParticipant::class);
        Notification::assertNotSentTo($learner->user, AssessmentJoinResponseNotification::class);
    }

    public function test_can_join_public_assessment()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $learner::class,
                'addedby_id' => $learner->id,
                'type' => 'PUBLIC'
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
        ];

        $response = $this->postJson("api/assessment/{$assessment->id}/join", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertEquals('ACTIVE', $assessment->getParticipantUsingAccount($facilitator)?->state);
        Event::assertDispatched(NewAssessmentParticipant::class);
        Notification::assertSentTo($assessment->addedby->user, AssessmentJoinNotification::class);
    }

    public function test_can_join_public_assessment_as_marker()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $learner::class,
                'addedby_id' => $learner->id,
                'type' => 'PUBLIC'
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'type' => "marker"
        ];

        $response = $this->postJson("api/assessment/{$assessment->id}/join", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant', 'marker'
            ]);

        $this->assertNull($response['participant']);
        $this->assertNotNull($response['marker']);
        $this->assertNull($assessment->getParticipantUsingAccount($facilitator));
        Event::assertDispatched(NewAssessmentMarker::class);
        Notification::assertSentTo($assessment->addedby->user, AssessmentJoinNotification::class);
    }

    public function test_can_send_join_request_to_private_assessment()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $learner::class,
                'addedby_id' => $learner->id,
                'type' => 'PRIVATE'
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
        ];

        $response = $this->postJson("api/assessment/{$assessment->id}/join", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful', 'participant' => null
            ]);

        $this->assertEquals('PENDING', $assessment->getParticipantUsingAccount($facilitator)?->state);
        Event::assertDispatched(NewAssessmentPendingParticipant::class);
        Notification::assertSentTo($assessment->addedby->user, AssessmentJoinRequestNotification::class);
    }

    public function test_can_send_join_request_to_private_assessment_as_marker()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $learner::class,
                'addedby_id' => $learner->id,
                'type' => 'PRIVATE'
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'type' => 'marker'
        ];

        $response = $this->postJson("api/assessment/{$assessment->id}/join", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful', 'participant' => null, 'marker' => null
            ]);

        $this->assertCount(1, Request::query()
            ->whereMarkerRequest()->whereSentBy($facilitator)->get()
        );
        $this->assertNull($assessment->getParticipantUsingAccount($facilitator));
        Event::assertNotDispatched(NewAssessmentPendingParticipant::class);
        Notification::assertSentTo($assessment->addedby->user, AssessmentJoinRequestNotification::class);
    }

    public function test_cannot_join_assessment_as_marker_with_invalid_account_type()
    {
        Event::fake();
        Notification::fake();

        $learner1 = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $learner::class,
                'addedby_id' => $learner->id,
                'type' => 'PRIVATE'
            ])->create();

        $data = [
            'account' => "$learner1",
            'accountId' => $learner1->id,
            'type' => 'marker'
        ];

        $response = $this->postJson("api/assessment/{$assessment->id}/join", $data);

        
        $types = implode(' or ', Assessment::MARKERS_ACCOUNT_TYPES);
        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, {$learner1->accountType} account is not valid for a marker. please use {$types} account.",
            ]);

        $this->assertCount(0, Request::query()
            ->whereMarkerRequest()->whereSentBy($learner1)->get()
        );
        $this->assertNull($assessment->getParticipantUsingAccount($learner1));
        Event::assertNotDispatched(NewAssessmentPendingParticipant::class);
        Notification::assertNotSentTo($assessment->addedby->user, AssessmentJoinRequestNotification::class);
    }

    public function test_cannot_join_assessment_as_marker_when_already_a_marker()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();
        $professional = Professional::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $learner::class,
                'addedby_id' => $learner->id,
                'type' => 'PRIVATE'
            ])->create();
        $assessmentable = $assessment->assessmentables()->create();
        $assessmentable->assessmentable()->associate($professional);
        $assessmentable->save();
        
        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'type' => 'marker'
        ];

        $response = $this->postJson("api/assessment/{$assessment->id}/join", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you are already a marker for assessment with name: {$assessment->name}",
            ]);

        $this->assertCount(0, Request::query()
            ->whereMarkerRequest()->whereSentBy($facilitator)->get()
        );
        $this->assertNull($assessment->getParticipantUsingAccount($facilitator));
        Event::assertNotDispatched(NewAssessmentPendingParticipant::class);
        Notification::assertNotSentTo($assessment->addedby->user, AssessmentJoinRequestNotification::class);
    }

    public function test_cannot_join_public_assessment_when_already_an_owner_or_participant()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PUBLIC'
            ])->create();

        $data = [
            'account' => "$learner",
            'accountId' => $learner->id,
        ];

        $response = $this->postJson("api/assessment/{$assessment->id}/join", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, an owner or participant cannot perform this action."
            ]);

        $this->assertNull($assessment->getParticipantUsingAccount($learner)?->state);
        Event::assertNotDispatched(NewAssessmentParticipant::class);
        Notification::assertNotSentTo($assessment->addedby->user, AssessmentJoinNotification::class);
    }

    public function test_cannot_send_join_request_to_private_assessment_when_having_pending_join_request_to_same_assessment()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $learner::class,
                'addedby_id' => $learner->id,
                'type' => 'PRIVATE'
            ])->create();

        $request = Request::factory()
            ->state([
                'requestfrom_type' => $facilitator::class,
                'requestfrom_id' => $facilitator->id,
                'requestable_type' => $assessment::class,
                'requestable_id' => $assessment->id,
                'state' => 'PENDING',
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
        ];

        $response = $this->postJson("api/assessment/{$assessment->id}/join", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you already have a pending request to join this assessment"
            ]);

        Event::assertNotDispatched(NewAssessmentPendingParticipant::class);
        Notification::assertNotSentTo($assessment->addedby->user, AssessmentJoinRequestNotification::class);
    }

    public function test_cannot_join_assessment_when_already_participating_with_different_account()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator1::class,
                'addedby_id' => $facilitator1->id,
                'type' => 'PRIVATE',
            ])
            ->hasParticipants([
                'accountable_type' => $learner::class,
                'accountable_id' => $learner->id,
                'user_id' => $learner->user_id,
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
        ];

        $response = $this->postJson("api/assessment/{$assessment->id}/join", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you are already participating with your learner account.",
            ]);

        Event::assertNotDispatched(NewAssessmentPendingParticipant::class);
        Notification::assertNotSentTo($assessment->addedby->user, AssessmentJoinRequestNotification::class);
    }
    
    public function test_can_invite_participant()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $data = [
            'account' => "$learner",
            'accountId' => $learner->id,
            'assessmentId' => $assessment->id
        ];

        $response = $this->postJson("api/assessment/invitation", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'pendingParticipant'
            ]);

        $this->assertNotNull($assessment->refresh()->getPendingParticipantUsingAccount($learner));
        Event::assertDispatched(NewAssessmentPendingParticipant::class);
        Notification::assertSentTo($learner->user, AssessmentInvitationNotification::class);
    }
    
    public function test_can_invite_marker()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $data = [
            'account' => "$facilitator1",
            'accountId' => $facilitator1->id,
            'assessmentId' => $assessment->id,
            'type' => 'marker'
        ];

        $response = $this->postJson("api/assessment/invitation", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'pendingParticipant'
            ]);

        $this->assertNull($response['pendingParticipant']);
        $this->assertNull($assessment->refresh()->getPendingParticipantUsingAccount($facilitator1));
        $this->assertCount(1, 
            Request::query()->whereMarkerRequest()->whereSentTo($facilitator1)->get()
        );
        Event::assertNotDispatched(NewAssessmentPendingParticipant::class);
        Notification::assertSentTo($facilitator1->user, AssessmentInvitationNotification::class);
    }
    
    public function test_cannot_invite_participant_if_not_owner()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $data = [
            'account' => "$learner",
            'accountId' => $learner->id,
            'assessmentId' => $assessment->id
        ];

        $response = $this->postJson("api/assessment/invitation", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'you are not authorized to perform this action on this assessment',
            ]);

        $this->assertNull($assessment->refresh()->getPendingParticipantUsingAccount($learner));
        Event::assertNotDispatched(NewAssessmentPendingParticipant::class);
        Notification::assertNotSentTo($learner->user, AssessmentInvitationNotification::class);
    }
    
    public function test_cannot_invite_marker_with_invalid_account_type()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $data = [
            'account' => "$learner",
            'accountId' => $learner->id,
            'assessmentId' => $assessment->id,
            'type' => 'marker'
        ];

        $response = $this->postJson("api/assessment/invitation", $data);

        $types = implode(' or ', Assessment::MARKERS_ACCOUNT_TYPES);
        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, {$learner->accountType} account is not valid for a marker. please use {$types} account.",
            ]);

        $this->assertNull($assessment->refresh()->getPendingParticipantUsingAccount($learner));
        $this->assertFalse($assessment->refresh()->isMarker($learner));
        Event::assertNotDispatched(NewAssessmentPendingParticipant::class);
        Notification::assertNotSentTo($learner->user, AssessmentInvitationNotification::class);
    }
    
    public function test_cannot_invite_participant_already_participating_with_different_account()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id'=> $this->user->id
            ])
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $facilitator1 = Facilitator::factory()
            ->state([
                'user_id'=> $learner->user_id
            ])
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE',
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'user_id' => $facilitator1->user_id,
            ])->create();

        $data = [
            'account' => "$learner",
            'accountId' => $learner->id,
            'assessmentId' => $assessment->id
        ];

        $response = $this->postJson("api/assessment/invitation", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you are already participating with your facilitator account.",
            ]);

        $this->assertNull($assessment->refresh()->getPendingParticipantUsingAccount($learner));
        Event::assertNotDispatched(NewAssessmentPendingParticipant::class);
        Notification::assertNotSentTo($learner->user, AssessmentInvitationNotification::class);
    }

    public function test_can_get_participants_when_an_owner()
    {
        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();

        $assessment = assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();
        $assessment1 = assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        for ($i=0; $i < 6; $i++) { 
            $learner = Learner::factory()
                ->forUser()->create();

            Participant::factory()
                ->state([
                    'state' => 'ACTIVE',
                    'user_id' => $learner->user_id,
                    'participation_type' => $assessment::class,
                    'participation_id' => $assessment->id,
                    'accountable_id' => $learner->id,
                    'accountable_type' => $learner::class,
                ])->create();

            Participant::factory()
                ->state([
                    'state' => 'ACTIVE',
                    'user_id' => $learner->user_id,
                    'participation_type' => $assessment1::class,
                    'participation_id' => $assessment1->id,
                    'accountable_id' => $learner->id,
                    'accountable_type' => $learner::class,
                ])->create();
        }

        $response = $this->getJson("api/assessment/{$assessment->id}/participants");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'data', 'meta', 'links'
            ]);

        $this->assertEquals(7, count($response['data']));
    }

    public function test_can_get_participants_when_not_owner()
    {
        $facilitator = Facilitator::factory()
            ->forUser()->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();
        $assessment1 = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        for ($i=0; $i < 6; $i++) { 
            $learner = Learner::factory()
                ->forUser()->create();

            Participant::factory()
                ->state([
                    'state' => 'ACTIVE',
                    'user_id' => $learner->user_id,
                    'participation_type' => $assessment::class,
                    'participation_id' => $assessment->id,
                    'accountable_id' => $learner->id,
                    'accountable_type' => $learner::class,
                ])->create();

            Participant::factory()
                ->state([
                    'state' => 'ACTIVE',
                    'user_id' => $learner->user_id,
                    'participation_type' => $assessment1::class,
                    'participation_id' => $assessment1->id,
                    'accountable_id' => $learner->id,
                    'accountable_type' => $learner::class,
                ])->create();
        }

        $response = $this->getJson("api/assessment/{$assessment->id}/participants");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'data', 'meta', 'links'
            ]);

        $this->assertEquals(7, count($response['data']));
    }

    public function test_can_update_assessment_participant_when_owner_with_valid_action()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $assessment = assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $assessment->participants()
            ->create([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'user_id' => $facilitator1->user_id,
                'state' => 'ACTIVE'
            ]);

        $data = [
            'action' => 'banned'
        ];

        $response = $this->putJson("api/assessment/participant/{$participant->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertEquals('BANNED', $assessment->refresh()->getParticipantUsingAccount($facilitator1)->state);
        Event::assertDispatched(UpdateAssessmentParticipant::class);
        Notification::assertSentTo($facilitator1->user, UpdateAssessmentParticipantNotification::class);
    }

    public function test_cannot_update_assessment_participant_when_not_owner_with_valid_action()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->forUser()->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $assessment->participants()
            ->create([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'user_id' => $facilitator1->user_id,
                'state' => 'ACTIVE'
            ]);

        $data = [
            'action' => 'banned'
        ];

        $response = $this->putJson("api/assessment/participant/{$participant->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'you are not authorized to perform this action on this assessment'
            ]);

        $this->assertEquals('ACTIVE', $assessment->refresh()->getParticipantUsingAccount($facilitator1)->state);
        Event::assertNotDispatched(UpdateAssessmentParticipant::class);
        Notification::assertNotSentTo($facilitator1->user, UpdateAssessmentParticipantNotification::class);
    }

    public function test_cannot_update_assessment_participant_when_an_admin_with_invalid_action()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $assessment->participants()
            ->create([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'user_id' => $facilitator1->user_id,
                'state' => 'ACTIVE'
            ]);

        $data = [
            'action' => 'wait'
        ];

        $response = $this->putJson("api/assessment/participant/{$participant->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, the action wait is invalid."
            ]);

        $this->assertEquals('ACTIVE', $assessment->refresh()->getParticipantUsingAccount($facilitator1)->state);
        Event::assertNotDispatched(UpdateAssessmentParticipant::class);
        Notification::assertNotSentTo($facilitator1->user, UpdateAssessmentParticipantNotification::class);
    }

    public function test_can_delete_participant_when_an_owner()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $assessment->participants()
            ->create([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'user_id' => $facilitator1->user_id,
                'state' => 'ACTIVE'
            ]);

        $response = $this->deleteJson("api/assessment/participant/{$participant->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful'
            ]);

        $this->assertNull($assessment->getParticipantUsingAccount($facilitator1));
        Event::assertDispatched(RemoveAssessmentParticipant::class);
        Notification::assertSentTo($facilitator1->user, RemoveAssessmentParticipantNotification::class);
    }

    public function test_cannot_delete_participant_when_not_an_owner()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->forUser()
            ->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $assessment->participants()
            ->create([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'user_id' => $facilitator1->user_id,
                'state' => 'ACTIVE'
            ]);

        $response = $this->deleteJson("api/assessment/participant/{$participant->id}");

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'you are not authorized to perform this action on this assessment'
            ]);

        $this->assertNotNull($assessment->getParticipantUsingAccount($facilitator1));
        Event::assertNotDispatched(RemoveAssessmentParticipant::class);
        Notification::assertNotSentTo($facilitator1->user, RemoveAssessmentParticipantNotification::class);
    }

    public function test_cannot_delete_marker_when_not_an_owner()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->forUser()
            ->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $marker = $assessment->assessmentables()
            ->create([
                'assessmentable_type' => $facilitator1::class,
                'assessmentable_id' => $facilitator1->id,
            ]);

        $response = $this->deleteJson("api/assessment/marker/{$marker->id}");

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'you are not authorized to perform this action on this assessment'
            ]);

        $this->assertTrue($assessment->isMarker($facilitator1->user_id));
        Event::assertNotDispatched(RemoveAssessmentParticipant::class);
        Notification::assertNotSentTo($facilitator1->user, RemoveAssessmentParticipantNotification::class);
    }

    public function test_cannot_delete_marker_without_assessmentable_when_not_an_owner()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->forUser()
            ->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $marker = $assessment->assessmentables()->create();

        $response = $this->deleteJson("api/assessment/marker/{$marker->id}");

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'sorry 😞,  account is not a marker for this assessment.'
            ]);

        $this->assertFalse($assessment->isMarker($facilitator1->user_id));
        Event::assertNotDispatched(RemoveAssessmentParticipant::class);
        Notification::assertNotSentTo($facilitator1->user, RemoveAssessmentParticipantNotification::class);
    }

    public function test_can_delete_own_participant()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator1::class,
                'addedby_id' => $facilitator1->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $assessment->participants()
            ->create([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'user_id' => $facilitator->user_id,
                'state' => 'ACTIVE'
            ]);

        $response = $this->deleteJson("api/assessment/participant/{$participant->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful'
            ]);

        $this->assertNull($assessment->getParticipantUsingAccount($facilitator));
        Event::assertDispatched(RemoveAssessmentParticipant::class);
        Notification::assertSentTo($assessment->addedby->user, RemoveAssessmentParticipantNotification::class);
    }

    public function test_can_delete_own_marker()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator1::class,
                'addedby_id' => $facilitator1->id,
                'type' => 'PRIVATE'
            ])->create();

        $marker = $assessment->assessmentables()
            ->create([
                'assessmentable_type' => $facilitator::class,
                'assessmentable_id' => $facilitator->id,
            ]);

        $response = $this->deleteJson("api/assessment/marker/{$marker->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful'
            ]);

        $this->assertFalse($assessment->isMarker($facilitator->user_id));
        Event::assertDispatched(RemoveAssessmentMarker::class);
        Notification::assertSentTo($assessment->addedby->user, RemoveAssessmentMarkerNotification::class);
    }

    public function test_can_delete_participant_as_authorized_user()
    {
        Event::fake();
        Notification::fake();

        $parent = ParentModel::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();
        $learner = Learner::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $parent->wards()->attach($learner);

        $facilitator = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $assessment = Assessment::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $assessment->participants()
            ->create([
                'accountable_type' => $learner::class,
                'accountable_id' => $learner->id,
                'user_id' => $learner->user_id,
                'state' => 'ACTIVE'
            ]);

        $response = $this->deleteJson("api/assessment/participant/{$participant->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful'
            ]);

        $this->assertNull($assessment->getParticipantUsingAccount($facilitator));
        Event::assertDispatched(RemoveAssessmentParticipant::class);
        Notification::assertSentTo($assessment->addedby->user, RemoveAssessmentParticipantNotification::class);
    }
}
