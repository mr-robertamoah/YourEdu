<?php

namespace Tests\Feature;

use App\Events\DeleteProgramEvent;
use App\Events\NewProgramEvent;
use App\Events\UpdateProgramEvent;
use App\Notifications\ProgramCreatedNotification;
use App\Notifications\ProgramDeletedNotification;
use App\Notifications\ProgramUpdatedNotification;
use App\User;
use App\YourEdu\ClassModel;
use App\YourEdu\Course;
use App\YourEdu\Facilitator;
use App\YourEdu\Payment;
use App\YourEdu\Price;
use App\YourEdu\Program;
use App\YourEdu\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProgramTest extends TestCase
{
    use WithFaker;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'api');

        // DB::table('users')->delete();
        DB::table('facilitators')->delete();
        DB::table('class_models')->delete();
        DB::table('schools')->delete();
        DB::table('images')->delete();
        DB::table('courses')->delete();
        DB::table('fees')->delete();
        DB::table('programs')->delete();
        DB::table('academic_years')->delete();
    }

    public function test_can_create_program()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $courses = Course::factory()->count(2)->create();
        $account->courses()->attach($courses);
        $account->save();
        $school->ownedCourses()->save($courses->first());

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'items' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $courses->first()->id
                ]
            ]),
            'type' => 'price',
            'attachments' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $courses->last()->id
                ]
            ]),
            'paymentData' => json_encode([
                (object) [
                    'amount' => '400',
                ]
            ]),
            'facilitate' => json_encode(true),
            'owner' => $school->accountType,
            'ownerId' => $school->id,
            'discussionData' => json_encode(
                (object) [
                    'title' => $this->faker->sentence,
                    'preamble' => $this->faker->sentence,
                ]
            ),
            'discussionFiles' => [
                UploadedFile::fake()->image('new_image.png')
            ],
            'description' => $this->faker->sentence,
        ];

        $response = $this->postJson("/api/program/main", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals(1, $response['program']['discussions']);

        Notification::assertNotSentTo($this->user, 
            ProgramCreatedNotification::class
        );

        Event::assertDispatched(NewProgramEvent::class);
    }

    public function test_cannot_create_program_with_unauthorized_access_to_items()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $course = Course::factory()->create();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'items' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $course->id
                ]
            ]),
            'type' => 'price',
            'paymentData' => json_encode([
                (object) [
                    'amount' => '400',
                ]
            ]),
            'paymentData' => json_encode([
                (object) [
                    'amount' => '400',
                ]
            ]),
            'facilitate' => json_encode(true),
            'owner' => $school->accountType,
            'ownerId' => $school->id,
            'sections' => json_encode([
                (object) [
                    'name' => $this->faker->sentence,
                    'description' => $this->faker->sentence,
                ]
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
            'description' => $this->faker->sentence,
        ];

        $response = $this->postJson("/api/course/main", $data);

        $response
            ->dump()
            ->assertStatus(422);
    }

    public function test_can_update_program()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $program = Program::factory()->create();
        $courses = Course::factory()->count(2)->create();
        $courses->first()->programs()->attach($program);
        $courses->first()->save();
        $program->ownedby()->associate($school);
        $program->save();

        $price = Price::factory()->state([
            'priceable_type' => $program::class,
            'priceable_id' => $program->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedPrograms()->save($program);
        $school->ownedCourses()->saveMany($courses);
        $account->programs()->attach($program);
        $account->courses()->attach($courses);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'programId' => $program->id,
            'name' => 'edited',
            'description' => $this->faker->sentence,
            'items' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $courses->last()->id
                ]
            ]),
            'removedItems' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $courses->first()->id
                ]
            ]),
            'facilitate' => json_encode(false),
            'sections' => json_encode([
                (object) [
                    'name' => $this->faker->sentence,
                    'description' => $this->faker->sentence,
                ]
            ]),
            'type' => 'price',
            'paymentData' => json_encode([
                (object) [
                    'amount' => '400',
                ]
            ]),
            'removedPaymentData' => json_encode([
                (object) [
                    'type' => 'price',
                    'type' => $price->id,
                ]
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

        $response = $this->putJson("/api/program/{$program->id}/main", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('edited', $response['program']['name']);
        $this->assertEquals(1, $response['program']['discussions']);
        
        Notification::assertNotSentTo($this->user, 
            ProgramUpdatedNotification::class
        );

        Event::assertDispatched(UpdateProgramEvent::class);
    }

    public function test_can_delete_program()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $program = Program::factory()->create();
        $classes = ClassModel::factory()->count(2)->create();
        $program->ownedby()->associate($school);
        $program->save();

        $price = Price::factory()->state([
            'priceable_type' => $program::class,
            'priceable_id' => $program->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedPrograms()->save($program);
        $school->ownedClasses()->saveMany($classes);
        $account->programs()->attach($program);
        $account->classes()->attach($classes);
        $account->save();

        $data = [
            'programId' => $program->id,
        ];

        $response = $this->deleteJson("/api/program/{$program->id}/main", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals(0, $school->ownedPrograms->count());
        
        Notification::assertNotSentTo($this->user, 
            ProgramDeletedNotification::class
        );

        Event::assertDispatched(DeleteProgramEvent::class);
    }

    public function test_can_delete_program_by_changing_state_by_paymentMadeFor()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $program = Program::factory()->create();
        $courses = Course::factory()->count(2)->create();
        $program->ownedby()->associate($school);
        $program->save();

        $price = Price::factory()->state([
            'priceable_type' => $program::class,
            'priceable_id' => $program->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedPrograms()->save($program);
        $school->ownedCourses()->saveMany($courses);
        $account->programs()->attach($program);
        $account->courses()->attach($courses);
        $account->save();
        
        $payment = Payment::factory()->create();
        $program->payments()->save($payment);
        $program->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'programId' => $program->id,
        ];

        $response = $this->deleteJson("/api/program/{$program->id}/main", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('DELETED', $response['program']['state']);
        $this->assertEquals(1, $school->ownedPrograms->count());
        
        // Notification::assertNotSentTo($this->user, 
        //     ClassDeletedNotification::class
        // );

        Event::assertDispatched(UpdateProgramEvent::class);
    }

    public function test_can_delete_program_by_changing_state_by_usedByAnother()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $program = Program::factory()->create();
        $courses = Course::factory()->count(2)->create();
        $courses->first()->programs()->attach($program, ['resource'=> true]);
        $courses->first()->save();
        $program->ownedby()->associate($school);
        $program->save();

        $price = Price::factory()->state([
            'priceable_type' => $program::class,
            'priceable_id' => $program->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedPrograms()->save($program);
        $school->ownedcourses()->saveMany($courses);
        $account->programs()->attach($program);
        $account->courses()->attach($courses);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'programId' => $program->id,
        ];

        $response = $this->deleteJson("/api/program/{$program->id}/main", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('DELETED', $response['program']['state']);
        $this->assertEquals(1, $school->ownedPrograms->count());
        
        // Notification::assertNotSentTo($this->user, 
        //     ClassDeletedNotification::class
        // );

        Event::assertDispatched(UpdateProgramEvent::class);
    }

    public function test_can_undo_delete_program_by_changing_state()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $program = Program::factory()->create();
        $courses = Course::factory()->count(2)->create();
        $program->ownedby()->associate($school);
        $program->save();

        $price = Price::factory()->state([
            'priceable_type' => $program::class,
            'priceable_id' => $program->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedPrograms()->save($program);
        $school->ownedCourses()->saveMany($courses);
        $account->programs()->attach($program);
        $account->courses()->attach($courses);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'programId' => $program->id,
            'action' => 'undo'
        ];

        $response = $this->deleteJson("/api/program/{$program->id}/main", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('ACCEPTED', $response['program']['state']);
        $this->assertEquals(1, $school->ownedPrograms->count());
        
        // Notification::assertNotSentTo($this->user, 
        //     ClassDeletedNotification::class
        // );

        Event::assertDispatched(UpdateProgramEvent::class);
    }
}
