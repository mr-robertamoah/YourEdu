<?php

namespace Tests\Feature;

use App\Events\DeleteClassEvent;
use App\Events\NewClassEvent;
use App\Events\UpdateClassEvent;
use App\Notifications\ClassCreatedNotification;
use App\Notifications\ClassUpdatedNotification;
use App\User;
use App\YourEdu\AcademicYear;
use App\YourEdu\ClassModel;
use App\YourEdu\Course;
use App\YourEdu\Facilitator;
use App\YourEdu\Fee;
use App\YourEdu\Payment;
use App\YourEdu\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ClassTest extends TestCase
{
    use WithFaker;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'api');

        DB::table('facilitators')->delete();
        DB::table('class_models')->delete();
        DB::table('schools')->delete();
        DB::table('images')->delete();
        DB::table('courses')->delete();
        DB::table('fees')->delete();
        DB::table('academic_years')->delete();
    }

    public function test_can_create_class()
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

        $academicYear = AcademicYear::factory()
            ->state(['school_id' => $school->id])->create();
        
        $course = Course::factory()->create();
        $school->ownedCourses()->save($course);
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
            'academicYears' => json_encode([
                (object) [
                    'type' => 'academicYear',
                    'id' => $academicYear->id
                ]
            ]),
            'type' => 'fee',
            'paymentData' => json_encode([
                (object) [
                    'amount' => '400',
                ]
            ]),
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

        $response = $this->postJson("/api/class", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals(1, $response['class']['discussions']);

        Notification::assertNotSentTo($this->user, 
            ClassCreatedNotification::class
        );

        Event::assertDispatched(NewClassEvent::class);
    }

    public function test_can_update_class()
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

        $academicYears = AcademicYear::factory()->count(2)
            ->state(['school_id' => $school->id])->create();
        
        $class = ClassModel::factory()->create();
        $courses = Course::factory()->count(2)->create();
        $class->academicYears()->save($academicYears->first());
        $class->courses()->attach($courses);
        $class->facilitators()->attach($account);
        $class->ownedby()->associate($school);
        $class->save();

        $fee = Fee::factory()->state([
            'class_id' => $class->id
        ])->create();
        $fee->feeable()->associate($academicYears);
        $fee->addedby()->associate($school);

        $course = Course::factory()->create();
        $school->ownedCourses()->save($course);
        $school->ownedCourses()->saveMany($courses);
        $account->courses()->attach($course);
        $account->courses()->attach($courses);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'classId' => $class->id,
            'name' => 'edited',
            'description' => $this->faker->sentence,
            'items' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $course->id
                ]
            ]),
            'removedItems' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $courses->first()->id
                ]
            ]),
            'academicYears' => json_encode([
                (object) [
                    'type' => 'academicYear',
                    'id' => $academicYears->last()->id
                ]
            ]),
            'removedAcademicYears' => json_encode([
                (object) [
                    'type' => 'academicYear',
                    'id' => $academicYears->first()->id
                ]
            ]),
            'type' => 'fee',
            'paymentData' => json_encode([
                (object) [
                    'amount' => '400',
                ]
            ]),
            'removedPaymentData' => json_encode([
                (object) [
                    'type' => 'fee',
                    'type' => $fee->id,
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

        $response = $this->putJson("/api/class/{$class->id}", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('edited', $response['class']['name']);
        $this->assertEquals(1, $response['class']['discussions']);
        
        Notification::assertNotSentTo($this->user, 
            ClassUpdatedNotification::class
        );

        Event::assertDispatched(UpdateClassEvent::class);
    }

    public function test_can_delete_class()
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

        $academicYears = AcademicYear::factory()->count(2)
            ->state(['school_id' => $school->id])->create();
        
        $class = ClassModel::factory()->create();
        $courses = Course::factory()->count(2)->create();
        $class->academicYears()->save($academicYears->first());
        $class->courses()->attach($courses);
        $class->facilitators()->attach($account);
        $class->ownedby()->associate($school);
        $class->save();

        $fee = Fee::factory()->state([
            'class_id' => $class->id
        ])->create();
        $fee->feeable()->associate($academicYears);
        $fee->addedby()->associate($school);

        $course = Course::factory()->create();
        $school->ownedCourses()->save($course);
        $school->ownedCourses()->saveMany($courses);
        $account->courses()->attach($course);
        $account->courses()->attach($courses);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'classId' => $class->id,
        ];

        $response = $this->deleteJson("/api/class/{$class->id}", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals(0, $school->ownedClasses->count());
        
        Notification::assertNotSentTo($this->user, 
            ClassDeletedNotification::class
        );

        Event::assertDispatched(DeleteClassEvent::class);
    }

    public function test_can_delete_class_by_changing_state_by_paymentMadeFor()
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

        $academicYears = AcademicYear::factory()->count(2)
            ->state(['school_id' => $school->id])->create();
        
        $class = ClassModel::factory()->create();
        $courses = Course::factory()->count(2)->create();
        $class->academicYears()->save($academicYears->first());
        $class->courses()->attach($courses);
        $class->facilitators()->attach($account);
        $class->ownedby()->associate($school);
        $payment = Payment::factory()->create();
        $class->payments()->save($payment);
        $class->save();

        $fee = Fee::factory()->state([
            'class_id' => $class->id
        ])->create();
        $fee->feeable()->associate($academicYears);
        $fee->addedby()->associate($school);

        $course = Course::factory()->create();
        $school->ownedCourses()->save($course);
        $school->ownedCourses()->saveMany($courses);
        $account->courses()->attach($course);
        $account->courses()->attach($courses);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'classId' => $class->id,
        ];

        $response = $this->deleteJson("/api/class/{$class->id}", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('DELETED', $response['class']['state']);
        $this->assertEquals(1, $school->ownedClasses->count());
        
        // Notification::assertNotSentTo($this->user, 
        //     ClassDeletedNotification::class
        // );

        Event::assertDispatched(UpdateClassEvent::class);
    }

    public function test_can_delete_class_by_changing_state_by_usedByAnother()
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
        $school1 = School::factory()
            ->state([
                'company_name' => $this->faker->name,
                'class_structure' => 'courses',
            ])
            ->for(User::factory(), 'owner')
            ->create();

        $academicYears = AcademicYear::factory()->count(2)
            ->state(['school_id' => $school->id])->create();
        
        $class = ClassModel::factory()->create();
        $courses = Course::factory()->count(2)->create();
        $class->academicYears()->save($academicYears->first());
        $class->courses()->attach($courses);
        $class->facilitators()->attach($account);
        $class->ownedby()->associate($school);
        $payment = Payment::factory()->create();
        $class->payments()->save($payment);
        $class->save();
        $school1->classes()->attach($class, ['resource' => true]);

        $fee = Fee::factory()->state([
            'class_id' => $class->id
        ])->create();
        $fee->feeable()->associate($academicYears);
        $fee->addedby()->associate($school);

        $course = Course::factory()->create();
        $school->ownedCourses()->save($course);
        $school->ownedCourses()->saveMany($courses);
        $account->courses()->attach($course);
        $account->courses()->attach($courses);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'classId' => $class->id,
        ];

        $response = $this->deleteJson("/api/class/{$class->id}", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('DELETED', $response['class']['state']);
        $this->assertEquals(1, $school->ownedClasses->count());
        
        // Notification::assertNotSentTo($this->user, 
        //     ClassDeletedNotification::class
        // );

        Event::assertDispatched(UpdateClassEvent::class);
    }

    public function test_can_undo_delete_class_by_changing_state()
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

        $academicYears = AcademicYear::factory()->count(2)
            ->state(['school_id' => $school->id])->create();
        
        $class = ClassModel::factory()->create();
        $courses = Course::factory()->count(2)->create();
        $class->academicYears()->save($academicYears->first());
        $class->courses()->attach($courses);
        $class->facilitators()->attach($account);
        $class->ownedby()->associate($school);
        $payment = Payment::factory()->create();
        $class->payments()->save($payment);
        $class->save();

        $fee = Fee::factory()->state([
            'class_id' => $class->id
        ])->create();
        $fee->feeable()->associate($academicYears);
        $fee->addedby()->associate($school);

        $course = Course::factory()->create();
        $school->ownedCourses()->save($course);
        $school->ownedCourses()->saveMany($courses);
        $account->courses()->attach($course);
        $account->courses()->attach($courses);
        $account->save();

        $data = [
            'account' => $account->accountType,
            'accountId' => $account->id,
            'classId' => $class->id,
            'action' => 'undo'
        ];

        $response = $this->deleteJson("/api/class/update", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals('ACCEPTED', $response['class']['state']);
        $this->assertEquals(1, $school->ownedClasses->count());
        
        // Notification::assertNotSentTo($this->user, 
        //     ClassDeletedNotification::class
        // );

        Event::assertDispatched(UpdateClassEvent::class);
    }
}
