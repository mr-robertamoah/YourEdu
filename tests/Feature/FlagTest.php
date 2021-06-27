<?php

namespace Tests\Feature;

use App\Events\DeleteFlag;
use App\Events\NewFlag;
use App\User;
use App\YourEdu\Facilitator;
use App\YourEdu\Flag;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class FlagTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user, 'api');
        DB::table('facilitators')->delete();
        DB::table('professionals')->delete();
        DB::table('admins')->delete();
        DB::table('schools')->delete();
        DB::table('learners')->delete();
        DB::table('parent_models')->delete();
        // DB::table('users')->delete();
        DB::table('comments')->delete();
    }

    public function test_can_flag_item()
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

        $response = $this->postJson('/api/flag', $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'flag' => [
                    'id',
                ]
            ]);

        Event::assertDispatched(NewFlag::class);
    }

    public function test_can_flag_item_as_parent_and_for_wards()
    {
        Event::fake();

        $parent = ParentModel::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->has(
                Learner::factory()->forUser()->count(2),
                'wards'
            )->create();

        $post = Post::factory()
            ->for(
                Facilitator::factory()->for(User::factory()),
                'addedby'
            )
            ->create();

        $data = [
            'item' => 'post',
            'itemId' => (string) $post->id,
            'account' => $parent->accountType,
            'accountId' => (string) $parent->id,
        ];

        $response = $this->postJson('/api/flag', $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'flag' => [
                    'id',
                ]
            ]);

        Event::assertDispatched(NewFlag::class);
        $this->assertCount(1, $parent->flagsRaised);
        $this->assertCount(1, $parent->wards->first()->flagsRaised);
    }

    public function test_can_unflag_item()
    {
        Event::fake();

        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();

        $flag = flag::factory()
            ->state([
                'user_id' => $this->user->id
            ])
            ->create();

        $post = Post::factory()
            ->for(Facilitator::factory()
                ->for(User::factory()), 'addedby')
            ->create();

        $flag->flaggable()->associate($post);
        $flag->save();

        $data = [];

        $response = $this->deleteJson("/api/flag/{$flag->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure(['message']);

        Event::assertDispatched(DeleteFlag::class);
    }

    public function test_cannot_unflag_item_added_by_another_user()
    {
        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();

        $flag = Flag::factory()
            ->for(User::factory())
            ->create();

        $post = Post::factory()
            ->for(Facilitator::factory()
                ->for(User::factory()), 'addedby')
            ->create();

        $flag->flaggable()->associate($post);
        $flag->save();

        $data = [];

        $response = $this->deleteJson("/api/flag/{$flag->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJsonStructure(['message']);
    }
}
