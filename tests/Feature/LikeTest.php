<?php

namespace Tests\Feature;

use App\Events\DeleteLike;
use App\Events\NewLike;
use App\User;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\Like;
use App\YourEdu\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use WithFaker;
    
    protected function setUp() : void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'api');
        DB::table('facilitators')->delete();
        DB::table('professionals')->delete();
        DB::table('admins')->delete();
        DB::table('schools')->delete();
        DB::table('learners')->delete();
        // DB::table('users')->delete();
        DB::table('comments')->delete();
    }
    
    public function test_can_like_item()
    {
        Event::fake();
        
        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
        
        $post = Post::factory()
            ->for(Facilitator::factory()
                ->for(User::factory()), 'addedby')
            ->create();

        $data = [
            'item' => 'post',
            'itemId' => (string) $post->id,
            'account' => $learner->accountType,
            'accountId' => (string) $learner->id,
        ];

        $response = $this->postJson('/api/like', $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'like' => [
                    'id',
                ]
            ]);

        Event::assertDispatched(NewLike::class);
    }
    
    public function test_can_unlike_item()
    {
        Event::fake();
        
        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $like = Like::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();
        
        $post = Post::factory()
            ->for(Facilitator::factory()
                ->for(User::factory()), 'addedby')
            ->create();

        $like->likeable()->associate($post);
        $like->save();

        $data = [

        ];

        $response = $this->deleteJson("/api/like/{$like->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure(['message']);

        Event::assertDispatched(DeleteLike::class);
    }
    
    public function test_cannot_unlike_item_added_by_another_user()
    {
        Event::fake();
        
        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $like = Like::factory()
            ->for(User::factory())
            ->create();
        
        $post = Post::factory()
            ->for(Facilitator::factory()
                ->for(User::factory()), 'addedby')
            ->create();

        $like->likeable()->associate($post);
        $like->save();

        $data = [

        ];

        $response = $this->deleteJson("/api/like/{$like->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJsonStructure(['message']);
    }
}
