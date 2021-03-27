<?php

namespace Tests\Feature;

use App\Events\DeleteCourseEvent;
use App\Events\NewCourseEvent;
use App\Events\UpdateCourseEvent;
use App\Notifications\CourseCreatedNotification;
use App\Notifications\CourseUpdatedNotification;
use App\User;
use App\YourEdu\ClassModel;
use App\YourEdu\Course;
use App\YourEdu\Facilitator;
use App\YourEdu\Payment;
use App\YourEdu\Price;
use App\YourEdu\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CourseTest extends TestCase
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
        DB::table('academic_years')->delete();
    }

    public function test_can_create_course()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state(['name' => $this->faker->name])
            ->for($this->user)
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $course = Course::factory()->create();
        $account->courses()->attach($course);
        $account->save();

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
            ->assertSuccessful();

        $this->assertEquals(1, $response['course']['discussions']);

        Notification::assertNotSentTo($this->user, 
            CourseCreatedNotification::class
        );

        Event::assertDispatched(NewCourseEvent::class);
    }

    public function test_cannot_create_course_with_unauthorized_access_to_items()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state(['name' => $this->faker->name])
            ->for($this->user)
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

    public function test_can_update_course()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state(['name' => $this->faker->name])
            ->for($this->user)
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $course = Course::factory()->create();
        $classes = ClassModel::factory()->count(2)->create();
        $classes->first()->courses()->attach($course);
        $classes->first()->save();
        $course->ownedby()->associate($school);
        $course->save();

        $price = Price::factory()->state([
            'priceable_type' => $course::class,
            'priceable_id' => $course->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedCourses()->save($course);
        $school->ownedClasses()->saveMany($classes);
        $account->courses()->attach($course);
        $account->classes()->attach($classes);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'courseId' => $course->id,
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

        $response = $this->putJson("/api/course/{$course->id}/main", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('edited', $response['course']['name']);
        $this->assertEquals(1, $response['course']['discussions']);
        
        Notification::assertNotSentTo($this->user, 
            CourseUpdatedNotification::class
        );

        Event::assertDispatched(UpdateCourseEvent::class);
    }

    public function test_can_delete_course()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state(['name' => $this->faker->name])
            ->for($this->user)
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $course = Course::factory()->create();
        $classes = ClassModel::factory()->count(2)->create();
        // $classes->first()->courses()->attach($course);
        // $classes->first()->save();
        $course->ownedby()->associate($school);
        $course->save();

        $price = Price::factory()->state([
            'priceable_type' => $course::class,
            'priceable_id' => $course->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedCourses()->save($course);
        $school->ownedClasses()->saveMany($classes);
        $account->courses()->attach($course);
        $account->classes()->attach($classes);
        $account->save();

        $data = [
            'courseId' => $course->id,
        ];

        $response = $this->deleteJson("/api/course/{$course->id}/main", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals(0, $school->ownedCourses->count());
        
        Notification::assertNotSentTo($this->user, 
            CourseDeletedNotification::class
        );

        Event::assertDispatched(DeleteCourseEvent::class);
    }

    public function test_can_delete_course_by_changing_state_by_paymentMadeFor()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state(['name' => $this->faker->name])
            ->for($this->user)
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $course = Course::factory()->create();
        $classes = ClassModel::factory()->count(2)->create();
        $course->ownedby()->associate($school);
        $course->save();

        $price = Price::factory()->state([
            'priceable_type' => $course::class,
            'priceable_id' => $course->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedCourses()->save($course);
        $school->ownedClasses()->saveMany($classes);
        $account->courses()->attach($course);
        $account->classes()->attach($classes);
        $account->save();
        
        $payment = Payment::factory()->create();
        $course->payments()->save($payment);
        $course->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'courseId' => $course->id,
        ];

        $response = $this->deleteJson("/api/course/{$course->id}/main", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('DELETED', $response['course']['state']);
        $this->assertEquals(1, $school->ownedCourses->count());
        
        // Notification::assertNotSentTo($this->user, 
        //     ClassDeletedNotification::class
        // );

        Event::assertDispatched(UpdateCourseEvent::class);
    }

    public function test_can_delete_course_by_changing_state_by_usedByAnother()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state(['name' => $this->faker->name])
            ->for($this->user)
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $course = Course::factory()->create();
        $classes = ClassModel::factory()->count(2)->create();
        $classes->first()->courses()->attach($course);
        $classes->first()->save();
        $course->ownedby()->associate($school);
        $course->save();

        $price = Price::factory()->state([
            'priceable_type' => $course::class,
            'priceable_id' => $course->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedCourses()->save($course);
        $school->ownedClasses()->saveMany($classes);
        $account->courses()->attach($course);
        $account->classes()->attach($classes);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'courseId' => $course->id,
        ];

        $response = $this->deleteJson("/api/course/{$course->id}/main", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('DELETED', $response['course']['state']);
        $this->assertEquals(1, $school->ownedCourses->count());
        
        // Notification::assertNotSentTo($this->user, 
        //     ClassDeletedNotification::class
        // );

        Event::assertDispatched(UpdateCourseEvent::class);
    }

    public function test_can_undo_delete_course_by_changing_state()
    {
        Notification::fake();
        Event::fake();
        $account = Facilitator::factory()
            ->state(['name' => $this->faker->name])
            ->for($this->user)
            ->create();

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();
        
        $course = Course::factory()->create();
        $classes = ClassModel::factory()->count(2)->create();
        // $classes->first()->courses()->attach($course);
        // $classes->first()->save();
        $course->ownedby()->associate($school);
        $course->save();

        $price = Price::factory()->state([
            'priceable_type' => $course::class,
            'priceable_id' => $course->id,
        ])->create();
        $price->ownedby()->associate($school);

        $school->ownedCourses()->save($course);
        $school->ownedClasses()->saveMany($classes);
        $account->courses()->attach($course);
        $account->classes()->attach($classes);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'courseId' => $course->id,
            'action' => 'undo'
        ];

        $response = $this->deleteJson("/api/course/{$course->id}/main", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('ACCEPTED', $response['course']['state']);
        $this->assertEquals(1, $school->ownedCourses->count());
        
        // Notification::assertNotSentTo($this->user, 
        //     ClassDeletedNotification::class
        // );

        Event::assertDispatched(UpdateCourseEvent::class);
    }
}
