<?php

namespace Tests\Feature;

use App\Events\ConversationResponse;
use App\Events\DeleteChatMessage;
use App\Events\NewChatAnswer;
use App\Events\NewChatMark;
use App\Events\NewChatMessage;
use App\Events\NewConversation;
use App\Events\UpdateChatMessage;
use App\User;
use App\YourEdu\Answer;
use App\YourEdu\Conversation;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\Message;
use App\YourEdu\Point;
use App\YourEdu\PossibleAnswer;
use App\YourEdu\Professional;
use App\YourEdu\Profile;
use App\YourEdu\Question;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ConversationTest extends TestCase
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
        DB::table('parent_models')->delete();
        DB::table('schools')->delete();
        DB::table('conversations')->delete();
        DB::table('conversationables')->delete();
        DB::table('questions')->delete();
        DB::table('messages')->delete();
        DB::table('answers')->delete();
        DB::table('marks')->delete();
        DB::table('images')->delete();
    }
    
    public function test_can_get_conversations()
    {
        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professionals = Professional::factory()
            ->forUser()->count(2)->create();
        $learners = Learner::factory()
            ->forUser()->count(2)->create();
        $facilitators = Facilitator::factory()
            ->forUser()->count(2)->create();
        
        $conversation = Conversation::factory()->create([
            'state' => 'CLOSED'
        ]);
        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'REQUEST'
        ]);
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();
        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professionals->first());
        $conversationAccount->save();

        $conversation = Conversation::factory()->create([
            'state' => 'CLOSED'
        ]);
        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'REQUEST'
        ]);
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();
        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professionals->last());
        $conversationAccount->save();
        
        $conversation = Conversation::factory()->create([
            'state' => 'CLOSED'
        ]);
        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'REQUEST'
        ]);
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();
        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitators->first());
        $conversationAccount->save();

        $conversation = Conversation::factory()->create([
            'state' => 'CLOSED'
        ]);
        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'ACCEPT'
        ]);
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();
        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'REQUEST'
        ]);
        $conversationAccount->accountable()->associate($facilitators->last());
        $conversationAccount->save();
        
        $conversation = Conversation::factory()->create([
            'state' => 'CLOSED'
        ]);
        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'ACCEPT'
        ]);
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();
        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'REQUEST'
        ]);
        $conversationAccount->accountable()->associate($learners->first());
        $conversationAccount->save();

        $conversation = Conversation::factory()->create([
            'state' => 'CLOSED'
        ]);
        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'ACCEPT'
        ]);
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();
        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'REQUEST'
        ]);
        $conversationAccount->accountable()->associate($learners->last());
        $conversationAccount->save();

        $response = $this->get("/api/conversations");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);

        $this->assertEquals(6, count($response['data']));
    }
    
    public function test_can_get_messages()
    {
        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        for ($i=0; $i < 5; $i++) { 
            $toable = $professional;
            $fromable = $facilitator;
            if ($i % 2) {
                $toable = $facilitator;
                $fromable = $professional;
            }

            $conversation->messages()->create([
                'toable_type' => $toable::class,
                'toable_id' => $toable->id,
                'fromable_type' => $fromable::class,
                'fromable_id' => $fromable->id,
                'message' => $this->faker->sentence,
            ]);
        }

        $conversation = $conversation->refresh();
        
        $response = $this->get("/api/conversation/{$conversation->id}/messages");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);

        $this->assertEquals(5, count($response['data']));
    }
    
    public function test_can_update_message_state()
    {
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = $conversation->messages()->create([
            'state' => 'SENT'
        ]);
        $message->toable()->associate($professional);
        $message->fromable()->associate($facilitator);
        $message->save();

        $conversation = $conversation->refresh();

        $data = [
            'state' => 'seen'
        ];
        
        $response = $this->putJson("/api/conversation/message/{$message->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'chatMessage',
            ]);

        $this->assertEquals(1, Message::where('state', 'seen')->count());

        Event::assertDispatched(UpdateChatMessage::class);
    }
    
    public function test_can_delete_message()
    {
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = $conversation->messages()->create();
        $message->toable()->associate($professional);
        $message->fromable()->associate($facilitator);
        $message->save();

        $conversation = $conversation->refresh();

        $data = [
            'action' => 'delete'
        ];
        
        $response = $this->deleteJson("/api/conversation/message/{$message->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
                'chatMessage' => null,
            ]);

        $this->assertEquals(0, Message::where('id', $message->id)->count());

        Event::assertDispatched(DeleteChatMessage::class);
    }
    
    public function test_can_delete_for_self_message()
    {
        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = $conversation->messages()->create();
        $message->toable()->associate($professional);
        $message->fromable()->associate($facilitator);
        $message->save();

        $conversation = $conversation->refresh();

        $data = [
            'action' => 'self'
        ];
        
        $response = $this->deleteJson("/api/conversation/message/{$message->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'chatMessage',
            ]);

        $this->assertEquals($this->user->id, $message->refresh()->user_deletes[0]);
    }
    
    public function test_cannot_get_messages_of_conversation_to_which_you_dont_have_access()
    {
        $facilitator = Facilitator::factory()
            ->forUser()->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        for ($i=0; $i < 5; $i++) { 
            $toable = $professional;
            $fromable = $facilitator;
            if ($i % 2) {
                $toable = $facilitator;
                $fromable = $professional;
            }

            $conversation->messages()->create([
                'toable_type' => $toable::class,
                'toable_id' => $toable->id,
                'fromable_type' => $fromable::class,
                'fromable_id' => $fromable->id,
                'message' => $this->faker->sentence,
            ]);
        }

        $conversation = $conversation->refresh();
        
        $response = $this->get("/api/conversation/{$conversation->id}/messages");

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you do not hav access to this conversation",
            ]);
    }

    public function test_can_create_conversation()
    {
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->has(
                Profile::factory()->state([
                    'user_id' => $this->user->id
                ])
            )->create();
            
        $professional = Professional::factory()
            ->forUser()->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'otherAccount' => "$professional",
            'otherAccountId' => $professional->id,
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
        ];

        $response = $this->postJson("/api/conversation", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure(['message', 'conversation']);

        Event::assertDispatched(NewConversation::class);
    }
    
    public function test_can_send_message()
    {
        Event::fake();
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $data = [
            'message' => $this->faker->paragraph,
            'file' => UploadedFile::fake()->image('new_image.png')
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/message",  $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'chatMessage'
            ]);

        $this->assertEquals(1, count($response['chatMessage']['images']));
        Event::assertDispatched(NewChatMessage::class);
    }
    
    public function test_cannot_send_message_to_a_conversation_to_which_you_dont_have_access()
    {
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->forUser()->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $data = [
            'message' => $this->faker->paragraph,
            'file' => UploadedFile::fake()->image('new_image.png')
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/message",  $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you do not have access to this conversation",
            ]);
    }
    
    public function test_can_send_message_with_question()
    {
        Event::fake();
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $data = [
            'message' => $this->faker->paragraph,
            'file' => UploadedFile::fake()->image('new_image.png'),
            'questionData' => json_encode(
                (object) [
                    'body' => $this->faker->sentence,
                    'hint' => $this->faker->paragraph,
                    'answerType' => 'true_false',
                    'possibleAnswers' => [
                        (object) [
                            'option' => 'true'
                        ],
                        (object) [
                            'option' => 'false'
                        ]
                    ],
                    'scoreOver' => 5,
                    'autoMark' => false,
                    'correctPossibleAnswers' => [
                        (object) [
                            'option' => 'true'
                        ]
                    ],
                ],
            ),
            'questionFiles' => [
                UploadedFile::fake()->image('new_image1.png'),
            ]
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/message",  $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'chatMessage'
            ]);

        $this->assertEquals(1, count($response['chatMessage']['images']));
        $this->assertEquals(1, count($response['chatMessage']['questions'][0]['images']));
        Event::assertDispatched(NewChatMessage::class);
    }
    
    public function test_can_send_answer_containing_one_possible_answer_to_question()
    {
        Event::fake();
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = Message::factory()
            ->state([
                'toable_type'=> $facilitator::class,
                'toable_id'=> $facilitator->id,
                'fromable_type'=> $professional::class,
                'fromable_id'=> $professional->id,
                'messageable_type'=> $conversation::class,
                'messageable_id'=> $conversation->id,
            ])->create();
        $question = Question::factory()
            ->state([
                'addedby_type'=> $facilitator::class,
                'addedby_id'=> $facilitator->id,
                'questionable_type'=> $message::class,
                'questionable_id'=> $message->id,
                'answer_type' => 'true_false'
            ])->hasPossibleAnswers(4)->create();
        
        $data = [
            'questionId' => $question->id,
            'account'=> "$facilitator",
            'accountId'=> $facilitator->id,
            'answerData' => json_encode(
                (object) [
                    "possibleAnswerIds" => [
                        $question->possibleAnswers->random(1)->first()->id
                    ],
                ],
            ),
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/answer",  $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'answer'
            ]);

        $this->assertEquals(1, count($response['answer']['possibleAnswerIds']));
        Event::assertDispatched(NewChatAnswer::class);
    }
    
    public function test_cannot_send_answer_containing_multiple_possible_answer_to_question_requiring_one_possible_answer()
    {
        Event::fake();
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = Message::factory()
            ->state([
                'toable_type'=> $facilitator::class,
                'toable_id'=> $facilitator->id,
                'fromable_type'=> $professional::class,
                'fromable_id'=> $professional->id,
                'messageable_type'=> $conversation::class,
                'messageable_id'=> $conversation->id,
            ])->create();
        $question = Question::factory()
            ->state([
                'addedby_type'=> $facilitator::class,
                'addedby_id'=> $facilitator->id,
                'questionable_type'=> $message::class,
                'questionable_id'=> $message->id,
                'answer_type' => 'true_false'
            ])->hasPossibleAnswers(4)->create();
        
        $data = [
            'questionId' => $question->id,
            'account'=> "$facilitator",
            'accountId'=> $facilitator->id,
            'answerData' => json_encode(
                (object) [
                    "possibleAnswerIds" => [
                        ...$question->possibleAnswers->random(2)->pluck('id')->toArray()
                    ],
                ],
            ),
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/answer",  $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you didnt provide the correct number of possible answers for your answer",
            ]);

    }
    
    public function test_cannot_send_answer_containing_one_possible_answer_to_question_requiring_multiple_possible_answer()
    {
        Event::fake();
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = Message::factory()
            ->state([
                'toable_type'=> $facilitator::class,
                'toable_id'=> $facilitator->id,
                'fromable_type'=> $professional::class,
                'fromable_id'=> $professional->id,
                'messageable_type'=> $conversation::class,
                'messageable_id'=> $conversation->id,
            ])->create();
        $question = Question::factory()
            ->state([
                'addedby_type'=> $facilitator::class,
                'addedby_id'=> $facilitator->id,
                'questionable_type'=> $message::class,
                'questionable_id'=> $message->id,
                'answer_type' => 'flow'
            ])->hasPossibleAnswers(4)->create();
        
        $data = [
            'questionId' => $question->id,
            'account'=> "$facilitator",
            'accountId'=> $facilitator->id,
            'answerData' => json_encode(
                (object) [
                    "possibleAnswerIds" => [
                        $question->possibleAnswers->random(2)->first()->id
                    ],
                ],
            ),
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/answer",  $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you didnt provide the correct number of possible answers for your answer",
            ]);

    }
    
    public function test_can_send_answer_containing_multiple_possible_answer_to_question()
    {
        Event::fake();
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = Message::factory()
            ->state([
                'toable_type'=> $facilitator::class,
                'toable_id'=> $facilitator->id,
                'fromable_type'=> $professional::class,
                'fromable_id'=> $professional->id,
                'messageable_type'=> $conversation::class,
                'messageable_id'=> $conversation->id,
            ])->create();
        $question = Question::factory()
            ->state([
                'addedby_type'=> $facilitator::class,
                'addedby_id'=> $facilitator->id,
                'questionable_type'=> $message::class,
                'questionable_id'=> $message->id,
                'answer_type' => 'FLOW'
            ])->hasPossibleAnswers(4)->create();
        
        $data = [
            'questionId' => $question->id,
            'account'=> "$facilitator",
            'accountId'=> $facilitator->id,
            'answerData' => json_encode(
                (object) [
                    "possibleAnswerIds" => [
                        ...$question->possibleAnswers->random(4)->pluck('id')->toArray()
                    ],
                ],
            ),
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/answer",  $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'answer'
            ]);

        $this->assertEquals(4, count($response['answer']['possibleAnswerIds']));
        Event::assertDispatched(NewChatAnswer::class);
    }
    
    public function test_cannot_send_answer_with_wrong_file_type_to_question()
    {
        Event::fake();
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = Message::factory()
            ->state([
                'toable_type'=> $facilitator::class,
                'toable_id'=> $facilitator->id,
                'fromable_type'=> $professional::class,
                'fromable_id'=> $professional->id,
                'messageable_type'=> $conversation::class,
                'messageable_id'=> $conversation->id,
            ])->create();
        $question = Question::factory()
            ->state([
                'addedby_type'=> $facilitator::class,
                'addedby_id'=> $facilitator->id,
                'questionable_type'=> $message::class,
                'questionable_id'=> $message->id,
                'answer_type' => 'video'
            ])->hasPossibleAnswers(4)->create();
        
        $data = [
            'questionId' => $question->id,
            'account'=> "$facilitator",
            'accountId'=> $facilitator->id,
            'answerData' => null,
            'answerFiles' => [
                UploadedFile::fake()->image('new_image.png')    
            ],
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/answer",  $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "please the answer expects that you send video. You sent a different type... 😏"
            ]);

    }
    
    public function test_cannot_send_answer_not_containing_required_data_to_question()
    {
        Event::fake();
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = Message::factory()
            ->state([
                'toable_type'=> $facilitator::class,
                'toable_id'=> $facilitator->id,
                'fromable_type'=> $professional::class,
                'fromable_id'=> $professional->id,
                'messageable_type'=> $conversation::class,
                'messageable_id'=> $conversation->id,
            ])->create();
        $question = Question::factory()
            ->state([
                'addedby_type'=> $facilitator::class,
                'addedby_id'=> $facilitator->id,
                'questionable_type'=> $message::class,
                'questionable_id'=> $message->id,
                'answer_type' => 'video'
            ])->hasPossibleAnswers(4)->create();
        
        $data = [
            'questionId' => $question->id,
            'account'=> "$facilitator",
            'accountId'=> $facilitator->id,
            'answerData' => null,
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/answer",  $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, answer does not have the required data"
            ]);

    }
    
    public function test_can_mark_answer()
    {
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();
        Point::factory()->state([
            'pointable_type'=> $facilitator::class,
            'pointable_id'=> $facilitator->id,
            'user_id' => $facilitator->user_id
        ])->create();

        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = Message::factory()
            ->state([
                'toable_type' => $facilitator::class,
                'toable_id' => $facilitator->id,
                'fromable_type' => $professional::class,
                'fromable_id' => $professional->id,
                'messageable_type' => $conversation::class,
                'messageable_id' => $conversation->id,
            ])
            ->has(
                Question::factory()
                    ->state([
                        'answer_type' => 'TRUE_FALSE',
                        'score_over' => 2
                    ])
            )->create();

        $possibleAnswers = PossibleAnswer::factory()
            ->state(
                new Sequence(
                    ['option' => 'true'],
                    ['option' => 'false'],
                )
            )->state([
                'question_type' => $message->questions->first()::class,
                'question_Id' => $message->questions->first()->id,
            ])
            ->count(2)
            ->create();

        $answer = Answer::factory()
            ->state([
                'answerable_type' => $message->questions->first()::class,
                'answerable_Id' => $message->questions->first()->id,
                'answeredby_type' => $professional::class,
                'answeredby_Id' => $professional->id,
                'possible_answer_ids' => [$possibleAnswers->first()->id],
                'answer_type' =>$message->questions->first()->answer_type,
            ])->create();
        
        $data = [
            'answerId' => $answer->id,
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'markData' => json_encode(
                (object) [
                    'score' => 2,
                    'remark' => $this->faker->paragraph,
                ],
            ),
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/mark",  $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'mark'
            ]);

        Event::assertDispatched(NewChatMark::class);
    }
    
    public function test_cannot_mark_answer_more_than_the_score_over()
    {
        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();
        Point::factory()->state([
            'pointable_type'=> $facilitator::class,
            'pointable_id'=> $facilitator->id,
            'user_id' => $facilitator->user_id
        ])->create();

        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = Message::factory()
            ->state([
                'toable_type' => $facilitator::class,
                'toable_id' => $facilitator->id,
                'fromable_type' => $professional::class,
                'fromable_id' => $professional->id,
                'messageable_type' => $conversation::class,
                'messageable_id' => $conversation->id,
            ])
            ->has(
                Question::factory()
                    ->state([
                        'answer_type' => 'TRUE_FALSE',
                        'score_over' => 2
                    ])
            )->create();

        $possibleAnswers = PossibleAnswer::factory()
            ->state(
                new Sequence(
                    ['option' => 'true'],
                    ['option' => 'false'],
                )
            )->state([
                'question_type' => $message->questions->first()::class,
                'question_Id' => $message->questions->first()->id,
            ])
            ->count(2)
            ->create();

        $answer = Answer::factory()
            ->state([
                'answerable_type' => $message->questions->first()::class,
                'answerable_Id' => $message->questions->first()->id,
                'answeredby_type' => $professional::class,
                'answeredby_Id' => $professional->id,
                'possible_answer_ids' => [$possibleAnswers->first()->id],
                'answer_type' =>$message->questions->first()->answer_type,
            ])->create();
        
        $data = [
            'answerId' => $answer->id,
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'markData' => json_encode(
                (object) [
                    'score' => 3,
                    'remark' => $this->faker->paragraph,
                ],
            ),
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/mark",  $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "you cannot score this answer above 2 😏",
            ]);

    }
    
    public function test_cannot_mark_answer_already_marked_by_your_account()
    {
        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();
        Point::factory()->state([
            'pointable_type'=> $facilitator::class,
            'pointable_id'=> $facilitator->id,
            'user_id' => $facilitator->user_id
        ])->create();

        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = Message::factory()
            ->state([
                'toable_type' => $facilitator::class,
                'toable_id' => $facilitator->id,
                'fromable_type' => $professional::class,
                'fromable_id' => $professional->id,
                'messageable_type' => $conversation::class,
                'messageable_id' => $conversation->id,
            ])
            ->has(
                Question::factory()
                    ->state([
                        'answer_type' => 'TRUE_FALSE',
                        'score_over' => 2
                    ])
            )->create();

        $possibleAnswers = PossibleAnswer::factory()
            ->state(
                new Sequence(
                    ['option' => 'true'],
                    ['option' => 'false'],
                )
            )->state([
                'question_type' => $message->questions->first()::class,
                'question_Id' => $message->questions->first()->id,
            ])
            ->count(2)
            ->create();

        $answer = Answer::factory()
            ->state([
                'answerable_type' => $message->questions->first()::class,
                'answerable_Id' => $message->questions->first()->id,
                'answeredby_type' => $professional::class,
                'answeredby_Id' => $professional->id,
                'possible_answer_ids' => [$possibleAnswers->first()->id],
                'answer_type' =>$message->questions->first()->answer_type,
            ])
            ->hasMarks(1, [
                'markedby_type' => $facilitator::class,
                'markedby_id' => $facilitator->id,
                'state' => 'CORRECT',
                'score' => 2,
                'score_over' => 2,
            ])->create();
        
        $data = [
            'answerId' => $answer->id,
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'markData' => json_encode(
                (object) [
                    'score' => 2,
                    'remark' => $this->faker->paragraph,
                ],
            ),
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/mark",  $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "charlie, you can't mark the same item twice 😏",
            ]);

    }
    
    public function test_cannot_mark_answer_as_the_answeredby()
    {
        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();
        Point::factory()->state([
            'pointable_type'=> $facilitator::class,
            'pointable_id'=> $facilitator->id,
            'user_id' => $facilitator->user_id
        ])->create();

        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($facilitator);
        $conversationAccount->save();

        $conversationAccount = $conversation->conversationAccounts()->create();
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();

        $message = Message::factory()
            ->state([
                'toable_type' => $facilitator::class,
                'toable_id' => $facilitator->id,
                'fromable_type' => $professional::class,
                'fromable_id' => $professional->id,
                'messageable_type' => $conversation::class,
                'messageable_id' => $conversation->id,
            ])
            ->has(
                Question::factory()
                    ->state([
                        'answer_type' => 'TRUE_FALSE',
                        'score_over' => 2
                    ])
            )->create();

        $possibleAnswers = PossibleAnswer::factory()
            ->state(
                new Sequence(
                    ['option' => 'true'],
                    ['option' => 'false'],
                )
            )->state([
                'question_type' => $message->questions->first()::class,
                'question_Id' => $message->questions->first()->id,
            ])
            ->count(2)
            ->create();

        $answer = Answer::factory()
            ->state([
                'answerable_type' => $message->questions->first()::class,
                'answerable_Id' => $message->questions->first()->id,
                'answeredby_type' => $facilitator::class,
                'answeredby_Id' => $facilitator->id,
                'possible_answer_ids' => [$possibleAnswers->first()->id],
                'answer_type' =>$message->questions->first()->answer_type,
            ])->create();
        
        $data = [
            'answerId' => $answer->id,
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'markData' => json_encode(
                (object) [
                    'score' => 2,
                    'remark' => $this->faker->paragraph,
                ],
            ),
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/mark",  $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "you cannot mark an your own answer 😏",
            ]);

    }
    
    public function test_can_create_conversation_response()
    {
        Event::fake();
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount1 = $conversation->conversationAccounts()->create([
            'state' => 'PENDING'
        ]);
        $conversationAccount1->accountable()->associate($facilitator);
        $conversationAccount1->save();

        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'REQUEST'
        ]);
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();
            
        $data = [
            'response' => 'accept',
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'otherAccount' => "$professional",
            'otherAccountId' => $professional->id,
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/response",  $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertEquals('ACCEPT', $conversationAccount1->refresh()->state);
        Event::assertDispatched(ConversationResponse::class);
    }
    
    public function test_cannot_create_conversation_response_with_wrong_response()
    {
        Event::fake();
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount1 = $conversation->conversationAccounts()->create([
            'state' => 'PENDING'
        ]);
        $conversationAccount1->accountable()->associate($facilitator);
        $conversationAccount1->save();

        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'REQUEST'
        ]);
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();
            
        $data = [
            'response' => 'accepted',
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'otherAccount' => "$professional",
            'otherAccountId' => $professional->id,
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/response",  $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, accepted is not a correct response",
            ]);
    }
    
    public function test_can_block_conversation_response()
    {
        Event::fake();
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount1 = $conversation->conversationAccounts()->create([
            'state' => 'PENDING'
        ]);
        $conversationAccount1->accountable()->associate($facilitator);
        $conversationAccount1->save();

        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'REQUEST'
        ]);
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();
            
        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'otherAccount' => "$professional",
            'otherAccountId' => $professional->id,
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/block",  $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertEquals('BLOCK', $conversationAccount1->refresh()->state);
        Event::assertDispatched(ConversationResponse::class);
    }
    
    public function test_can_unblock_create_conversation_response()
    {
        Event::fake();
        Storage::fake('public');

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();
        
        $conversation = Conversation::factory()->create();

        $conversationAccount1 = $conversation->conversationAccounts()->create([
            'state' => 'BLOCK'
        ]);
        $conversationAccount1->accountable()->associate($facilitator);
        $conversationAccount1->save();

        $conversationAccount = $conversation->conversationAccounts()->create([
            'state' => 'REQUEST'
        ]);
        $conversationAccount->accountable()->associate($professional);
        $conversationAccount->save();
            
        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'otherAccount' => "$professional",
            'otherAccountId' => $professional->id,
        ];

        $response = $this->postJson("/api/conversation/{$conversation->id}/unblock",  $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertEquals('ACCEPT', $conversationAccount1->refresh()->state);
        Event::assertDispatched(ConversationResponse::class);
    }
}
