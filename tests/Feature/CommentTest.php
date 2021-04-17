<?php

namespace Tests\Feature;

use App\Events\DeleteComment;
use App\Events\NewComment;
use App\Events\UpdateComment;
use App\User;
use App\YourEdu\Comment;
use App\YourEdu\Facilitator;
use App\YourEdu\Image;
use App\YourEdu\Learner;
use App\YourEdu\Post;
use App\YourEdu\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CommentTest extends TestCase
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
        DB::table('images')->delete();
    }

    public function test_can_create_comment()
    {
        Storage::fake('public');
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
            'body' => 'this is the comment',
            'file' => UploadedFile::fake()->image('new_image.png')
        ];

        $response = $this->postJson('/api/comment', $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'comment' => [
                    'body',
                    'images' => ['*' => ['id', 'url']]
                ]
            ]);

        Event::assertDispatched(NewComment::class);
    }

    public function test_cannot_create_comment_without_body_or_files()
    {
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

        $response = $this->postJson('/api/comment', $data);

        $response
            ->dump()
            ->assertJson([
                'message' => "unsuccessful. there is nothing to add as comment."
            ])
            ->assertStatus(422);
    }

    public function test_can_update_comment()
    {
        Storage::fake('public');
        Event::fake();
        
        $learner = Learner::factory()
            ->state([
                'user_id' => $this->user->id
            ])->create();
        
        $post = Post::factory()
            ->for(Facilitator::factory()
                ->for(User::factory()), 'addedby')
            ->create();

        $comment = Comment::factory()
            ->state([
                'commentedby_type' => $learner::class,
                'commentedby_id' => $learner->id,
            ])->create();

        $post->comments()->save($comment);

        $data = [
            'account' => $learner->accountType,
            'accountId' => (string) $learner->id,
            'body' => 'this is the comment',
            'file' => UploadedFile::fake()->image('new_image.png')
        ];

        $response = $this->putJson("/api/comment/{$comment->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure([
                'comment' => [
                    'body',
                    'images' => ['*' => ['id', 'url']]
                ]
            ]);

        Event::assertDispatched(UpdateComment::class);
    }

    public function test_can_delete_comment()
    {
        Storage::fake('public');
        Event::fake();
        
        $image = Image::factory()->create();

        $school = School::factory()
            ->state([
                'owner_id' => $this->user->id,
            ])->create();
        
        $post = Post::factory()
            ->for(Facilitator::factory()
                ->for(User::factory()), 'addedby')
            ->create();

        $comment = Comment::factory()
            ->state([
                'commentedby_type' => $school::class,
                'commentedby_id' => $school->id,
            ])->create();
        $comment->images()->attach($image);
        $comment->save();
        $post->comments()->save($comment);
        
        $this->assertTrue(Image::exists($image->id));

        $response = $this->deleteJson("/api/comment/{$comment->id}");

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJsonStructure(['message']);

        $this->assertFalse(Image::exists($image->id));

        Event::assertDispatched(DeleteComment::class);
    }
}
