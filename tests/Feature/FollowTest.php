<?php

namespace Tests\Feature;

use App\Events\DeleteFollow;
use App\Events\NewFollow;
use App\Events\NewFollowBack;
use App\User;
use App\YourEdu\Facilitator;
use App\YourEdu\Follow;
use App\YourEdu\Professional;
use App\YourEdu\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class FollowTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'api');

        DB::table('follows')->delete();
        DB::table('facilitators')->delete();
        DB::table('professionals')->delete();
        DB::table('learners')->delete();
    }
    
    public function test_can_follow()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
        ];

        $response = $this->postJson("api/follow/{$professional->accountType}/{$professional->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message', 'follow', 'following'
            ]);

        $this->assertEquals(
            1, 
            Follow::query()
                ->whereFollowedby($facilitator)
                ->whereFollowable($professional)
                ->count()
        );

        Event::assertDispatched(NewFollow::class);
    }
    
    public function test_cannot_follow_own_account()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
        ];

        $response = $this->postJson("api/follow/{$professional->accountType}/{$professional->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "unsuccessful. you cannot follow one of your own accounts"
            ]);
    }
    
    public function test_cannot_follow_an_account_being_followed_by_you()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional1 = Professional::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();

        Follow::factory()
            ->state([
                'followedby_type' => $professional1::class,
                'followedby_id' => $professional1->id,
                'followable_type' => $professional::class,
                'followable_id' => $professional->id,
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
        ];

        $response = $this->postJson("api/follow/{$professional->accountType}/{$professional->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "unsuccessful. you already follow this account"
            ]);
    }
    
    public function test_cannot_follow_an_account_with_pending_follow_request_from_you()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional1 = Professional::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();

        $follow = Follow::factory()->create();
        Request::factory()
            ->state([
                'requestfrom_type' => $professional1::class,
                'requestfrom_id' => $professional1->id,
                'requestto_type' => $professional::class,
                'requestto_id' => $professional->id,
                'requestable_type' => $follow::class,
                'requestable_id' => $follow->id,
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
        ];

        $response = $this->postJson("api/follow/{$professional->accountType}/{$professional->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "unsuccessful. you have a pending request to this account."
            ]);
    }
    
    public function test_can_unfollow()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();

        $follow = Follow::factory()
            ->state([
                'followedby_type' => $facilitator::class,
                'followedby_id' => $facilitator->id,
                'followable_type' => $professional::class,
                'followable_id' => $professional->id,
            ])->create();

        $data = [
            
        ];

        $response = $this->deleteJson("api/follow/{$follow->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertNull(Follow::find($follow->id));
        Event::assertDispatched(DeleteFollow::class);
    }
    
    public function test_cannot_unfollow_when_account_is_not_followedby()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()->create();
            
        $professional = Professional::factory()
            ->forUser()->create();

        $follow = Follow::factory()
            ->state([
                'followedby_type' => $facilitator1::class,
                'followedby_id' => $facilitator1->id,
                'followable_type' => $professional::class,
                'followable_id' => $professional->id,
            ])->create();

        $data = [
            
        ];

        $response = $this->deleteJson("api/follow/{$follow->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "charlie you cannot unfollow because you are not the one following 😏",
            ]);
    }
    
    public function test_can_follow_back()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();

        $follows = Follow::factory()->count(2)->create();

        $follows->last()->followedby()->associate($professional);
        $follows->last()->followedby()->associate($professional);
        $follows->last()->save();

        $request = Request::factory()
            ->state([
                'requestfrom_type' => $professional::class,
                'requestfrom_id' => $professional->id,
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestable_type' => $follows->first()::class,
                'requestable_id' => $follows->first()->id,
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'otherAccount' => "$professional",
            'otherAccountId' => $professional->id,
        ];

        $response = $this->postJson("api/follow/request/accept", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertEquals('ACCEPTED', $request->refresh()->state);
        $this->assertEquals(
            1, 
            Follow::query()
                ->whereFollowable($professional)
                ->whereFollowedby($facilitator)
                ->count()
        );
        Event::assertDispatched(NewFollowBack::class);
    }
    
    public function test_cannot_follow_back_when_not_requestto()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();

        $facilitator1 = Facilitator::factory()
            ->forUser()->create();
            
        $professional = Professional::factory()
            ->forUser()->create();

        $follows = Follow::factory()->count(2)->create();

        $follows->last()->followedby()->associate($professional);
        $follows->last()->followedby()->associate($professional);
        $follows->last()->save();

        $request = Request::factory()
            ->state([
                'requestfrom_type' => $professional::class,
                'requestfrom_id' => $professional->id,
                'requestto_type' => $facilitator1::class,
                'requestto_id' => $facilitator1->id,
                'requestable_type' => $follows->first()::class,
                'requestable_id' => $follows->first()->id,
            ])->create();

        $data = [
            'account' => "$facilitator1",
            'accountId' => $facilitator1->id,
            'otherAccount' => "$professional",
            'otherAccountId' => $professional->id,
        ];

        $response = $this->postJson("api/follow/request/accept", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "sorry 😞, you do not own the account {$facilitator1->accountType} with id {$facilitator1->id}",
            ]);
    }
    
    public function test_can_decline_follow_back()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();

        $follows = Follow::factory()->count(2)->create();

        $follows->last()->followedby()->associate($professional);
        $follows->last()->followedby()->associate($professional);
        $follows->last()->save();

        $request = Request::factory()
            ->state([
                'requestfrom_type' => $professional::class,
                'requestfrom_id' => $professional->id,
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestable_type' => $follows->first()::class,
                'requestable_id' => $follows->first()->id,
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'otherAccount' => "$professional",
            'otherAccountId' => $professional->id,
        ];

        $response = $this->postJson("api/follow/request/decline", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertEquals('DECLINED', $request->refresh()->state);
    }
    
    public function test_can_decline_follow_back_if_request_is_not_to_your_account()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->forUser()->create();
            
        $professional = Professional::factory()
            ->forUser()->create();

        $follows = Follow::factory()->count(2)->create();

        $follows->last()->followedby()->associate($professional);
        $follows->last()->followedby()->associate($professional);
        $follows->last()->save();

        $request = Request::factory()
            ->state([
                'requestfrom_type' => $professional::class,
                'requestfrom_id' => $professional->id,
                'requestto_type' => $facilitator::class,
                'requestto_id' => $facilitator->id,
                'requestable_type' => $follows->first()::class,
                'requestable_id' => $follows->first()->id,
            ])->create();

        $data = [
            'account' => "$facilitator",
            'accountId' => $facilitator->id,
            'otherAccount' => "$professional",
            'otherAccountId' => $professional->id,
        ];

        $response = $this->postJson("api/follow/request/decline", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "charlie you cannot decline this request because it is not for you 😏",
            ]);
    }
    
    public function test_can_get_followers()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();

        $follows = Follow::factory()
            ->state([
                'followedby_type' => $professional::class,
                'followedby_id' => $professional->id,
                'followable_type' => $facilitator::class,
                'followable_id' => $facilitator->id,
            ])->count(6)->create();

        $response = $this->getJson("api/follow/user/followers");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'data'
            ]);

        $this->assertEquals(6, count($response['data']));
    }
    
    public function test_can_get_followings()
    {
        Event::fake();
        Notification::fake();

        $facilitator = Facilitator::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $professional = Professional::factory()
            ->forUser()->create();

        $follows = Follow::factory()
            ->state([
                'followedby_type' => $facilitator::class,
                'followedby_id' => $facilitator->id,
                'followable_type' => $professional::class,
                'followable_id' => $professional->id,
            ])->count(6)->create();

        $response = $this->getJson("api/follow/user/followings");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'data'
            ]);

        $this->assertEquals(6, count($response['data']));
    }
}
