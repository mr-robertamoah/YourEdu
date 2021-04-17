<?php

namespace Tests\Feature;

use App\Events\DeleteSave;
use App\Events\NewSave;
use App\User;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\Post;
use App\YourEdu\Save;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class SaveTest extends TestCase
{
    
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
    
    public function test_can_save_item()
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

        $response = $this->postJson('/api/save', $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'save' => [
                    'id',
                ]
            ]);

        Event::assertDispatched(NewSave::class);
    }
    
    public function test_can_unsave_item()
    {
        Event::fake();
        
        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $save = Save::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();
        
        $post = Post::factory()
            ->for(Facilitator::factory()
                ->for(User::factory()), 'addedby')
            ->create();

        $save->saveable()->associate($post);
        $save->save();

        $data = [

        ];

        $response = $this->deleteJson("/api/save/{$save->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure(['message']);

        Event::assertDispatched(DeleteSave::class);
    }
    
    public function test_cannot_unsave_item_added_by_another_user()
    {
        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
            
        $save = Save::factory()
            ->for(User::factory())
            ->create();
        
        $post = Post::factory()
            ->for(Facilitator::factory()
                ->for(User::factory()), 'addedby')
            ->create();

        $save->saveable()->associate($post);
        $save->save();

        $data = [

        ];

        $response = $this->deleteJson("/api/save/{$save->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJsonStructure(['message']);
    }
}
