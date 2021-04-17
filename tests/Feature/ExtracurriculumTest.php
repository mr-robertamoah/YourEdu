<?php

namespace Tests\Feature;

use App\Events\DeleteExtracurriculumEvent;
use App\Events\NewExtracurriculumEvent;
use App\Events\UpdateExtracurriculumEvent;
use App\Notifications\ExtracurriculumCreatedNotification;
use App\Notifications\ExtracurriculumDeletedNotification;
use App\Notifications\ExtracurriculumUpdatedNotification;
use App\User;
use App\YourEdu\ClassModel;
use App\YourEdu\Course;
use App\YourEdu\Extracurriculum;
use App\YourEdu\Facilitator;
use App\YourEdu\Payment;
use App\YourEdu\Price;
use App\YourEdu\Program;
use App\YourEdu\School;
use App\YourEdu\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ExtracurriculumTest extends TestCase
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
        DB::table('extracurriculums')->delete();
        DB::table('programs')->delete();
    }

    public function test_can_create_extracurriculum()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
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
        $class = ClassModel::factory()->create();
        $account->classes()->attach($class);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'attachments' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $course->id
                ]
            ]),
            'items' => json_encode([
                (object) [
                    'type' => 'class',
                    'id' => $class->id
                ]
            ]),
            'paymentType' => 'subscription',
            'paymentData' => json_encode([
                (object) [
                    'amount' => '400',
                    'name' => 'name of subscription'
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

        $response = $this->postJson("/api/extracurriculum", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals(1, $response['extracurriculum']['discussions']);
        $this->assertEquals(1, count($response['extracurriculum']['subscriptions']));

        Notification::assertNotSentTo($this->user, 
            ExtracurriculumCreatedNotification::class
        );

        Event::assertDispatched(NewExtracurriculumEvent::class);
    }

    public function test_cannot_create_extracurriculum_with_unauthorized_access_to_items()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $class = ClassModel::factory()->create();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'items' => json_encode([
                (object) [
                    'type' => 'class',
                    'id' => $class->id
                ]
            ]),
            'paymentType' => 'price',
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

        $response = $this->postJson("/api/extracurriculum", $data);

        $response
            ->dump()
            ->assertStatus(422);
    }

    public function test_can_update_extracurriculum()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $extracurriculum = Extracurriculum::factory()->create();
        $classes = ClassModel::factory()->count(2)->create();
        $classes->first()->courses()->attach($extracurriculum);
        $classes->first()->save();
        $extracurriculum->ownedby()->associate($school);
        $extracurriculum->save();

        $subscription = Subscription::factory()->state([
            'subscribable_type' => $extracurriculum::class,
            'subscribable_id' => $extracurriculum->id,
        ])->create();
        $subscription->ownedby()->associate($school);

        $school->ownedExtracurriculums()->save($extracurriculum);
        $school->ownedClasses()->saveMany($classes);
        $account->extracurriculums()->attach($extracurriculum);
        $account->classes()->attach($classes);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'extracurriculumId' => $extracurriculum->id,
            'name' => 'edited',
            'description' => $this->faker->sentence,
            'items' => json_encode([
                (object) [
                    'type' => 'class',
                    'id' => $classes->last()->id
                ]
            ]),
            'removedItems' => json_encode([
                (object) [
                    'type' => 'class',
                    'id' => $classes->first()->id
                ]
            ]),
            'facilitate' => json_encode(false),
            'sections' => json_encode([
                (object) [
                    'name' => $this->faker->sentence,
                    'description' => $this->faker->sentence,
                ]
            ]),
            'paymentType' => 'price',
            'paymentData' => json_encode([
                (object) [
                    'amount' => '400',
                ]
            ]),
            'removedPaymentData' => json_encode([
                (object) [
                    'type' => 'subscription',
                    'id' => $subscription->id,
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

        $response = $this->putJson("/api/extracurriculum/{$extracurriculum->id}", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('edited', $response['extracurriculum']['name']);
        $this->assertEquals(1, $response['extracurriculum']['discussions']);
        $this->assertEquals(1, count($response['extracurriculum']['prices']));
        $this->assertEquals(0, count($response['extracurriculum']['subscriptions']));
        
        Notification::assertNotSentTo($this->user, 
            ExtracurriculumUpdatedNotification::class
        );

        Event::assertDispatched(UpdateExtracurriculumEvent::class);
    }

    public function test_can_delete_extracurriculum()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $extracurriculum = Extracurriculum::factory()->create();
        $classes = ClassModel::factory()->count(2)->create();
        $extracurriculum->ownedby()->associate($school);
        $extracurriculum->save();

        $price = Price::factory()->state([
            'priceable_type' => $extracurriculum::class,
            'priceable_id' => $extracurriculum->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedExtracurriculums()->save($extracurriculum);
        $school->ownedClasses()->saveMany($classes);
        $account->extracurriculums()->attach($extracurriculum);
        $account->classes()->attach($classes);
        $account->save();

        $data = [
            'extracurriculumId' => $extracurriculum->id,
        ];

        $response = $this->deleteJson("/api/extracurriculum/{$extracurriculum->id}", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals(0, $school->ownedExtracurriculums->count());
        
        Notification::assertNotSentTo($this->user, 
            ExtracurriculumDeletedNotification::class
        );

        Event::assertDispatched(DeleteExtracurriculumEvent::class);
    }

    public function test_can_delete_extracurriculum_by_changing_state_by_paymentMadeFor()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $extracurriculum = Extracurriculum::factory()->create();
        $classes = ClassModel::factory()->count(2)->create();
        $extracurriculum->ownedby()->associate($school);
        $extracurriculum->save();

        $price = Price::factory()->state([
            'priceable_type' => $extracurriculum::class,
            'priceable_id' => $extracurriculum->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedExtracurriculums()->save($extracurriculum);
        $school->ownedClasses()->saveMany($classes);
        $account->extracurriculums()->attach($extracurriculum);
        $account->classes()->attach($classes);
        $account->save();
        
        $payment = Payment::factory()->create();
        $extracurriculum->payments()->save($payment);
        $extracurriculum->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'extracurriculumId' => $extracurriculum->id,
        ];

        $response = $this->deleteJson("/api/extracurriculum/{$extracurriculum->id}", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('DELETED', $response['extracurriculum']['state']);
        $this->assertEquals(1, $school->ownedExtracurriculums->count());
        
        // Notification::assertNotSentTo($this->user, 
        //     ClassDeletedNotification::class
        // );

        Event::assertDispatched(UpdateExtracurriculumEvent::class);
    }

    public function test_can_delete_extracurriculum_by_changing_state_by_usedByAnother()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $extracurriculum = Extracurriculum::factory()->create();
        $classes = ClassModel::factory()->count(2)->create();
        $classes->first()->extracurriculums()->attach($extracurriculum);
        $classes->first()->save();
        $extracurriculum->ownedby()->associate($school);
        $extracurriculum->save();

        $school->ownedExtracurriculums()->save($extracurriculum);
        $school->ownedClasses()->saveMany($classes);
        $account->extracurriculums()->attach($extracurriculum);
        $account->classes()->attach($classes);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'extracurriculumId' => $extracurriculum->id,
        ];

        $response = $this->deleteJson("/api/extracurriculum/{$extracurriculum->id}", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('DELETED', $response['extracurriculum']['state']);
        $this->assertEquals(1, $school->ownedExtracurriculums->count());
        
        // Notification::assertNotSentTo($this->user, 
        //     ClassDeletedNotification::class
        // );

        Event::assertDispatched(UpdateExtracurriculumEvent::class);
    }

    public function test_can_undo_delete_extracurriculum_by_changing_state()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $extracurriculum = Extracurriculum::factory()->create();
        $programs = Program::factory()->count(2)->create();
        $extracurriculum->ownedby()->associate($school);
        $extracurriculum->save();

        $price = Price::factory()->state([
            'priceable_type' => $extracurriculum::class,
            'priceable_id' => $extracurriculum->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedExtracurriculums()->save($extracurriculum);
        $school->ownedPrograms()->saveMany($programs);
        $account->extracurriculums()->attach($extracurriculum);
        $account->programs()->attach($programs);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'extracurriculumId' => $extracurriculum->id,
            'action' => 'undo'
        ];

        $response = $this->deleteJson("/api/extracurriculum/{$extracurriculum->id}", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('ACCEPTED', $response['extracurriculum']['state']);
        $this->assertEquals(1, $school->ownedExtracurriculums->count());
        
        // Notification::assertNotSentTo($this->user, 
        //     ClassDeletedNotification::class
        // );

        Event::assertDispatched(UpdateExtracurriculumEvent::class);
    }
}
