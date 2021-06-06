<?php

namespace Tests\Feature;

use App\User;
use App\YourEdu\Answer;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker;
    
    protected function setUp() : void
    {
        parent::setUp();    
        DB::table('users')->delete();
        DB::table('questions')->delete();
        DB::table('answers')->delete();
        DB::table('learners')->delete();
        DB::table('professionals')->delete();
        DB::table('parent_models')->delete();
    }

    public function test_can_register_with_valid_data()
    {
        Artisan::call("passport:install");
        $data = [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'username' => "username",
            'password' => 'password',
            'passwordConfirmation' => 'password',
        ];

        $response = $this->postJson("/api/register", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'status', 'user', 'token'
            ]);

        $this->assertEquals($response['user']['username'], $data['username']);
    }

    public function test_cannot_register_with_invalid_data()
    {
        $data = [
            
        ];

        $response = $this->postJson("/api/register", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJsonStructure([
                'message', 'errors'
            ]);
    }

    public function test_can_login_with_email_and_password()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->postJson("/api/login", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'status', 'user', 'token'
            ]);

        $this->assertEquals($response['user']['username'], $user->username);
    }

    public function test_can_login_with_username_and_password()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email,
                'username' => 'itismeagain'
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            'username' => $user->username,
            'password' => 'password',
        ];

        $response = $this->postJson("/api/login", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'status', 'user', 'token'
            ]);

        $this->assertEquals($response['user']['username'], $user->username);
    }

    public function test_cannot_login_with_invalid_data()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            
        ];

        $response = $this->postJson("/api/login", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJsonStructure([
                'message', 'errors'
            ]);
    }

    public function test_can_logout()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $user->withAccessToken(
            $user->createToken('YourEdu')->accessToken
        );

        $this->actingAs($user, 'api');

        $response = $this->postJson("/api/logout");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertTrue($user->refresh()->tokens()->latest()->first()->revoked);
    }

    public function test_can_create_user_question()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            'questionData' => json_encode((object) ['body' => 'what is the name of my pet?']),
        ];

        $response = $this->postJson("/api/user/secret", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertEquals(1 ,Question::where('body', 'what is the name of my pet?')->count());
    }

    public function test_cannot_create_user_question_when_limit_is_reached()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        Question::factory()
            ->state([
                'addedby_type' => $user::class,
                'addedby_id' => $user->id,
            ])->count(3)->create();

        $data = [
            'questionData' => json_encode((object) ['body' => 'what is the name of my pet?']),
        ];

        $response = $this->postJson("/api/user/secret", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, your questions has already reached the maximum limit."
            ]);

        $this->assertNotEquals(1 ,Question::where('body', 'what is the name of my pet?')->count());
    }

    public function test_can_create_user_answer()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $question = Question::factory()
            ->state([
                'addedby_type' => $user::class,
                'addedby_id' => $user->id,
                'body' => 'what is the name of my pet?'
            ])->create();

        $data = [
            'questionId' => $question->id,
            'answerData' => json_encode((object) ['answer' => 'jojo']),
        ];

        $response = $this->postJson("/api/user/answer", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertEquals(1 ,Answer::where('answer', 'jojo')->count());
        $this->assertEquals(1 ,$question->refresh()->answers()->count());
    }

    public function test_cannot_create_user_answer_for_question_you_didnt_create()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();
            
        $user1 = User::factory()->create();

        $this->actingAs($user, 'api');

        $question = Question::factory()
            ->state([
                'addedby_type' => $user1::class,
                'addedby_id' => $user1->id,
                'body' => 'what is the name of my pet?'
            ])->create();

        $data = [
            'questionId' => $question->id,
            'answerData' => json_encode((object) ['answer' => 'jojo']),
        ];

        $response = $this->postJson("/api/user/answer", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "the question you tried answering either isn't yours or was not found."
            ]);

        $this->assertEquals(0 ,Answer::where('answer', 'jojo')->count());
        $this->assertEquals(0 ,$question->refresh()->answers()->count());
    }

    public function test_cannot_create_user_answer_for_question_already_answered()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $question = Question::factory()
            ->state([
                'addedby_type' => $user::class,
                'addedby_id' => $user->id,
                'body' => 'what is the name of my pet?'
            ])->create();

        $answer = Answer::factory()->create();

        $answer->answeredby()->associate($user);
        $answer->answerable()->associate($question);
        $answer->save();

        $data = [
            'questionId' => $question->id,
            'answerData' => json_encode((object) ['answer' => 'jojo']),
        ];

        $response = $this->postJson("/api/user/answer", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "you have already answered this question."
            ]);

        $this->assertEquals(0 ,Answer::where('answer', 'jojo')->count());
        $this->assertEquals(1 ,$question->refresh()->answers()->count());
    }

    public function test_can_delete_user_answer()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $question = Question::factory()
            ->state([
                'addedby_type' => $user::class,
                'addedby_id' => $user->id,
                'body' => 'what is the name of my pet?'
            ])->create();

        $answer = Answer::factory()
            ->state([
                'answeredby_type' => $user::class,
                'answeredby_id' => $user->id,
                'answerable_type' => $question::class,
                'answerable_id' => $question->id,
                'answer' => 'jojo'
            ])->create();

        $response = $this->deleteJson("/api/user/answer/{$answer->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertEquals(0 ,Answer::where('answer', 'jojo')->count());
        $this->assertEquals(0 ,$question->refresh()->answers()->count());
    }

    public function test_cannot_delete_user_answer_which_doesnt_belong_to_you()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();
        $user1 = User::factory()->create();

        $this->actingAs($user, 'api');

        $question = Question::factory()
            ->state([
                'addedby_type' => $user::class,
                'addedby_id' => $user->id,
                'body' => 'what is the name of my pet?'
            ])->create();

        $answer = Answer::factory()
            ->state([
                'answeredby_type' => $user1::class,
                'answeredby_id' => $user1->id,
                'answerable_type' => $question::class,
                'answerable_id' => $question->id,
                'answer' => 'jojo'
            ])->create();

        $response = $this->deleteJson("/api/user/answer/{$answer->id}");

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "the answer either isn't yours or was not found."
            ]);

        $this->assertEquals(1 ,Answer::where('answer', 'jojo')->count());
        $this->assertEquals(1 ,$question->refresh()->answers()->count());
    }

    public function test_can_delete_user_question()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $question = Question::factory()
            ->state([
                'addedby_type' => $user::class,
                'addedby_id' => $user->id,
                'body' => 'what is the name of my pet?'
            ])->create();

        $response = $this->deleteJson("/api/user/secret/{$question->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertEquals(0 ,Question::where('body', 'what is the name of my pet?')->count());
    }

    public function test_cannot_delete_user_question_which_doesnt_belong_to_you()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();
        $user1 = User::factory()->create();

        $this->actingAs($user, 'api');

        $question = Question::factory()
            ->state([
                'addedby_type' => $user1::class,
                'addedby_id' => $user1->id,
                'body' => 'what is the name of my pet?'
            ])->create();

        $response = $this->deleteJson("/api/user/secret/{$question->id}");

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "the question you tried answering either isn't yours or was not found."
            ]);

        $this->assertEquals(1 ,Question::where('body', 'what is the name of my pet?')->count());
    }

    public function test_can_create_account_for_user()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            'accountData' => json_encode(
                (object) [
                    'create' => 'learner',
                    'name' => 'good boy',
                ]
            )
        ];

        $response = $this->postJson("/api/user/account", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'profile'
            ]);


        $this->assertNotNull($response['profile']);
    }

    public function test_can_create_user_for_another_and_be_a_referrer()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            'extraUserData' => json_encode(
                (object) [
                    'firstName' => 'good',
                    'lastName' => 'boy',
                    'username' => 'goodusername',
                    'password' => 'password',
                    'passwordConfirmation' => 'password',
                ]
            )
        ];

        $response = $this->postJson("/api/user/account", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'profile'
            ]);


        $this->assertNull($response['profile']);
        $this->assertTrue(User::where('username', 'goodusername')->exists());
        $this->assertEquals($user->id, User::where('username', 'goodusername')->first()->referrer_id);
    }

    public function test_can_create_user_and_account_for_another_and_be_a_referrer()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            'extraUserData' => json_encode(
                (object) [
                    'firstName' => 'good',
                    'lastName' => 'boy',
                    'username' => 'goodusername',
                    'password' => 'password',
                    'passwordConfirmation' => 'password',
                ]
            ),
            'extraAccountData' => json_encode(
                (object) [
                    'name' => 'good prof',
                    'create' => 'professional',
                    'description' => $this->faker->sentence,
                    'role' => 'counselor',
                ]
            )
        ];

        $response = $this->postJson("/api/user/account", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'profile'
            ]);


        $this->assertNull($response['profile']);
        $this->assertTrue(User::where('username', 'goodusername')->exists());
        $this->assertTrue(Professional::where('name', 'good prof')->exists());
        $this->assertEquals($user->id, User::where('username', 'goodusername')->first()->referrer_id);
    }

    public function test_can_create_user_and_account_for_learner_and_parent_and_be_a_referrer_for_both()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            'extraUserData' => json_encode(
                (object) [
                    'firstName' => 'good',
                    'lastName' => 'boy',
                    'username' => 'goodusername',
                    'password' => 'password',
                    'passwordConfirmation' => 'password',
                ]
            ),
            'extraAccountData' => json_encode(
                (object) [
                    'name' => 'good prof',
                    'create' => 'learner',
                ]
            ),
            'parentUserData' => json_encode(
                (object) [
                    'firstName' => 'old',
                    'lastName' => 'boy',
                    'username' => 'oldusername',
                    'dob' => now()->subYears(40)->toDateTimeString(),
                    'password' => 'password',
                    'passwordConfirmation' => 'password',
                ]
            ),
            'parentAccountData' => json_encode(
                (object) [
                    'role' => 'father',
                ]
            )
        ];

        $response = $this->postJson("/api/user/account", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'profile'
            ]);


        $this->assertNull($response['profile']);
        $this->assertTrue(User::where('username', 'goodusername')->exists());
        $this->assertTrue(User::where('username', 'oldusername')->exists());
        $this->assertTrue(
            ParentModel::where('name', User::where('username', 'oldusername')->first()->full_name)->exists()
        );
        $this->assertTrue(Learner::where('name', 'good prof')->exists());
        $this->assertEquals($user->id, User::where('username', 'goodusername')->first()->referrer_id);
        $this->assertEquals($user->id, User::where('username', 'oldusername')->first()->referrer_id);
        $this->assertTrue(
            User::where('username', 'oldusername')
                ->first()->parent->isParenting(
                    User::where('username', 'goodusername')->first()->learner
                )
        );
    }

    public function testCannotCreateUserAndAccountForLearnerAndParentWhenParentIsNotAnAdult()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            'extraUserData' => json_encode(
                (object) [
                    'firstName' => 'good',
                    'lastName' => 'boy',
                    'username' => 'goodusername',
                    'password' => 'password',
                    'passwordConfirmation' => 'password',
                ]
            ),
            'extraAccountData' => json_encode(
                (object) [
                    'name' => 'good prof',
                    'create' => 'learner',
                ]
            ),
            'parentUserData' => json_encode(
                (object) [
                    'firstName' => 'old',
                    'lastName' => 'boy',
                    'username' => 'oldusername',
                    'password' => 'password',
                    'passwordConfirmation' => 'password',
                ]
            ),
            'parentAccountData' => json_encode(
                (object) [
                    'role' => 'father',
                ]
            )
        ];

        $minimumAge = User::MINIMUM_ADULT_AGE;

        $response = $this->postJson("/api/user/account", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, old boy must be an adult of at least {$minimumAge} years."
            ]);


        $this->assertFalse(User::where('username', 'goodusername')->exists());
        $this->assertFalse(User::where('username', 'oldusername')->exists());
        $this->assertFalse(Learner::where('name', 'good prof')->exists());
    }

    public function testCannotCreateUserAndAccountForLearnerAndParentWithoutValidParentingRole()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            'extraUserData' => json_encode(
                (object) [
                    'firstName' => 'good',
                    'lastName' => 'boy',
                    'username' => 'goodusername',
                    'password' => 'password',
                    'passwordConfirmation' => 'password',
                ]
            ),
            'extraAccountData' => json_encode(
                (object) [
                    'name' => 'good prof',
                    'create' => 'learner',
                ]
            ),
            'parentUserData' => json_encode(
                (object) [
                    'firstName' => 'old',
                    'lastName' => 'boy',
                    'username' => 'oldusername',
                    'dob' => now()->subYears(40)->toDateTimeString(),
                    'password' => 'password',
                    'passwordConfirmation' => 'password',
                ]
            ),
            'parentAccountData' => json_encode(
                (object) [
                    'role' => 'model',
                ]
            )
        ];

        $response = $this->postJson("/api/user/account", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, model is not a valid parenting role."
            ]);


        $this->assertFalse(User::where('username', 'goodusername')->exists());
        $this->assertFalse(User::where('username', 'oldusername')->exists());
        $this->assertFalse(Learner::where('name', 'good prof')->exists());
    }

    public function test_cannot_create_user_and_account_for_another_when_not_a_user()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $data = [
            'extraUserData' => json_encode(
                (object) [
                    'firstName' => 'good',
                    'lastName' => 'boy',
                    'username' => 'goodusername',
                    'password' => 'password',
                    'passwordConfirmation' => 'password',
                ]
            )
        ];

        $response = $this->postJson("/api/user/account", $data);

        $response
            ->dump()
            ->assertStatus(401)
            ->assertJson([
                'message' => "Unauthenticated."
            ]);


        $this->assertFalse(User::where('username', 'goodusername')->exists());
        $this->assertNotEquals($user->id, User::where('username', 'goodusername')->first()?->referrer_id);
    }

    public function test_cannot_create_user_for_another_without_proper_data_like_username()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            'extraUserData' => json_encode(
                (object) [
                    'firstName' => 'good',
                    'lastName' => 'boy',
                    'password' => 'password',
                    'passwordConfirmation' => 'password',
                ]
            )
        ];

        $response = $this->postJson("/api/user/account", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'username is a required 😏. please provide one'
            ]);


        $this->assertFalse(User::where('username', 'goodusername')->exists());
        $this->assertNotEquals($user->id, User::where('username', 'goodusername')->first()?->referrer_id);
    }

    public function testCannotCreateUserAndAccountForAnotherWithEmptyData()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            
        ];

        $response = $this->postJson("/api/user/account", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you don't have enough data to perform this action",
            ]);

    }

    public function testCanUnregisterUserAndRevokeTokenAndDeleteAccounts()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])
            ->hasLearner()
            ->hasFacilitator()
            ->hasProfessionals()
            ->create();

        $token = $user->createToken('YourEdu');

        $user->withAccessToken($token->accessToken);

        $this->actingAs($user, 'api');

        $this->assertNotNull(User::find($user->id));

        $response = $this->postJson("/api/unregister");

        $response
            ->dump()
            ->assertSuccessful(422)
            ->assertJson([
                'message' => 'successful'
            ]);


        $this->assertNull(User::find($user->id));
        $this->assertNull(Learner::where('user_id', $user->id)->first());
        $this->assertTrue($token->token->refresh()->revoked);
    }

    public function testCanGetUserUsingSecretAnswerPair()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $question = Question::factory()
            ->state([
                'addedby_type' => $user::class,
                'addedby_id' => $user->id,
                'body' => 'what is the name of your pet'
            ])
            ->hasAnswers([
                'answeredby_type' => $user::class,
                'answeredby_id' => $user->id,
                'answer' => 'jojo'
            ])->create();

        $data = [
            'answer' => 'jojo',
            'questionId' => $question->id
        ];

        $response = $this->json('GET',"/api/user/secret/answer", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'status', 'user', 'token'
            ]);


        $this->assertTrue($response['status']);
        $this->assertNotNull($response['token']);
        $this->assertInstanceOf( User::class, $user);
    }

    public function testCannotGetUserUsingWrongSecretAnswerPair()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $this->actingAs($user, 'api');

        $question = Question::factory()
            ->state([
                'addedby_type' => $user::class,
                'addedby_id' => $user->id,
                'body' => 'what is the name of your pet'
            ])
            ->hasAnswers([
                'answeredby_type' => $user::class,
                'answeredby_id' => $user->id,
                'answer' => 'jojo'
            ])->create();

        $data = [
            'answer' => 'jojoj',
            'questionId' => $question->id
        ];

        $response = $this->json('GET',"/api/user/secret/answer", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, the question you answered does'nt have jojoj as an answer."
            ]);
    }

    public function testCanDeleteOwnUserAccount()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])
            ->hasLearner()->create();

        $this->actingAs($user, 'api');

        $response = $this->deleteJson("/api/user/learner/{$user->learner->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => "successful"
            ]);

        $this->assertNull($user->refresh()->learner);
    }

    public function testCannotDeleteAnotherUsersAccount()
    {
        $user = User::factory()
            ->state([
                'email' => $this->faker->email
            ])->create();

        $learner = Learner::factory()
            ->forUser()->create();

        $this->actingAs($user, 'api');

        $response = $this->deleteJson("/api/user/learner/{$learner->id}");

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, learner account with id {$learner->id} does'nt belong to you.",
            ]);

        $this->assertNotNull(Learner::find($learner->id));
    }

    public function testCanUpdateSingleAttributeOfAnAccountBelongingToYou()
    {
        $user = User::factory()
            ->hasProfessionals([
                'role' => "COUNSELOR"
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            'accountData' => json_encode((object) ['role' => 'nanny',])
        ];

        $response = $this->putJson("api/user/professional/{$user->professionals()->first()->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'profile'
            ]);
        
        $this->assertEquals('NANNY', $user->refresh()->professionals()->first()->role);
    }

    public function testCanUpdateMultipleAttributeOfAnAccountBelongingToYou()
    {
        $user = User::factory()
            ->hasProfessionals([
                'role' => "COUNSELOR",
                'name' => "good boy"
            ])->create();

        $this->actingAs($user, 'api');

        $data = [
            'accountData' => json_encode((object) [
                'role' => 'nanny',
                'name' => 'great guy',
            ])
        ];

        $response = $this->putJson("api/user/professional/{$user->professionals()->first()->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'profile'
            ]);
        
        $this->assertEquals('NANNY', $user->refresh()->professionals()->first()->role);
        $this->assertEquals('great guy', $user->refresh()->professionals()->first()->name);
    }
}
