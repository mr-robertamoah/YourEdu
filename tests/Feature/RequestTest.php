<?php

namespace Tests\Feature;

use App\DTOs\DiscountDTO;
use App\DTOs\RequestDTO;
use App\DTOs\SalaryDTO;
use App\Events\DeleteRequestMessage;
use App\Events\NewRequestMessage;
use App\Notifications\AccountRequestNotification;
use App\Notifications\AccountResponseNotification;
use App\Notifications\RequestMessageNotification;
use App\Services\DiscountService;
use App\Services\SalaryService;
use App\User;
use App\YourEdu\Admin;
use App\YourEdu\ClassModel;
use App\YourEdu\Collaboration;
use App\YourEdu\Commission;
use App\YourEdu\Course;
use App\YourEdu\Discount;
use App\YourEdu\Facilitator;
use App\YourEdu\Grade;
use App\YourEdu\Image;
use App\YourEdu\Learner;
use App\YourEdu\Message;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\Request;
use App\YourEdu\Salariable;
use App\YourEdu\Salary;
use App\YourEdu\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RequestTest extends TestCase
{
    use WithFaker;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'api');
        
        // DB::table('users')->delete();
        DB::table('facilitators')->delete();
        DB::table('learners')->delete();
        // DB::table('salariables')->delete();
        // DB::table('salaries')->delete();
        DB::table('parent_models')->delete();
        DB::table('schools')->delete();
        DB::table('grades')->delete();
        DB::table('files')->delete();
        DB::table('courses')->delete();
        DB::table('extracurriculums')->delete();
        DB::table('class_models')->delete();
    }

    public function test_can_search_accounts()
    {
        
        Facilitator::factory()
            ->count(2)
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();
        
        $data = [
            'type' => 'facilitators',
            'search' => '',
            'others' => json_encode(true),
        ];

        $response = $this->json('GET', '/api/request/accounts/search', $data);

        $response
            ->dump()
            ->assertStatus(200);
    }

    public function test_can_search_items()
    {
        
        $account = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();

        $items = ClassModel::factory()->count(3)->create();
        
        // $items = Course::factory()->state([
        //     'stand_alone' => 1
        // ])->count(3)->create();

        $first = $items->first();

        // $account->ownedCourses()->save($first);
        
        $data = [
            'type' => 'classes',
            'search' => '',
            'others' => json_encode(true),
        ];

        $response = $this->json('GET', '/api/request/items/search', $data);

        $response
            ->dump()
            ->assertStatus(200);

        $this->assertEquals(
            ClassModel::count() - $account->ownedClasses()->count(), 
            count($response['data']));
    }

    public function test_can_send_learning_account_request_as_learner()
    {
        Notification::fake();
        $learner = Learner::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();
        $facilitator = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        
        $courses = Course::factory()
            ->state([
                'ownedby_type' => $facilitator::class,
                'ownedby_id' => $facilitator->id,
            ])->count(2)->create();
        
        $classes = ClassModel::factory()
            ->state([
                'ownedby_type' => $facilitator::class,
                'ownedby_id' => $facilitator->id,
            ])->count(2)->create();

        $data = [
            'account' => $learner->accountType,
            'accountId' => $learner->id,
            'action' => 'learning',
            'items' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $courses->first()->id,
                ],
                (object) [
                    'type' => 'class',
                    'id' => $classes->first()->id,
                ],
            ]),
            'discountData' => json_encode((object) [
                'name' => 'name of discount',
                'percentage' => 0.2
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();

        Notification::assertSentTo(
            $facilitator->user,
            AccountRequestNotification::class
        );

        $this->assertTrue(
            Discount::where('addedby_type', $learner::class)
                ->where('addedby_id', $learner->id)->exists()
        );
    }

    public function test_can_send_learning_account_request_as_parent_for_learner()
    {
        Notification::fake();
        $learner = Learner::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $facilitator = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $parent = ParentModel::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();
        $parent->wards()->attach($learner);
        $parent->save();

        $courses = Course::factory()
            ->state([
                'ownedby_type' => $facilitator::class,
                'ownedby_id' => $facilitator->id,
            ])->count(2)->create();
        
        $classes = ClassModel::factory()
            ->state([
                'ownedby_type' => $facilitator::class,
                'ownedby_id' => $facilitator->id,
            ])->count(2)->create();

        $data = [
            'account' => $parent->accountType,
            'accountId' => $parent->id,
            'action' => 'learning',
            'items' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $courses->first()->id,
                ],
                (object) [
                    'type' => 'class',
                    'id' => $classes->first()->id,
                ],
            ]),
            'wardId' => $learner->id,
            'discountData' => json_encode((object) [
                'name' => 'name of discount',
                'percentage' => 0.2
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals(
            2, 
            Request::where('requestfrom_type', $learner::class)
                ->where('requestfrom_id', $learner->id)->count()
        );

        Notification::assertSentTo(
            $facilitator->user,
            AccountRequestNotification::class
        );
    }

    public function test_can_send_learning_account_request_as_facilitator()
    {
        Notification::fake();
        $learner = Learner::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $facilitator = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();
        $parent = ParentModel::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $parent->wards()->attach($learner);
        $parent->save();
        
        $courses = Course::factory()
            ->state([
                'ownedby_type' => $facilitator::class,
                'ownedby_id' => $facilitator->id,
            ])->count(2)->create();
        
        $classes = ClassModel::factory()
            ->state([
                'ownedby_type' => $facilitator::class,
                'ownedby_id' => $facilitator->id,
            ])->count(2)->create();

        $data = [
            'account' => $facilitator->accountType,
            'accountId' => $facilitator->id,
            'action' => 'learning',
            'items' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $courses->first()->id,
                ],
                (object) [
                    'type' => 'class',
                    'id' => $classes->first()->id,
                ],
            ]),
            'receivers' => json_encode([
                (object) [
                    'account' => 'learner',
                    'accountId' => $learner->id,
                ],
            ]),
            'discountData' => json_encode((object) [
                'name' => 'name of discount',
                'percentage' => 0.2
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();

        Notification::assertSentTo(
            $learner->user,
            AccountRequestNotification::class
        );

        Notification::assertSentTo(
            $parent->user,
            AccountRequestNotification::class
        );
    }

    public function test_can_send_learning_account_request_as_school()
    {
        Notification::fake();
        $learner = Learner::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $school->admins()->attach($admin);
        $school->save();
        $parent = ParentModel::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $parent->wards()->attach($learner);
        $parent->save();
        
        $courses = Course::factory()
            ->state([
                'ownedby_type' => $school::class,
                'ownedby_id' => $school->id,
            ])->count(2)->create();
        
        $classes = ClassModel::factory()
            ->state([
                'ownedby_type' => $school::class,
                'ownedby_id' => $school->id,
            ])->count(2)->create();

        $data = [
            'account' => $school->accountType,
            'accountId' => $school->id,
            'action' => 'learning',
            'adminId' => $admin->id,
            'items' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $courses->first()->id,
                ],
                (object) [
                    'type' => 'class',
                    'id' => $classes->first()->id,
                ],
            ]),
            'receivers' => json_encode([
                (object) [
                    'account' => 'learner',
                    'accountId' => $learner->id,
                ],
            ]),
            'discountData' => json_encode((object) [
                'name' => 'name of discount',
                'percentage' => 0.2
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();

        Notification::assertSentTo(
            $learner->user,
            AccountRequestNotification::class
        );

        Notification::assertSentTo(
            $parent->user,
            AccountRequestNotification::class
        );
    }

    public function test_can_send_facilitation_account_request_as_facilitator()
    {
        Notification::fake();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $school->admins()->attach($admin);
        $school->save();
        $facilitator = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();
        
        $courses = Course::factory()
            ->state([
                'ownedby_type' => $school::class,
                'ownedby_id' => $school->id,
            ])->count(2)->create();
        
        $classes = ClassModel::factory()
            ->state([
                'ownedby_type' => $school::class,
                'ownedby_id' => $school->id,
            ])->count(2)->create();

        $data = [
            'account' => $facilitator->accountType,
            'accountId' => $facilitator->id,
            'action' => 'facilitation',
            'items' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $courses->first()->id,
                ],
                (object) [
                    'type' => 'class',
                    'id' => $classes->first()->id,
                ],
            ]),
            'paymentType' => 'salary',
            'paymentData' => json_encode([
                (object) [
                    'name' => 'name of discount',
                    'amount' => 200
                ]
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();

        Notification::assertSentTo(
            $school->owner,
            AccountRequestNotification::class
        );
        Notification::assertSentTo(
            $admin->user,
            AccountRequestNotification::class
        );

        $this->assertEquals(1, 
            Salary::where('addedby_type', $facilitator->first()::class)
                ->where('addedby_id', $facilitator->first()->id)->count()
        );
    }

    public function test_can_send_facilitation_account_request_as_school()
    {
        Notification::fake();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->for(User::factory())
            ->create();
        $school->admins()->attach($admin);
        $school->save();
        $facilitator = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        
        $courses = Course::factory()
            ->state([
                'ownedby_type' => $school::class,
                'ownedby_id' => $school->id,
            ])->count(2)->create();
        
        $classes = ClassModel::factory()
            ->state([
                'ownedby_type' => $school::class,
                'ownedby_id' => $school->id,
            ])->count(2)->create();

        $data = [
            'account' => $school->accountType,
            'accountId' => $school->id,
            'action' => 'facilitation',
            'items' => json_encode([
                (object) [
                    'type' => 'course',
                    'id' => $courses->first()->id,
                ],
                (object) [
                    'type' => 'class',
                    'id' => $classes->first()->id,
                ],
            ]),
            'paymentType' => 'salary',
            'paymentData' => json_encode([
                (object) [
                    'name' => 'name of discount',
                    'amount' => 200
                ]
            ]),
            'receivers' => json_encode([
                (object) [
                    'account' => $facilitator->accountType,
                    'accountId' => $facilitator->id
                ]
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();
            
        Notification::assertSentTo(
            $facilitator->user,
            AccountRequestNotification::class
        );

        $this->assertEquals(1, 
            Salary::where('addedby_type', $school->first()::class)
                ->where('addedby_id', $school->first()->id)->count()
        );
    }

    public function test_can_send_collaboration_account_request_as_facilitator()
    {
        Notification::fake();
        $otherFacilitator = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $facilitator = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();
        $collaborations = Collaboration::factory()->count(2)
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])->create();

        $data = [
            'account' => $facilitator->accountType,
            'accountId' => $facilitator->id,
            'action' => 'collaboration',
            'items' => json_encode([
                (object) [
                    'type' => 'collaboration',
                    'id' => $collaborations->first()->id,
                ],
                (object) [
                    'type' => 'collaboration',
                    'id' => $collaborations->last()->id,
                ],
            ]),
            'paymentType' => 'commission',
            'paymentData' => json_encode([
                (object) [
                    'percentageOwned' => 0.45
                ]
            ]),
            'receivers' => json_encode([
                (object) [
                    'account' => $otherFacilitator->accountType,
                    'accountId' => $otherFacilitator->id,
                ]
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();

        Notification::assertSentTo(
            $otherFacilitator->user,
            AccountRequestNotification::class
        );

        $this->assertEquals(1, 
            Commission::where('addedby_type', $facilitator::class)
                ->where('addedby_id', $facilitator->id)->count()
        );
    }

    public function test_can_send_collaboration_account_request_as_school()
    {
        Notification::fake();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();
        $school->admins()->attach($admin);
        $school->save();
        $facilitator = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $collaboration = Collaboration::factory()
            ->state([
                'addedby_type' => $school::class,
                'addedby_id' => $school->id,
            ])->create();

        $data = [
            'account' => $school->accountType,
            'accountId' => $school->id,
            'action' => 'collaboration',
            'adminId' => $admin->id,
            'items' => json_encode([
                (object) [
                    'type' => 'collaboration',
                    'id' => $collaboration->id,
                ],
            ]),
            'paymentType' => 'commission',
            'paymentData' => json_encode([
                (object) [
                    'percentageOwned' => 0.200
                ]
            ]),
            'receivers' => json_encode([
                (object) [
                    'account' => $facilitator->accountType,
                    'accountId' => $facilitator->id,
                ]
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();

        Notification::assertSentTo(
            $facilitator->user,
            AccountRequestNotification::class
        );

        $this->assertEquals(1, 
            Commission::where('addedby_type', $school::class)
                ->where('addedby_id', $school->id)->count()
        );
        $this->assertEquals(1, 
            Request::where('requestable_type', $collaboration::class)
                ->where('requestable_id', $collaboration->id)->count()
        );
    }

    public function test_can_send_collaboration_account_request_as_professional()
    {
        Notification::fake();
        $facilitator = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $professional = Professional::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();
        $collaborations = Collaboration::factory()->count(2)
            ->state([
                'addedby_type' => $professional::class,
                'addedby_id' => $professional->id,
            ])->create();

        $data = [
            'account' => $professional->accountType,
            'accountId' => $professional->id,
            'action' => 'collaboration',
            'items' => json_encode([
                (object) [
                    'type' => 'collaboration',
                    'id' => $collaborations->first()->id,
                ],
                (object) [
                    'type' => 'collaboration',
                    'id' => $collaborations->last()->id,
                ],
            ]),
            'paymentType' => 'commission',
            'paymentData' => json_encode([
                (object) [
                    'percentageOwned' => 0.45
                ]
            ]),
            'receivers' => json_encode([
                (object) [
                    'account' => $facilitator->accountType,
                    'accountId' => $facilitator->id,
                ]
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();

        Notification::assertSentTo(
            $facilitator->user,
            AccountRequestNotification::class
        );

        $this->assertEquals(1, 
            Commission::where('addedby_type', $professional::class)
                ->where('addedby_id', $professional->id)->count()
        );
        $this->assertEquals(1, 
            Request::where('requestable_type', $collaborations->first()::class)
                ->where('requestable_id', $collaborations->first()->id)->count()
        );
    }
    
    public function test_can_send_collaboration_account_request_as_professional_with_error()
    {
        Notification::fake();
        $facilitator = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $professional = Professional::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id,
            ])
            ->create();
        $collaborations = Collaboration::factory()->count(2)
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])->create();

        $data = [
            'account' => $professional->accountType,
            'accountId' => $professional->id,
            'action' => 'collaboration',
            'items' => json_encode([
                (object) [
                    'type' => 'collaboration',
                    'id' => $collaborations->first()->id,
                ],
                (object) [
                    'type' => 'collaboration',
                    'id' => $collaborations->last()->id,
                ],
            ]),
            'paymentType' => 'commission',
            'paymentData' => json_encode([
                (object) [
                    'percentageOwned' => 0.45
                ]
            ]),
            'receivers' => json_encode([
                (object) [
                    'account' => $facilitator->accountType,
                    'accountId' => $facilitator->id,
                ]
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "you are not authorized to use this collaboration with id {$collaborations->first()->id}"
            ]);
    }

    public function test_can_send_admission_account_request_as_school()
    {
        Notification::fake();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();
        $grade = Grade::factory()->state([
                'addedby_type' => $school::class,
                'addedby_id' => $school->id,
            ])->create();
        $school->admins()->attach($admin);
        $school->grades()->attach($grade);
        $school->save();
        $learner = Learner::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $parent = ParentModel::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $parent->wards()->attach($learner);
        $parent->save();

        $data = [
            'account' => $school->accountType,
            'accountId' => $school->id,
            'action' => 'admission',
            'adminId' => $admin->id,
            'paymentType' => 'fee',
            'paymentData' => json_encode([
                (object) [
                    'amount' => 200
                ]
            ]),
            'admissionData' => json_encode([
                (object) [
                    'gradeId' => $grade->id,
                    'type' => 'virtual'
                ]
            ]),
            'receivers' => json_encode([
                (object) [
                    'account' => $learner->accountType,
                    'accountId' => $learner->id,
                ]
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();

        Notification::assertSentTo(
            $learner->user,
            AccountRequestNotification::class
        );
        Notification::assertSentTo(
            $parent->user,
            AccountRequestNotification::class
        );
    }

    public function test_can_send_admission_account_request_as_learner()
    {
        Notification::fake();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $grade = Grade::factory()->state([
                'addedby_type' => $school::class,
                'addedby_id' => $school->id,
            ])->create();
        $school->admins()->attach($admin);
        $school->grades()->attach($grade);
        $school->save();
        $learner = Learner::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $parent = ParentModel::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();
        $parent->wards()->attach($learner);
        $parent->save();

        $data = [
            'account' => $parent->accountType,
            'accountId' => $parent->id,
            'action' => 'admission',
            'wardId' => $learner->id,
            'paymentType' => 'fee',
            'paymentData' => json_encode([
                (object) [
                    'amount' => 200
                ]
            ]),
            'admissionData' => json_encode([
                (object) [
                    'gradeId' => $grade->id,
                    'type' => 'virtual'
                ]
            ]),
            'receivers' => json_encode([
                (object) [
                    'account' => $school->accountType,
                    'accountId' => $school->id,
                ]
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();

        Notification::assertSentTo(
            $school->user,
            AccountRequestNotification::class
        );
        Notification::assertSentTo(
            $admin->user,
            AccountRequestNotification::class
        );
    }

    public function test_can_send_administration_account_request_as_user()
    {
        Storage::fake('public');
        Notification::fake();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->for(User::factory())
            ->create();
        $school->admins()->attach($admin);
        $school->save();


        $file = UploadedFile::fake()->image('new_image.jpg');
        $data = [
            'account' => 'user',
            'accountId' => $this->user->id,
            'action' => 'administration',
            'paymentType' => 'salary',
            'paymentData' => json_encode([
                (object) [
                    'name' => 'name of salary',
                    'amount' => 200
                ]
            ]),
            'files' => [$file],
            'receivers' => json_encode([
                (object) [
                    'account' => $school->accountType,
                    'accountId' => $school->id,
                ]
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();

        Notification::assertSentTo(
            $school->user,
            AccountRequestNotification::class
        );
        Notification::assertSentTo(
            $admin->user,
            AccountRequestNotification::class
        );
    }

    public function test_can_send_administration_account_request_as_school()
    {
        Storage::fake('public');
        Notification::fake();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();
        $school->admins()->attach($admin);
        $school->save();

        $user = User::factory()->create();

        $data = [
            'account' => $school->accountType,
            'accountId' => $school->id,
            'action' => 'administration',
            'adminId' => $admin->id,
            'paymentType' => 'salary',
            'paymentData' => json_encode([
                (object) [
                    'name' => 'name of salary',
                    'amount' => 200
                ]
            ]),
            'receivers' => json_encode([
                (object) [
                    'account' => $user->accountType,
                    'accountId' => $user->id,
                ]
            ])
        ];

        $response = $this->postJson('api/request/account/send', $data);

        $response
            ->dump()
            ->assertSuccessful();

        Notification::assertSentTo(
            $user,
            AccountRequestNotification::class
        );
    }

    public function test_can_send_request_message()
    {
        Notification::fake();
        Storage::fake();
        Event::fake();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->forUser()
            ->create();
        $school->admins()->attach($admin);
        $school->save();
        
        $learner = Learner::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->forUser()
            ->create();

        $course = Course::factory()
            ->state([
                'stand_alone' => true,
                'state' => 'ACCEPTED'
            ])
            ->create();
        $course->ownedby()->associate($school);

        $request = Request::factory()->create();
        $request->requestfrom()->associate($school);
        $request->requestto()->associate($learner);
        $request->requestable()->associate($course);
        
        $discount = (new DiscountService)->createDiscount(
            DiscountDTO::createFromData(
                requiresDiscountable: false,
                discountedPrice: 200,
                expiresAt: now()->addDays(10),
                name: $this->faker->name
            )->withAddedby($school)
        );

        $request->discounts()->attach($discount);
        $request->data = RequestDTO::createFromData(action: 'learning');
        $request->save();

        $data = [
            'message' => $this->faker->sentence,
            'file' => UploadedFile::fake()->image('new_image.png')
        ];

        $response = $this->postJson("api/request/{$request->id}/message", $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertEquals(1, count($response['requestMessage']['images']));

        Notification::assertTimesSent(2, RequestMessageNotification::class);

        Event::assertDispatched(NewRequestMessage::class);
    }

    public function test_can_delete_request_message()
    {
        Notification::fake();
        Event::fake();
        Storage::fake('public');

        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->forUser()
            ->create();
        $school->admins()->attach($admin);
        $school->save();
        
        $learner = Learner::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();

        $course = Course::factory()
            ->state([
                'stand_alone' => true,
                'state' => 'ACCEPTED'
            ])
            ->create();
        $course->ownedby()->associate($school);

        $request = Request::factory()->create();
        $request->requestfrom()->associate($school);
        $request->requestto()->associate($learner);
        $request->requestable()->associate($course);
        
        $discount = (new DiscountService)->createDiscount(
            DiscountDTO::createFromData(
                requiresDiscountable: false,
                discountedPrice: 200,
                expiresAt: now()->addDays(10),
                name: $this->faker->name
            )->withAddedby($school)
        );

        $request->discounts()->attach($discount);
        $request->data = RequestDTO::createFromData(action: 'learning');
        $request->save();

        UploadedFile::fake()->image('new_image.png')->storeAs('images', 'new_image.png');

        $image = Image::factory()
            ->state([
                'name' => 'new_image.png',
                'path' => 'images/new_image.png',
                'size' => '554353',
                'addedby_type' => $learner::class,
                'addedby_id' => $learner->id,
            ])
            ->create();

        $message = Message::factory()
            ->state([
                'from_user_id' => $learner->user_id,
                'to_user_id' => $school->user_id
            ])->create();
        $message->messageable()->associate($request);
        $message->fromable()->associate($learner);
        $message->toable()->associate($school);
        $message->images()->attach($image);
        $message->save();

        $response = $this->deleteJson("api/request/message/{$message->id}");

        $response
            ->dump()
            ->assertSuccessful();

        Notification::assertTimesSent(0, RequestMessageNotification::class);

        Event::assertDispatched(DeleteRequestMessage::class);
    }

    public function test_can_decline_to_learning_request_as_learner()
    {
        Notification::fake();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->forUser()
            ->create();
        $school->admins()->attach($admin);
        $school->save();
        
        $learner = Learner::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();

        $course = Course::factory()
            ->state([
                'stand_alone' => true,
                'state' => 'ACCEPTED'
            ])
            ->create();
        $course->ownedby()->associate($school);

        $request = Request::factory()->create();
        $request->requestfrom()->associate($school);
        $request->requestto()->associate($learner);
        $request->requestable()->associate($course);
        
        $discount = (new DiscountService)->createDiscount(
            DiscountDTO::createFromData(
                requiresDiscountable: false,
                discountedPrice: 200,
                expiresAt: now()->addDays(10),
                name: $this->faker->name
            )->withAddedby($school)
        );

        $request->discounts()->attach($discount);
        $request->data = RequestDTO::createFromData(action: 'learning');
        $request->save();

        $data = [
            'requestId' => $request->id,
            'response' => 'declined',
        ];

        $response = $this->postJson('api/request/account/respond', $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertTrue('declined' === strtolower(Request::find($request->id)->state));

        Notification::assertTimesSent(2, AccountResponseNotification::class);
    }

    public function test_can_accept_to_learning_request_as_learner()
    {
        Notification::fake();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->forUser()
            ->create();
        $school->admins()->attach($admin);
        $school->save();
        
        $learner = Learner::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->forUser()
            ->create();

        $course = Course::factory()
            ->state([
                'stand_alone' => true,
                'state' => 'ACCEPTED'
            ])
            ->create();
        $course->ownedby()->associate($school);

        $request = Request::factory()->create();
        $request->requestfrom()->associate($school);
        $request->requestto()->associate($learner);
        $request->requestable()->associate($course);
        
        $discount = (new DiscountService)->createDiscount(
            DiscountDTO::createFromData(
                requiresDiscountable: false,
                discountedPrice: 200,
                expiresAt: now()->addDays(10),
                name: $this->faker->name
            )->withAddedby($school)
        );

        $request->discounts()->attach($discount);
        $request->data = RequestDTO::createFromData(action: 'learning');
        $request->save();

        $data = [
            'requestId' => $request->id,
            'response' => 'accepted',
        ];

        $response = $this->postJson('api/request/account/respond', $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertTrue('accepted' === strtolower(Request::find($request->id)->state));
        $this->assertEquals(1, $learner->refresh()->ownedDiscounts()->count());
        Notification::assertTimesSent(2, AccountResponseNotification::class);
    }

    public function test_can_decline_to_facilitation_request_as_facilitator()
    {
        Notification::fake();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->forUser()
            ->create();
        $school->admins()->attach($admin);
        $school->save();
        
        $facilitator = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();

        $course = Course::factory()
            ->state([
                'stand_alone' => true,
                'state' => 'ACCEPTED'
            ])
            ->create();
        $course->ownedby()->associate($school);

        $request = Request::factory()->create();
        $request->requestfrom()->associate($school);
        $request->requestto()->associate($facilitator);
        $request->requestable()->associate($course);
        
        $salary = (new SalaryService)->set(
            SalaryDTO::createFromData(
                amount: 200,
                period: 'MONTH',
                name: $this->faker->name
            )->withAddedby($school)
        );

        $request->salaries()->attach($salary);
        $request->data = RequestDTO::createFromData(action: 'facilitation');
        $request->save();

        $data = [
            'requestId' => $request->id,
            'response' => 'declined',
        ];

        $response = $this->postJson('api/request/account/respond', $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertTrue('declined' === strtolower(Request::find($request->id)->state));

        Notification::assertTimesSent(2, AccountResponseNotification::class);
    }

    public function test_can_accept_to_facilitation_request_as_facilitator()
    {
        Notification::fake();
        $school = School::factory()
            ->state([
                'company_name' => $this->faker->name,
            ])
            ->for(User::factory(), 'owner')
            ->create();
        $admin = Admin::factory()
            ->state([
                'name' => $this->faker->name,
            ])
            ->forUser()
            ->create();
        $school->admins()->attach($admin);
        $school->save();
        
        $facilitator = Facilitator::factory()
            ->state([
                'name' => $this->faker->name,
                'user_id' => $this->user->id
            ])
            ->create();

        $course = Course::factory()
            ->state([
                'stand_alone' => true,
                'state' => 'ACCEPTED'
            ])
            ->create();
        $course->ownedby()->associate($school);

        $request = Request::factory()->create();
        $request->requestfrom()->associate($school);
        $request->requestto()->associate($facilitator);
        $request->requestable()->associate($course);
        
        $salary = (new SalaryService)->set(
            SalaryDTO::createFromData(
                amount: 200,
                period: 'MONTH',
                name: $this->faker->name
            )->withAddedby($school)
        );

        $request->salaries()->attach($salary);
        $request->data = RequestDTO::createFromData(action: 'facilitation');
        $request->save();

        $data = [
            'requestId' => $request->id,
            'response' => 'accepted',
        ];

        $response = $this->postJson('api/request/account/respond', $data);

        $response
            ->dump()
            ->assertSuccessful();

        $this->assertTrue('accepted' === strtolower(Request::find($request->id)->state));

        $this->assertEquals(1,$facilitator->refresh()->ownedSalaries()->count());

        Notification::assertTimesSent(2, AccountResponseNotification::class);
    }

}
