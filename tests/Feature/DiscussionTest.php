<?php

namespace Tests\Feature;

use App\Events\DeleteDiscussion;
use App\Events\DeleteDiscussionMessage;
use App\Events\NewDiscussion;
use App\Events\NewDiscussionMessage;
use App\Events\NewDiscussionParticipant;
use App\Events\NewDiscussionPendingParticipant;
use App\Events\RemoveDiscussionParticipant;
use App\Events\RemoveDiscussionPendingParticipant;
use App\Events\UpdatedDiscussionParticipant;
use App\Events\UpdateDiscussionMessage;
use App\Events\UpdateDiscussion;
use App\Http\Resources\DiscussionParticipantResource;
use App\Notifications\DiscussionContributionResponseNotification;
use App\Notifications\DiscussionInvitationNotification;
use App\Notifications\DiscussionInvitationResponseNotification;
use App\Notifications\DiscussionJoinNotification;
use App\Notifications\DiscussionJoinRequestNotification;
use App\Notifications\DiscussionJoinResponseNotification;
use App\Notifications\NewDiscussionMessageNotification;
use App\Notifications\RemoveDiscussionParticipantNotification;
use App\Notifications\UpdateDiscussionParticipantNotification;
use App\Services\DiscussionService;
use App\Services\MessageService;
use App\User;
use App\YourEdu\Discussion;
use App\YourEdu\Facilitator;
use App\YourEdu\Image;
use App\YourEdu\Learner;
use App\YourEdu\Message;
use App\YourEdu\ParentModel;
use App\YourEdu\Participant;
use App\YourEdu\Profile;
use App\YourEdu\Request;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DiscussionTest extends TestCase
{
    use WithFaker;

    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'api');

        DB::table('facilitators')->delete();
        DB::table('professionals')->delete();
        DB::table('discussions')->delete();
        DB::table('messages')->delete();
        DB::table('images')->delete();
        DB::table('participants')->delete();
    }
    
    public function test_cannot_create_discussion_without_valid_data()
    {
        $data = [

        ];

        $response = $this->postJson("/api/discussion", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['title', 'type', 'allowed']
            ]);
    }
    
    public function test_can_create_discussion()
    {
        Storage::fake('public');
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'title' => $this->faker->title,
            'type' => 'private',
            'allowed' => 'all',
            'preamble' => $this->faker->sentence,
            'restricted' => false,
            'files' => [
                UploadedFile::fake()->image('image1.png'),
                UploadedFile::fake()->image('image2.png'),
            ]
        ];

        $response = $this->postJson("/api/discussion", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'discussion'
            ]);

        $this->assertEquals(2, count($response['discussion']['images']));

        Event::assertDispatched(NewDiscussion::class);
    }
    
    public function test_cannot_create_discussion_with_more_than_the_max_files()
    {
        Storage::fake('public');
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'title' => $this->faker->title,
            'type' => 'private',
            'allowed' => 'all',
            'preamble' => $this->faker->sentence,
            'restricted' => false,
            'files' => []
        ];

        $maxFiles = DiscussionService::MAX_NUMBER_OF_FILES;
        for ($i=0; $i < $maxFiles + 1; $i++) { 
            $data['files'][] = UploadedFile::fake()->image("image$i.png");
        }

        $response = $this->postJson("/api/discussion", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you cannot have more than {$maxFiles} files for a discussion.",
            ]);
    }
    
    public function test_can_update_discussion()
    {
        Storage::fake('public');
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
            ])->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])->create();

        $discussion->images()->attach($image);

        $data = [
            'title' => 'edited',
            'type' => 'private',
            'allowed' => 'all',
            'preamble' => $this->faker->sentence,
            'restricted' => false,
            'removedFiles' => json_encode([
                (object) [
                    'id' => $image->id,
                    'type' => 'image',
                ]
            ]),
            'files' => [
                UploadedFile::fake()->image('image1.png'),
                UploadedFile::fake()->image('image2.png'),
            ]
        ];

        $response = $this->putJson("/api/discussion/{$discussion->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'discussion'
            ]);

        $this->assertEquals(2, count($response['discussion']['images']));
        $this->assertEquals('edited', $response['discussion']['title']);

        Event::assertDispatched(UpdateDiscussion::class);
    }
    
    public function test_can_update_discussion_as_an_admin()
    {
        Storage::fake('public');
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()->hasProfile(function($a, $b) {
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'state' => "ADMIN",
                'user_id' => $this->user->id,
            ])->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $facilitator1::class,
                'addedby_id' => $facilitator1->id,
            ])->create();

        $discussion->images()->attach($image);

        $data = [
            'title' => 'edited',
            'type' => 'private',
            'allowed' => 'all',
            'preamble' => $this->faker->sentence,
            'restricted' => false,
            'removedFiles' => json_encode([
                (object) [
                    'id' => $image->id,
                    'type' => 'image',
                ]
            ]),
            'files' => [
                UploadedFile::fake()->image('image1.png'),
                UploadedFile::fake()->image('image2.png'),
            ]
        ];

        $response = $this->putJson("/api/discussion/{$discussion->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'discussion'
            ]);

        $this->assertEquals(2, count($response['discussion']['images']));
        $this->assertEquals('edited', $response['discussion']['title']);

        Event::assertDispatched(UpdateDiscussion::class);
    }
    
    public function test_cannot_update_discussion_with_more_than_the_max_files()
    {
        Storage::fake('public');
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
            ])->create();

        $images = Image::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])->count(2)->create();

        $discussion->images()->attach($images);

        $data = [
            'title' => 'edited',
            'type' => 'private',
            'allowed' => 'all',
            'preamble' => $this->faker->sentence,
            'restricted' => false,
            'removedFiles' => json_encode([
                (object) [
                    'id' => $images->first()->id,
                    'type' => 'image',
                ]
            ]),
            'files' => [
                UploadedFile::fake()->image('image1.png'),
                UploadedFile::fake()->image('image2.png'),
                UploadedFile::fake()->image('image3.png'),
            ]
        ];

        $maxFiles = DiscussionService::MAX_NUMBER_OF_FILES;
        $response = $this->putJson("/api/discussion/{$discussion->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you cannot have more than {$maxFiles} files for a discussion.",
            ]);
    }
    
    public function test_cannot_update_discussion_if_not_admin()
    {
        Storage::fake('public');
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $facilitator1::class,
                'addedby_id' => $facilitator1->id,
            ])->create();

        $discussion->images()->attach($image);

        $data = [
            'title' => 'edited',
            'type' => 'private',
            'allowed' => 'all',
            'preamble' => $this->faker->sentence,
            'restricted' => false,
            'removedFiles' => json_encode([
                (object) [
                    'id' => $image->id,
                    'type' => 'image',
                ]
            ]),
            'files' => [
                UploadedFile::fake()->image('image1.png'),
                UploadedFile::fake()->image('image2.png'),
            ]
        ];

        $response = $this->putJson("/api/discussion/{$discussion->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'you are not authorized to perform this action on this discussion',
            ]);
    }
    
    public function test_can_delete_discussion()
    {
        Storage::fake('public');
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
            ])->create();

        $response = $this->deleteJson("/api/discussion/{$discussion->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        Event::assertDispatched(DeleteDiscussion::class);
    }
    
    public function test_can_delete_discussion_as_admin()
    {
        Storage::fake('public');
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()->hasProfile(function($a, $b) {
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'state' => "ADMIN",
                'user_id' => $this->user->id,
            ])->create();
            
        $response = $this->deleteJson("/api/discussion/{$discussion->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        Event::assertDispatched(DeleteDiscussion::class);
    }
    
    public function test_cannot_delete_discussion_if_not_admin()
    {
        Storage::fake('public');
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();
        
        $facilitator1 = Facilitator::factory()
            ->forUser()->hasProfile(function($a, $b) {
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])->create();
            
        $response = $this->deleteJson("/api/discussion/{$discussion->id}");

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'you are not authorized to perform this action on this discussion',
            ]);
    }
    
    public function test_can_get_discussion_messages()
    {

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()->hasProfile(function($a, $b) {
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'state' => "ADMIN",
                'user_id' => $this->user->id,
            ])->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])->create();

        $discussion->images()->attach($image);

        Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
            ])->count(6)->create();

        $response = $this->getJson("/api/discussion/{$discussion->id}/messages");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'data',
                'links',
                'meta'
            ]);

        $this->assertEquals(6, count($response['data']));
    }
    
    public function test_can_get_restricted_discussion_accepted_messages_as_non_admin()
    {

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()->hasProfile(function($a, $b) {
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
                'restricted' => true,
            ])->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $facilitator1::class,
                'addedby_id' => $facilitator1->id,
            ])->create();

        $discussion->images()->attach($image);

        Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
                'state' => 'ACCEPTED',
            ])->count(3)->create();
        Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
                'state' => 'PENDING',
            ])->count(3)->create();

        $response = $this->getJson("/api/discussion/{$discussion->id}/messages");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'data',
                'links',
                'meta'
            ]);

        $this->assertEquals(3, count($response['data']));
    }
    
    public function test_can_get_restricted_discussion_pending_messages_as_admin()
    {

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();
        $facilitator1 = Facilitator::factory()
            ->forUser()->hasProfile(function($a, $b) {
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'restricted' => true,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'state' => "ADMIN",
                'user_id' => $this->user->id,
            ])->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])->create();

        $discussion->images()->attach($image);

        Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
                'state' => 'ACCEPTED',
            ])->count(3)->create();
        Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
                'state' => 'PENDING',
            ])->count(3)->create();

        $response = $this->json('GET', "/api/discussion/{$discussion->id}/messages", ['state' => 'pending']);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'data',
                'links',
                'meta'
            ]);

        $this->assertEquals(3, count($response['data']));
        $this->assertEquals('PENDING', $response['data'][0]['state']);
    }
    
    public function test_can_send_discussion_message()
    {
        Storage::fake('public');
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
            ])->create();

        $data = [
            'message' => $this->faker->paragraph,
            'files' => [
                UploadedFile::fake()->image('image1.png'),
                UploadedFile::fake()->image('image2.png'),
            ]
        ];

        $response = $this->postJson("/api/discussion/{$discussion->id}/message", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'discussionMessage',
            ]);

        $this->assertEquals(2, count($response['discussionMessage']['images']));
        Event::assertDispatched(NewDiscussionMessage::class);
    }
    
    public function test_can_create_discussion_message_and_request_and_send_notification()
    {
        Storage::fake('public');
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
            ->forUser()
            ->hasProfile(function($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
                'restricted' => true,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'user_id' => $facilitator->user_id,
            ])->create();

        $data = [
            'message' => $this->faker->paragraph,
            'files' => [
                UploadedFile::fake()->image('image1.png'),
                UploadedFile::fake()->image('image2.png'),
            ]
        ];

        $response = $this->postJson("/api/discussion/{$discussion->id}/message", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful', 
                'discussionMessage' => null,
            ]);

        Notification::assertSentTo($facilitator1->user, NewDiscussionMessageNotification::class);
        Event::assertNotDispatched(NewDiscussionMessage::class);
    }
    
    public function test_cannot_send_discussion_message_if_not_participant()
    {
        Storage::fake('public');
        Event::fake();

        $facilitator = Facilitator::factory()
            ->forUser()
            ->hasProfile(function($a, $b) {
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
            ])->create();

        $data = [
            'message' => $this->faker->paragraph,
            'files' => [
                UploadedFile::fake()->image('image1.png'),
                UploadedFile::fake()->image('image2.png'),
            ]
        ];

        $response = $this->postJson("/api/discussion/{$discussion->id}/message", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you are not a participant of this discussion",
            ]);
    }
    
    public function test_cannot_send_discussion_message_with_more_than_max_number_files()
    {
        Storage::fake('public');
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
            ])->create();

        $data = [
            'message' => $this->faker->paragraph,
            'files' => [
                UploadedFile::fake()->image('image1.png'),
                UploadedFile::fake()->image('image2.png'),
                UploadedFile::fake()->image('image2.png'),
                UploadedFile::fake()->image('image2.png'),
            ]
        ];

        $response = $this->postJson("/api/discussion/{$discussion->id}/message", $data);

        $maxFiles = MessageService::MAX_NUMBER_OF_FILES;
        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you cannot have more than {$maxFiles} files for a message.",
            ]);
    }

    public function test_can_delete_own_discussion_message_for_all_as_a_participant()
    {
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'user_id' => $facilitator->user_id,
            ])->create();
        $message = Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
            ])->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])->create();

        $message->images()->attach($image);

        $data = [
            'action' => 'delete'
        ];

        $response = $this->deleteJson("api/discussion/message/{$message->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        $this->assertFalse((bool) Message::find($message->id), 'message deleted');
        $this->assertFalse((bool) Image::find($image->id), 'image deleted');
        Event::assertDispatched(DeleteDiscussionMessage::class);
    }

    public function test_can_delete_own_discussion_message_for_self_as_a_participant()
    {
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'user_id' => $facilitator->user_id,
            ])->create();
        $message = Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
                'user_deletes' => [],
            ])->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])->create();

        $message->images()->attach($image);

        $data = [
            'action' => 'self'
        ];

        $response = $this->deleteJson("api/discussion/message/{$message->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        $this->assertTrue((bool) Message::find($message->id), 'message not deleted');
        $this->assertTrue((bool) Image::find($image->id), 'image not deleted');
        Event::assertDispatched(UpdateDiscussionMessage::class);
    }

    public function test_cannot_totally_delete_discussion_message_if_not_owner()
    {
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'user_id' => $facilitator->user_id,
            ])->create();
        $message = Message::factory()
            ->state([
                'fromable_type' => $facilitator1::class,
                'fromable_id' => $facilitator1->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
                'user_deletes' => [],
            ])->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $facilitator1::class,
                'addedby_id' => $facilitator1->id,
            ])->create();

        $message->images()->attach($image);

        $data = [
            'action' => 'delete'
        ];

        $response = $this->deleteJson("api/discussion/message/{$message->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you are not authorized to perform this action",
            ]);
    }

    public function test_can_delete_others_discussion_message_for_self_as_a_participant()
    {
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'user_id' => $facilitator->user_id,
            ])->create();
        $message = Message::factory()
            ->state([
                'fromable_type' => $facilitator1::class,
                'fromable_id' => $facilitator1->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
                'user_deletes' => [],
            ])->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $facilitator1::class,
                'addedby_id' => $facilitator1->id,
            ])->create();

        $message->images()->attach($image);

        $data = [
            'action' => 'self'
        ];

        $response = $this->deleteJson("api/discussion/message/{$message->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        $this->assertTrue((bool) Message::find($message->id), 'message not deleted');
        $this->assertTrue((bool) Image::find($image->id), 'image not deleted');
        Event::assertDispatched(UpdateDiscussionMessage::class);
    }

    public function test_can_delete_others_discussion_message_for_all_as_an_admin()
    {
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'user_id' => $facilitator->user_id,
                'state' => 'ADMIN'
            ])->create();
        $message = Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
                'user_deletes' => [],
            ])->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])->create();

        $message->images()->attach($image);

        $data = [
            'action' => 'all'
        ];

        $response = $this->deleteJson("api/discussion/message/{$message->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        $this->assertTrue((bool) Message::find($message->id), 'message not deleted');
        $this->assertTrue((bool) Image::find($image->id), 'image not deleted');
        Event::assertDispatched(UpdateDiscussionMessage::class);
    }

    public function test_cannot_delete_others_discussion_message_for_all_if_not_an_admin()
    {
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'user_id' => $facilitator->user_id,
            ])->create();
        $message = Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
                'user_deletes' => [],
            ])->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $facilitator::class,
                'addedby_id' => $facilitator->id,
            ])->create();

        $message->images()->attach($image);

        $data = [
            'action' => 'all'
        ];

        $response = $this->deleteJson("api/discussion/message/{$message->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you are not authorized to perform this action",
            ]);

    }

    public function test_cannot_delete_own_discussion_message_if_not_a_participant()
    {
        Event::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])->create();
        $message = Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
            ])->create();

        $data = [
            'action' => 'delete'
        ];

        $response = $this->deleteJson("api/discussion/message/{$message->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you are not authorized to perform this action",
            ]);
    }

    public function test_can_respond_to_contribution_request_for_message_to_be_accepted()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitators->first()::class,
                'raisedby_id' => $facilitators->first()->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'state' => "ADMIN",
                'user_id' => $facilitator->user_id,
            ])->create();

        $message = Message::factory()
            ->state([
                'fromable_type' => $facilitators->last()::class,
                'fromable_id' => $facilitators->last()->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
            ])->create();

        $request = Request::factory()
            ->state([
                'requestfrom_type' => $facilitators->last()::class,
                'requestfrom_id' => $facilitators->last()->id,
                'requestto_type' => $discussion->raisedby::class,
                'requestto_id' => $discussion->raisedby->id,
                'requestable_type' => $message::class,
                'requestable_id' => $message->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'messageId' => $message->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/discussion/contribution/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertEquals('ACCEPTED', Message::find($message->id)->state);
        $this->assertEquals('ACCEPTED', Request::find($request->id)->state);
        Event::assertDispatched(NewDiscussionMessage::class);
        Notification::assertSentTo(
            $message->fromable->user, 
            DiscussionContributionResponseNotification::class
        );
    }

    public function test_can_respond_to_contribution_request_for_message_to_be_declined()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitators->first()::class,
                'raisedby_id' => $facilitators->first()->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'state' => "ADMIN",
                'user_id' => $facilitator->user_id,
            ])->create();

        $message = Message::factory()
            ->state([
                'fromable_type' => $facilitators->last()::class,
                'fromable_id' => $facilitators->last()->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
            ])->create();

        $request = Request::factory()
            ->state([
                'requestfrom_type' => $facilitators->last()::class,
                'requestfrom_id' => $facilitators->last()->id,
                'requestto_type' => $discussion->raisedby::class,
                'requestto_id' => $discussion->raisedby->id,
                'requestable_type' => $message::class,
                'requestable_id' => $message->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'messageId' => $message->id,
            'action' => 'declined'
        ];

        $response = $this->postJson("api/discussion/contribution/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertEquals('DECLINED', Message::find($message->id)->state);
        $this->assertEquals('DECLINED', Request::find($request->id)->state);
        Event::assertNotDispatched(NewDiscussionMessage::class);
        Notification::assertNothingSent();
    }

    public function test_cannot_respond_to_contribution_request_if_not_an_admin()
    {
         $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])->create();
        $message = Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
            ])->create();

        $request = Request::factory()
            ->state([
                'requestfrom_type' => $facilitator::class,
                'requestfrom_id' => $facilitator->id,
                'requestto_type' => $discussion->raisedby::class,
                'requestto_id' => $discussion->raisedby->id,
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'messageId' => $message->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/discussion/contribution/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'you are not authorized to perform this action on this discussion'
            ]);
    }

    public function test_cannot_respond_to_contribution_request_without_valid_action()
    {
         $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->hasProfile([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()
            ->hasProfile(function ($a, $b){
                return [
                    'user_id' => $b['user_id']
                ];
            })->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
            ])
            ->hasParticipants([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'state' => "ADMIN",
                'user_id' => $facilitator->user_id,
            ])->create();
        $message = Message::factory()
            ->state([
                'fromable_type' => $facilitator::class,
                'fromable_id' => $facilitator->id,
                'messageable_type' => $discussion::class,
                'messageable_id' => $discussion->id,
            ])->create();

        $request = Request::factory()
            ->state([
                'requestfrom_type' => $facilitator::class,
                'requestfrom_id' => $facilitator->id,
                'requestto_type' => $discussion->raisedby::class,
                'requestto_id' => $discussion->raisedby->id,
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'messageId' => $message->id,
            'action' => 'wait'
        ];

        $response = $this->postJson("api/discussion/contribution/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, wait is not a valid response",
            ]);
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitators->first()::class,
                'raisedby_id' => $facilitators->first()->id,
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
                'requestfrom_type' => $discussion->raisedby::class,
                'requestfrom_id' => $discussion->raisedby->id,
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/discussion/invitation/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertNotNull($response['participant']);
        $this->assertEquals('ACCEPTED', Request::find($request->id)->state);
        Event::assertDispatched(NewDiscussionParticipant::class);
        Notification::assertSentTo(
            $facilitators->first()->user, 
            DiscussionInvitationResponseNotification::class
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitators->first()::class,
                'raisedby_id' => $facilitators->first()->id,
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
                'requestfrom_type' => $discussion->raisedby::class,
                'requestfrom_id' => $discussion->raisedby->id,
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'declined'
        ];

        $response = $this->postJson("api/discussion/invitation/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertNull($response['participant']);
        $this->assertEquals('DECLINED', Request::find($request->id)->state);
        Event::assertNotDispatched(NewDiscussionParticipant::class);
        Notification::assertNotSentTo(
            $facilitators->first()->user, 
            DiscussionInvitationResponseNotification::class
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitators->first()::class,
                'raisedby_id' => $facilitators->first()->id,
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
                'requestfrom_type' => $discussion->raisedby::class,
                'requestfrom_id' => $discussion->raisedby->id,
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'wait'
        ];

        $response = $this->postJson("api/discussion/invitation/response", $data);

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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitators->first()::class,
                'raisedby_id' => $facilitators->first()->id,
            ])->create();

        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitators->last()::class,
                'requestto_id' => $facilitators->last()->id,
                'requestfrom_type' => $discussion->raisedby::class,
                'requestfrom_id' => $discussion->raisedby->id,
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
                'state' => "PENDING",
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/discussion/invitation/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you are not authorized to respond to this request"
            ]);

        Event::assertNotDispatched(NewDiscussionParticipant::class);
        Notification::assertNothingSent();
    }

    public function test_can_accept_a_join_discussion_request()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
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
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/discussion/join/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertEquals('ACCEPTED', Request::find($request->id)->state);
        $this->assertEquals('ACTIVE', $discussion->getParticipantUsingAccount($learner)?->state);
        Event::assertDispatched(RemoveDiscussionPendingParticipant::class);
        Event::assertDispatched(NewDiscussionParticipant::class);
        Notification::assertSentTo($learner->user, DiscussionJoinResponseNotification::class);
    }

    public function test_can_decine_a_join_discussion_request()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
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
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'declined'
        ];

        $response = $this->postJson("api/discussion/join/response", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertEquals('DECLINED', Request::find($request->id)->state);
        $this->assertNull($discussion->getParticipantUsingAccount($learner));
        Event::assertDispatched(RemoveDiscussionPendingParticipant::class);
        Event::assertNotDispatched(NewDiscussionParticipant::class);
        Notification::assertSentTo($learner->user, DiscussionJoinResponseNotification::class);
    }

    public function test_cannot_respond_to_a_join_discussion_request_with_invalid_action()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
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
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'wait'
        ];

        $response = $this->postJson("api/discussion/join/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, wait is not a valid response",
            ]);

        $this->assertEquals('PENDING', Request::find($request->id)->state);
        $this->assertEquals('PENDING', $discussion->getParticipantUsingAccount($learner)?->state);
        Event::assertNotDispatched(RemoveDiscussionPendingParticipant::class);
        Event::assertNotDispatched(NewDiscussionParticipant::class);
        Notification::assertNotSentTo($learner->user, DiscussionJoinResponseNotification::class);
    }

    public function test_cannot_respond_to_a_join_discussion_request_which_is_not_a_discussion_request()
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

        $discussion = Message::factory()
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
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/discussion/join/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "request with id {$request->id} is not related to a discussion",
            ]);

        $this->assertEquals('PENDING', Request::find($request->id)->state);
        Event::assertNotDispatched(RemoveDiscussionPendingParticipant::class);
        Event::assertNotDispatched(NewDiscussionParticipant::class);
        Notification::assertNotSentTo($learner->user, DiscussionJoinResponseNotification::class);
    }

    public function test_cannot_respond_to_a_join_discussion_if_not_admin()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
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
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/discussion/join/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'you are not authorized to perform this action on this discussion',
            ]);

        $this->assertEquals('PENDING', Request::find($request->id)->state);
        $this->assertEquals('PENDING', $discussion->getParticipantUsingAccount($learner)?->state);
        Event::assertNotDispatched(RemoveDiscussionPendingParticipant::class);
        Event::assertNotDispatched(NewDiscussionParticipant::class);
        Notification::assertNotSentTo($learner->user, DiscussionJoinResponseNotification::class);
    }

    public function test_cannot_respond_to_a_join_discussion_request_without_a_pending_participant()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
            ])->create();
        
        $request = Request::factory()
            ->state([
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestfrom_type' => $learner::class,
                'requestfrom_id' => $learner->id,
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
            ])->create();

        $data = [
            'requestId' => $request->id,
            'action' => 'accepted'
        ];

        $response = $this->postJson("api/discussion/join/response", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "no participant was found for this request."
            ]);

        $this->assertEquals('PENDING', Request::find($request->id)->state);
        Event::assertNotDispatched(RemoveDiscussionPendingParticipant::class);
        Event::assertNotDispatched(NewDiscussionParticipant::class);
        Notification::assertNotSentTo($learner->user, DiscussionJoinResponseNotification::class);
    }

    public function test_can_join_public_discussion()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $learner::class,
                'raisedby_id' => $learner->id,
                'type' => 'PUBLIC'
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
        ];

        $response = $this->postJson("api/discussion/{$discussion->id}/join", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertEquals('ACTIVE', $discussion->getParticipantUsingAccount($facilitator)?->state);
        Event::assertDispatched(NewDiscussionParticipant::class);
        Notification::assertSentTo($discussion->raisedby->user, DiscussionJoinNotification::class);
    }

    public function test_can_send_join_request_to_private_discussion()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $learner::class,
                'raisedby_id' => $learner->id,
                'type' => 'PRIVATE'
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
        ];

        $response = $this->postJson("api/discussion/{$discussion->id}/join", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful', 'participant' => null
            ]);

        $this->assertEquals('PENDING', $discussion->getParticipantUsingAccount($facilitator)?->state);
        Event::assertDispatched(NewDiscussionPendingParticipant::class);
        Notification::assertSentTo($discussion->raisedby->user, DiscussionJoinRequestNotification::class);
    }

    public function test_cannot_join_public_discussion_as_admin_or_participant()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PUBLIC'
            ])->create();

        $data = [
            'account' => "$learner",
            'accountId' => $learner->id,
        ];

        $response = $this->postJson("api/discussion/{$discussion->id}/join", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, an admin or participant cannot perform this action."
            ]);

        $this->assertNull($discussion->getParticipantUsingAccount($learner)?->state);
        Event::assertNotDispatched(NewDiscussionParticipant::class);
        Notification::assertNotSentTo($discussion->raisedby->user, DiscussionJoinNotification::class);
    }

    public function test_cannot_send_join_request_to_private_discussion_when_having_pending_join_request_to_same_discussion()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $learner::class,
                'raisedby_id' => $learner->id,
                'type' => 'PRIVATE'
            ])->create();

        $request = Request::factory()
            ->state([
                'requestfrom_type' => $facilitator::class,
                'requestfrom_id' => $facilitator->id,
                'requestable_type' => $discussion::class,
                'requestable_id' => $discussion->id,
                'state' => 'PENDING',
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
        ];

        $response = $this->postJson("api/discussion/{$discussion->id}/join", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you already have a pending request to join this discussion"
            ]);

        Event::assertNotDispatched(NewDiscussionPendingParticipant::class);
        Notification::assertNotSentTo($discussion->raisedby->user, DiscussionJoinRequestNotification::class);
    }

    public function test_cannot_join_discussion_with_an_unallowed_account()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $learner::class,
                'raisedby_id' => $learner->id,
                'type' => 'PRIVATE',
                'allowed' => "LEARNERS"
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
        ];

        $response = $this->postJson("api/discussion/{$discussion->id}/join", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, facilitator account is not allowed. please use a learner account",
            ]);

        Event::assertNotDispatched(NewDiscussionPendingParticipant::class);
        Notification::assertNotSentTo($discussion->raisedby->user, DiscussionJoinRequestNotification::class);
    }

    public function test_cannot_join_discussion_when_already_participating_with_different_account()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
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

        $response = $this->postJson("api/discussion/{$discussion->id}/join", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you are already participating with your learner account.",
            ]);

        Event::assertNotDispatched(NewDiscussionPendingParticipant::class);
        Notification::assertNotSentTo($discussion->raisedby->user, DiscussionJoinRequestNotification::class);
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $data = [
            'account' => "$learner",
            'accountId' => $learner->id,
            'discussionId' => $discussion->id
        ];

        $response = $this->postJson("api/discussion/invitation", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'pendingParticipant'
            ]);

        $this->assertNotNull($discussion->refresh()->getPendingParticipantUsingAccount($learner));
        Event::assertDispatched(NewDiscussionPendingParticipant::class);
        Notification::assertSentTo($learner->user, DiscussionInvitationNotification::class);
    }
    
    public function test_cannot_invite_participant_if_not_admin()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $data = [
            'account' => "$learner",
            'accountId' => $learner->id,
            'discussionId' => $discussion->id
        ];

        $response = $this->postJson("api/discussion/invitation", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'you are not authorized to perform this action on this discussion',
            ]);

        $this->assertNull($discussion->refresh()->getPendingParticipantUsingAccount($learner));
        Event::assertNotDispatched(NewDiscussionPendingParticipant::class);
        Notification::assertNotSentTo($learner->user, DiscussionInvitationNotification::class);
    }
    
    public function test_cannot_invite_participant_with_an_unallowed_account()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE',
                'allowed' => "FACILITATORS"
            ])->create();

        $data = [
            'account' => "$learner",
            'accountId' => $learner->id,
            'discussionId' => $discussion->id
        ];

        $response = $this->postJson("api/discussion/invitation", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, learner account is not allowed. please use a facilitator account",
            ]);

        $this->assertNull($discussion->refresh()->getPendingParticipantUsingAccount($learner));
        Event::assertNotDispatched(NewDiscussionPendingParticipant::class);
        Notification::assertNotSentTo($learner->user, DiscussionInvitationNotification::class);
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
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
            'discussionId' => $discussion->id
        ];

        $response = $this->postJson("api/discussion/invitation", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you are already participating with your facilitator account.",
            ]);

        $this->assertNull($discussion->refresh()->getPendingParticipantUsingAccount($learner));
        Event::assertNotDispatched(NewDiscussionPendingParticipant::class);
        Notification::assertNotSentTo($learner->user, DiscussionInvitationNotification::class);
    }

    public function test_can_get_participants_when_an_admin()
    {
        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();
        $discussion1 = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        for ($i=0; $i < 6; $i++) { 
            $learner = Learner::factory()
                ->forUser()->create();

            Participant::factory()
                ->state([
                    'state' => 'ACTIVE',
                    'user_id' => $learner->user_id,
                    'participation_type' => $discussion::class,
                    'participation_id' => $discussion->id,
                    'accountable_id' => $learner->id,
                    'accountable_type' => $learner::class,
                ])->create();

            Participant::factory()
                ->state([
                    'state' => 'ACTIVE',
                    'user_id' => $learner->user_id,
                    'participation_type' => $discussion1::class,
                    'participation_id' => $discussion1->id,
                    'accountable_id' => $learner->id,
                    'accountable_type' => $learner::class,
                ])->create();
        }

        $response = $this->getJson("api/discussion/{$discussion->id}/participants");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'data', 'meta', 'links'
            ]);

        $this->assertEquals(7, count($response['data']));
    }

    public function test_can_get_participants_when_not_admin()
    {
        $facilitator = Facilitator::factory()
            ->forUser()->create();

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();
        $discussion1 = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        for ($i=0; $i < 6; $i++) { 
            $learner = Learner::factory()
                ->forUser()->create();

            Participant::factory()
                ->state([
                    'state' => 'ACTIVE',
                    'user_id' => $learner->user_id,
                    'participation_type' => $discussion::class,
                    'participation_id' => $discussion->id,
                    'accountable_id' => $learner->id,
                    'accountable_type' => $learner::class,
                ])->create();

            Participant::factory()
                ->state([
                    'state' => 'ACTIVE',
                    'user_id' => $learner->user_id,
                    'participation_type' => $discussion1::class,
                    'participation_id' => $discussion1->id,
                    'accountable_id' => $learner->id,
                    'accountable_type' => $learner::class,
                ])->create();
        }

        $response = $this->getJson("api/discussion/{$discussion->id}/participants");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'data', 'meta', 'links'
            ]);

        $this->assertEquals(7, count($response['data']));
    }

    public function test_can_update_discussion_participant_when_admin_with_valid_action()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $discussion->participants()
            ->create([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'user_id' => $facilitator1->user_id,
                'state' => 'ACTIVE'
            ]);

        $data = [
            'action' => 'banned'
        ];

        $response = $this->putJson("api/discussion/participant/{$participant->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'participant'
            ]);

        $this->assertEquals('BANNED', $discussion->refresh()->getParticipantUsingAccount($facilitator1)->state);
        Event::assertDispatched(UpdatedDiscussionParticipant::class);
        Notification::assertSentTo($facilitator1->user, UpdateDiscussionParticipantNotification::class);
    }

    public function test_cannot_update_discussion_participant_when_not_admin_with_valid_action()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $discussion->participants()
            ->create([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'user_id' => $facilitator1->user_id,
                'state' => 'ACTIVE'
            ]);

        $data = [
            'action' => 'banned'
        ];

        $response = $this->putJson("api/discussion/participant/{$participant->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'you are not authorized to perform this action on this discussion'
            ]);

        $this->assertEquals('ACTIVE', $discussion->refresh()->getParticipantUsingAccount($facilitator1)->state);
        Event::assertNotDispatched(UpdatedDiscussionParticipant::class);
        Notification::assertNotSentTo($facilitator1->user, UpdateDiscussionParticipantNotification::class);
    }

    public function test_cannot_update_discussion_participant_when_an_admin_with_invalid_action()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $discussion->participants()
            ->create([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'user_id' => $facilitator1->user_id,
                'state' => 'ACTIVE'
            ]);

        $data = [
            'action' => 'wait'
        ];

        $response = $this->putJson("api/discussion/participant/{$participant->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, the action wait is invalid."
            ]);

        $this->assertEquals('ACTIVE', $discussion->refresh()->getParticipantUsingAccount($facilitator1)->state);
        Event::assertNotDispatched(UpdatedDiscussionParticipant::class);
        Notification::assertNotSentTo($facilitator1->user, UpdateDiscussionParticipantNotification::class);
    }

    public function test_can_delete_participant_when_an_admin()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $discussion->participants()
            ->create([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'user_id' => $facilitator1->user_id,
                'state' => 'ACTIVE'
            ]);

        $response = $this->deleteJson("api/discussion/participant/{$participant->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful'
            ]);

        $this->assertNull($discussion->getParticipantUsingAccount($facilitator1));
        Event::assertDispatched(RemoveDiscussionParticipant::class);
        Notification::assertSentTo($facilitator1->user, RemoveDiscussionParticipantNotification::class);
    }

    public function test_cannot_delete_participant_when_not_an_admin()
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $discussion->participants()
            ->create([
                'accountable_type' => $facilitator1::class,
                'accountable_id' => $facilitator1->id,
                'user_id' => $facilitator1->user_id,
                'state' => 'ACTIVE'
            ]);

        $response = $this->deleteJson("api/discussion/participant/{$participant->id}");

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'you are not authorized to perform this action on this discussion'
            ]);

        $this->assertNotNull($discussion->getParticipantUsingAccount($facilitator1));
        Event::assertNotDispatched(RemoveDiscussionParticipant::class);
        Notification::assertNotSentTo($facilitator1->user, RemoveDiscussionParticipantNotification::class);
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator1::class,
                'raisedby_id' => $facilitator1->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $discussion->participants()
            ->create([
                'accountable_type' => $facilitator::class,
                'accountable_id' => $facilitator->id,
                'user_id' => $facilitator->user_id,
                'state' => 'ACTIVE'
            ]);

        $response = $this->deleteJson("api/discussion/participant/{$participant->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful'
            ]);

        $this->assertNull($discussion->getParticipantUsingAccount($facilitator));
        Event::assertDispatched(RemoveDiscussionParticipant::class);
        Notification::assertSentTo($discussion->raisedby->user, RemoveDiscussionParticipantNotification::class);
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

        $discussion = Discussion::factory()
            ->state([
                'raisedby_type' => $facilitator::class,
                'raisedby_id' => $facilitator->id,
                'type' => 'PRIVATE'
            ])->create();

        $participant = $discussion->participants()
            ->create([
                'accountable_type' => $learner::class,
                'accountable_id' => $learner->id,
                'user_id' => $learner->user_id,
                'state' => 'ACTIVE'
            ]);

        $response = $this->deleteJson("api/discussion/participant/{$participant->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful'
            ]);

        $this->assertNull($discussion->getParticipantUsingAccount($facilitator));
        Event::assertDispatched(RemoveDiscussionParticipant::class);
        Notification::assertSentTo($discussion->raisedby->user, RemoveDiscussionParticipantNotification::class);
    }
}
