<?php

namespace Tests\Feature;

use App\Events\DeletePost;
use App\User;
use App\YourEdu\Activity;
use App\YourEdu\Admin;
use App\YourEdu\Book;
use App\YourEdu\Facilitator;
use App\YourEdu\Image;
use App\YourEdu\Learner;
use App\YourEdu\Lesson;
use App\YourEdu\Link;
use App\YourEdu\ParentModel;
use App\YourEdu\Poem;
use App\YourEdu\PoemSection;
use App\YourEdu\Post;
use App\YourEdu\Professional;
use App\YourEdu\Question;
use App\YourEdu\Riddle;
use App\YourEdu\School;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use WithFaker;

    private $user;

    public function setUp() : void
    {
        parent::setUp();

        if (!Schema::hasTable('users')) {
            Artisan::call('migrate:fresh');
        }
        DB::table('poem_sections')->delete();
        DB::table('images')->delete();
        DB::table('links')->delete();

        $this->user = User::factory()->create();

        $this->actingAs($this->user,'api');
    }

    public function tearDown() : void
    {
        DB::table('users')->delete();
        DB::table('posts')->delete();
        DB::table('links')->delete();
        DB::table('learners')->delete();
        DB::table('facilitators')->delete();
        DB::table('parent_models')->delete();
        DB::table('admins')->delete();
        DB::table('schools')->delete();
        DB::table('images')->delete();
        DB::table('questions')->delete();
        DB::table('books')->delete();
        DB::table('activities')->delete();
        DB::table('activity_tracks')->delete();
        DB::table('riddles')->delete();
        DB::table('poems')->delete();
        DB::table('poem_sections')->delete();
        DB::table('lessons')->delete();
        DB::table('possible_answers')->delete();
        parent::tearDown();        
    }

    public function test_can_create_post_without_post_type_and_files()
    {
        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $this->assertDatabaseCount('learners',1);

        $data = [
            'content' => $this->faker->sentence,
            'account' =>  $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->postJson('/api/post', $data);
        
        $this->assertDatabaseCount('posts',2);

        $this->assertDatabaseCount('images',0);

        $response->assertJson([
            'message' => 'successful',
            'post'=> [
                'content'=> $data['content'],
                'isPost'=> true,
            ]
        ]);
    }

    public function test_cannot_create_post_without_required_data()
    {
        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $data = [
            'content' => "",
            'account' =>  $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->postJson('/api/post', $data);

        $response
            ->assertJson([
                'message' => "a post requires a body and/or files",
            ])
            ->assertStatus(422);
    }

    public function test_can_create_post_with_files_but_without_post_type()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $this->assertDatabaseCount('learners',1);

        $file = UploadedFile::fake()->image('new_image.jpg');

        $data = [
            'content' => $this->faker->sentence,
            'account' =>  $account->accountType,
            'accountId' => $account->id,
            'file'=> [$file]
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();
        $this->assertDatabaseCount('posts',2);

        $this->assertDatabaseCount('images',1);
        
        $response
            ->assertJsonMissingValidationErrors()
            ->assertSuccessful()
            ->assertJson([
                'post'=> [
                    'content'=> $data['content'],
                    'isPost'=> true,
                    'images' => [['name' => 'new_image.jpg']]
                ]
            ]);
        
        $string = $response['post']['images'][0]['url'];

        Storage::disk('public')->assertExists(
            str_replace(
                substr($string, 0 , 24),
                '',
                $string
            )
        );
    }

    public function test_validation_of_required_data_for_creating_post()
    {

        $data = [
            
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response->assertJsonValidationErrors(['account', 'accountId']);
    }

    public function test_can_create_post_with_post_type_of_question()
    {
        $account = Facilitator::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $this->assertDatabaseCount('facilitators',1);

        $data = [
            'type' => 'question',
            "account" => $account->accountType,
            'accountId' => $account->id,
            'score' => 120,
            'question' => $this->faker->sentence,
            'published' => now(),
            'possibleAnswers' => json_encode([
                (object) ['option' => true, 'position'=> 1],
                (object) ['option' => false, 'position'=> 2],
            ])
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response
            ->assertSuccessful()
            ->assertJson([
                'post' => [
                    'content' => null,
                    'typeName' => 'question',
                    'isPost' => true,
                    'type' => [
                        ['question' => $data['question']]
                    ],
                ]
            ]);

        $this->assertEquals(
            2, 
            count($response['post']['type'][0]['possible_answers'])
        );
    }

    public function test_can_create_post_with_post_type_of_question_with_files()
    {
        Storage::fake('public');

        $account = Facilitator::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $this->assertDatabaseCount('facilitators',1);

        $data = [
            'type' => 'question',
            "account" => $account->accountType,
            'accountId' => $account->id,
            'score' => 120,
            'question' => $this->faker->sentence,
            'published' => now(),
            'possibleAnswers' => json_encode([
                (object) ['option' => 'one', 'position'=> 1],
                (object) ['option' => 'two', 'position'=> 2],
                (object) ['option' => 'three', 'position'=> 3],
            ]),
            'typeFiles' => [
                UploadedFile::fake()->image('new_image1.png'),
                UploadedFile::fake()->image('new_image2.png'),
            ]
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response
            ->assertSuccessful()
            ->assertJson([
                'post' => [
                    'content' => null,
                    'typeName' => 'question',
                    'isPost' => true,
                    'type' => [
                        [
                            'question' => $data['question'],
                            'scoreOver' => 100
                        ]
                    ],
                ]
            ]);

        $this->assertEquals(
            3, 
            count($response['post']['type'][0]['possible_answers'])
        );

        $string1 = $response['post']['type'][0]['images'][0]['url'];
        $string2 = $response['post']['type'][0]['images'][1]['url'];

        Storage::disk('public')->assertExists(
            str_replace(
                substr($string1, 0 , 24),
                '',
                $string1
            )
        );
        Storage::disk('public')->assertExists(
            str_replace(
                substr($string2, 0 , 24),
                '',
                $string2
            )
        );
    }

    public function test_validation_of_required_data_for_creating_post_type_question()
    {

        $data = [
            'type' => 'question'
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response->assertJsonValidationErrors(['account', 'accountId','question']);
    }

    public function test_can_create_post_with_post_type_of_poem()
    {
        $account = Professional::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $this->assertDatabaseCount('professionals',1);

        $data = [
            'type' => 'poem',
            "account" => $account->accountType,
            'accountId' => $account->id,
            'authorNames' => implode(', ', [
                $this->faker->name,
                $this->faker->name,
            ]),
            'title' => $this->faker->sentence,
            'about' => $this->faker->paragraph,
            'published' => now(),
            'sections' => json_encode([
                (object) ['body' => $this->faker->sentence],
                (object) ['body' => $this->faker->sentence],
            ])
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response
            ->assertSuccessful()
            ->assertJson([
                'post' => [
                    'content' => null,
                    'typeName' => 'poem',
                    'isPost' => true,
                    'type' => [
                        ['title' => $data['title']]
                    ],
                ]
            ]);

        $this->assertEquals(
            2, 
            count($response['post']['type'][0]['sections'])
        );
    }

    public function test_can_create_post_with_post_type_of_poem_with_files()
    {
        Storage::fake('public');

        $account = Professional::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $this->assertDatabaseCount('professionals',1);

        $data = [
            'type' => 'poem',
            "account" => $account->accountType,
            'accountId' => $account->id,
            'authorNames' => implode(', ', [
                $this->faker->name,
                $this->faker->name,
            ]),
            'title' => $this->faker->sentence,
            'about' => $this->faker->paragraph,
            'published' => now(),
            'sections' => json_encode([
                (object) ['body' => $this->faker->sentence],
                (object) ['body' => $this->faker->sentence],
            ]),
            'typeFiles' => [
                UploadedFile::fake()->image('new_image1.png'),
                UploadedFile::fake()->image('new_image2.png'),
            ]
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response
            ->assertSuccessful()
            ->assertJson([
                'post' => [
                    'content' => null,
                    'typeName' => 'poem',
                    'isPost' => true,
                    'type' => [
                        ['title' => $data['title']]
                    ],
                ]
            ]);

        $this->assertEquals(
            2, 
            count($response['post']['type'][0]['sections'])
        );

        $string1 = $response['post']['type'][0]['images'][0]['url'];
        $string2 = $response['post']['type'][0]['images'][1]['url'];

        Storage::disk('public')->assertExists(
            str_replace(
                substr($string1, 0 , 24),
                '',
                $string1
            )
        );
        Storage::disk('public')->assertExists(
            str_replace(
                substr($string2, 0 , 24),
                '',
                $string2
            )
        );
    }

    public function test_validation_of_required_data_for_creating_post_type_poem()
    {

        $data = [
            'type' => 'poem'
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response->assertJsonValidationErrors(['account', 'accountId','title']);
    }

    public function test_validation_of_required_data_for_creating_post_type_riddle()
    {

        $data = [
            'type' => 'riddle'
        ];

        $response = $this->postJson('/api/post', $data);

        $response
            ->assertJsonValidationErrors(['account', 'accountId','riddle']);
    }

    public function test_can_create_post_with_post_type_of_riddle()
    {
        $account = ParentModel::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $this->assertDatabaseCount('parent_models',1);

        $data = [
            'type' => 'riddle',
            "account" => $account->accountType,
            'accountId' => $account->id,
            'authorNames' => implode(', ', [
                $this->faker->name,
                $this->faker->name,
            ]),
            'riddle' => $this->faker->paragraph,
            'published' => now()->toDateTimeString(),
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response
            ->assertSuccessful()
            ->assertJson([
                'post' => [
                    'content' => null,
                    'typeName' => 'riddle',
                    'isPost' => true,
                    'type' => [
                        ['riddle' => $data['riddle']]
                    ],
                ]
            ]);

        $this->assertNull(
            $response['post']['type'][0]['images']
        );
    }

    public function test_can_create_post_with_post_type_of_riddle_with_files()
    {
        Storage::fake('public');

        $account = ParentModel::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $this->assertDatabaseCount('parent_models',1);

        $data = [
            'type' => 'riddle',
            "account" => $account->accountType,
            'accountId' => $account->id,
            'authorNames' => implode(', ', [
                $this->faker->name,
                $this->faker->name,
            ]),
            'riddle' => $this->faker->paragraph,
            'published' => now()->toDateTimeString(),
            'typeFiles' => [
                UploadedFile::fake()->image('new_image1.png'),
                UploadedFile::fake()->image('new_image2.png'),
            ]
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response
            ->assertSuccessful()
            ->assertJson([
                'post' => [
                    'content' => null,
                    'typeName' => 'riddle',
                    'isPost' => true,
                    'type' => [
                        ['riddle' => $data['riddle']]
                    ],
                ]
            ]);

        $this->assertNotNull(
            $response['post']['type'][0]['images']
        );
        

        $string1 = $response['post']['type'][0]['images'][0]['url'];
        $string2 = $response['post']['type'][0]['images'][1]['url'];

        Storage::disk('public')->assertExists(
            str_replace(
                substr($string1, 0 , 24),
                '',
                $string1
            )
        );
        Storage::disk('public')->assertExists(
            str_replace(
                substr($string2, 0 , 24),
                '',
                $string2
            )
        );
    }

    public function test_validation_of_required_data_for_create_post_with_post_type_of_book()
    {
        $data = [
            'type' => 'book'
        ];

        $response = $this->postJson('/api/post', $data);

        $response->assertJsonValidationErrors(['account', 'accountId', 'title']);
    }

    public function test_can_create_post_with_post_type_of_book()
    {
        $account = School::factory()
            ->for($this->user, 'owner')
            ->state([
                'company_name'=> $this->faker->company
            ])
            ->create();
        
        $this->assertDatabaseCount('schools',1);
        $this->assertDatabaseCount('profiles',1);

        $data = [
            'type' => 'book',
            "account" => $account->accountType,
            'accountId' => $account->id,
            'authorNames' => implode(', ', [
                $this->faker->name,
                $this->faker->name,
            ]),
            'title' => $this->faker->sentence,
            'about' => $this->faker->paragraph,
            'published' => now()->toDateTimeString(),
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response
            ->assertSuccessful()
            ->assertJson([
                'post' => [
                    'content' => null,
                    'typeName' => 'book',
                    'isPost' => true,
                    'type' => [
                        ['title' => $data['title']]
                    ],
                ]
            ]);

        $this->assertNull(
            $response['post']['type'][0]['images']
        );
        $this->assertNotNull(
            $response['post']['type'][0]['publishedAt']
        );
    }

    public function test_can_create_post_with_post_type_of_book_with_files()
    {
        Storage::fake('public');

        $account = School::factory()
            ->for($this->user, 'owner')
            ->state([
                'company_name'=> $this->faker->company
            ])
            ->create();
        
        $user = User::factory()
            ->has(Admin::factory()->state(function($attributes, $user){
                return [
                    'name'=> "{$user->first_name} {$user->last_name}"
                ];
            }))
            ->create();

        $this->assertDatabaseCount('schools',1);
        $this->assertDatabaseCount('profiles',1);
        $this->assertDatabaseCount('admins',1);

        $data = [
            'type' => 'book',
            'adminId' => $user->admins->first()->id,
            "account" => $account->accountType,
            'accountId' => $account->id,
            'authorNames' => implode(', ', [
                $this->faker->name,
                $this->faker->name,
            ]),
            'title' => $this->faker->sentence,
            'about' => $this->faker->paragraph,
            'published' => now()->toDateTimeString(),
            'typeFiles' => [
                UploadedFile::fake()->image('new_image1.png'),
                UploadedFile::fake()->image('new_image2.png'),
            ]
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response
            ->assertSuccessful()
            ->assertJson([
                'post' => [
                    'content' => null,
                    'typeName' => 'book',
                    'isPost' => true,
                    'type' => [
                        ['title' => $data['title']]
                    ],
                ]
            ]);
        
        $this->assertDatabaseCount('activity_tracks',1);

        $string2 = $response['post']['type'][0]['images'][1]['url'];

        Storage::disk('public')->assertExists(
            str_replace(
                substr($string2, 0 , 24),
                '',
                $string2
            )
        );
    }

    public function test_can_create_post_with_post_type_of_activity_with_files_validation()
    {
        $data = [
            'type' => 'activity',
        ];

        $response = $this->postJson('/api/post', $data);

        $response->assertJsonValidationErrors(['account', 'accountId']);
    }

    public function test_validation_of_required_data_and_file_for_create_post_with_post_type_of_activity()
    {
        $account = School::factory()
            ->for($this->user, 'owner')
            ->state([
                'company_name'=> $this->faker->company
            ])
            ->create();

        $data = [
            'type' => 'activity',
            "account" => $account->accountType,
            'accountId' => $account->id,
            'description' => $this->faker->paragraph,
            'published' => now()->toDateTimeString(),
        ];

        $response = $this->postJson('/api/post', $data);

        $response->assertJson(['message' => 'an activity should have at least one file (image, video or audio)']);
    }

    public function test_can_create_post_with_post_type_of_activity()
    {
        Storage::fake('public');

        $account = School::factory()
            ->for($this->user, 'owner')
            ->state([
                'company_name'=> $this->faker->company
            ])
            ->create();

        $data = [
            'type' => 'activity',
            "account" => $account->accountType,
            'accountId' => $account->id,
            'description' => $this->faker->paragraph,
            'published' => now()->toDateTimeString(),
            'typeFiles' => [
                UploadedFile::fake()->image('new_image1.png'),
                UploadedFile::fake()->image('new_image2.png'),
            ]
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response
            ->assertSuccessful()
            ->assertJson([
                'post' => [
                    'content' => null,
                    'typeName' => 'activity',
                    'isPost' => true,
                    'type' => [
                        ['description' => $data['description']]
                    ],
                ]
            ]);

        $this->assertNotNull(
            $response['post']['type'][0]['description']
        );
        $this->assertNotNull(
            $response['post']['type'][0]['publishedAt']
        );

        $string1 = $response['post']['type'][0]['images'][0]['url'];
        $string2 = $response['post']['type'][0]['images'][1]['url'];

        Storage::disk('public')->assertExists(
            str_replace(
                substr($string1, 0 , 24),
                '',
                $string1
            )
        );
        Storage::disk('public')->assertExists(
            str_replace(
                substr($string2, 0 , 24),
                '',
                $string2
            )
        );
    }

    public function test_validation_of_required_data_for_create_post_with_post_type_of_lesson()
    {
        $data = [
            'type' => 'lesson'
        ];

        $response = $this->postJson('/api/post', $data);

        $response->assertJsonValidationErrors(['account', 'accountId', 'title']);
    }

    public function test_can_create_post_with_post_type_of_lesson()
    {
        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> $this->faker->company
            ])
            ->create();

        $this->assertDatabaseCount('learners',1);
        $this->assertDatabaseCount('profiles',1);

        $data = [
            'type' => 'lesson',
            "account" => $account->accountType,
            'accountId' => $account->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'published' => now()->toDateTimeString(),
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response
            ->assertSuccessful()
            ->assertJson([
                'post' => [
                    'content' => null,
                    'typeName' => 'lesson',
                    'isPost' => true,
                    'type' => [
                        ['title' => $data['title']]
                    ],
                ]
            ]);
        
    }

    public function test_can_create_post_with_post_type_of_lesson_with_files_validation()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> $this->faker->company
            ])
            ->create();

        $this->assertDatabaseCount('learners',1);
        $this->assertDatabaseCount('profiles',1);

        $data = [
            'type' => 'lesson',
            "account" => $account->accountType,
            'accountId' => $account->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'published' => now()->toDateTimeString(),
            'typeFiles' => [
                UploadedFile::fake()->image('new_image1.png'),
                UploadedFile::fake()->image('new_image2.png'),
            ]
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => "lesson's images cannot be more than 1"
            ]);
        
    }

    public function test_can_create_post_with_post_type_of_lesson_with_files()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> $this->faker->company
            ])
            ->create();

        $this->assertDatabaseCount('learners',1);
        $this->assertDatabaseCount('profiles',1);

        $data = [
            'type' => 'lesson',
            "account" => $account->accountType,
            'accountId' => $account->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'published' => now()->toDateTimeString(),
            'typeFiles' => [
                UploadedFile::fake()->image('new_image1.png'),
            ]
        ];

        $response = $this->postJson('/api/post', $data);

        $response->dump();

        $response
            ->assertSuccessful()
            ->assertJson([
                'post' => [
                    'content' => null,
                    'typeName' => 'lesson',
                    'isPost' => true,
                    'type' => [
                        ['title' => $data['title']]
                    ],
                ]
            ]);
        $string = $response['post']['type'][0]['images'][0]['url'];
        Storage::assertExists(
            str_replace(substr($string, 0, 24), '', $string)
        );
    }

    public function test_validation_of_required_update_post_without_post_type()
    {
        $data = [

        ];

        $response = $this->postJson('/api/post/1', $data);
        
        $response->assertJsonValidationErrors(['account','accountId']);
    }

    public function test_cannot_update_post_without_required_data()
    {
        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $post = Post::factory()
            ->for($account, 'addedby')
            ->create();

        $data = [
            "postId" => $post->id,
            'content' => "",
            'account' =>  $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->postJson('/api/post/1', $data);

        $response
            ->assertJson([
                'message' => "a post requires a body and/or files",
            ])
            ->assertStatus(422);
    }

    public function test_can_update_post_without_post_type()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $post = Post::factory()
            ->for($account, 'addedby')
            ->create();

        $newContent = $this->faker->paragraph;

        $data = [
            "postId" => $post->id,
            'content' => $newContent,
            'account' =>  $account->accountType,
            'accountId' => $account->id,
            'file' => [
                UploadedFile::fake()->image('new_image.png')
            ]
        ];

        $response = $this->postJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'post' => [
                    'id' => $post->id,
                    'content' => $newContent
                ]
            ]);

        $string = $response['post']['images'][0]['url'];
        Storage::assertExists(
            str_replace(substr($string, 0, 24), '', $string)
        );
    }

    public function test_can_update_post_with_post_type_of_question()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $question = Question::factory()
            ->hasPossibleAnswers(3,new Sequence(
                ['option' => 'true'],
                ['option' => 'false'],
                ['option' => 'none'],
            ))
            ->for($account, 'addedby')
            ->create();

        $question->images()->attach($image);
        $question->save();
            
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();
        
        $post->questions()->save($question);
        
        $data = [
            "postId" => $post->id,
            'content' => 'edited',
            'type' => 'question',
            'account' =>  $account->accountType,
            'accountId' => $account->id,
            'questionId' => $question->id,
            'body' => 'edited question',
            'typeFiles' => [
                UploadedFile::fake()->image('new_image.png')
            ],
            'removedTypeFiles' => json_encode([
                (object) [
                    'type' => 'image', 
                    'id' => $image->id
                ]
            ]),
            'editedPossibleAnswers' => json_encode([
                (object) [
                    'option' => 'hey', 
                    'possibleAnswerId' => $question->possibleAnswers()
                    ->where('option', 'true')->first()->id
                ]
            ]),
            'removedPossibleAnswers' => json_encode([
                (object) [
                    'option' => '', 
                    'possibleAnswerId' => $question->possibleAnswers()
                    ->where('option', 'none')->first()->id
                ]
            ])
        ];

        $response = $this->postJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
                'post' => [
                    'id' => $post->id,
                    'content' => 'edited',
                    'typeName' => 'question',
                    'type' => [
                        [
                            'id' => $question->id,
                            'body' => 'edited question',
                        ]
                    ]
                ]
            ]);

        $this->assertEquals(2, count($response['post']['type'][0]['possibleAnswers']));
        $this->assertEquals(1, count(
            array_filter(
                $response['post']['type'][0]['possibleAnswers'],
                function($possibleAnswer) {
                    ray($possibleAnswer)->green();
                    return $possibleAnswer['option'] === 'hey';
                }
            )
        ));

        $this->assertEquals(1, count($response['post']['type'][0]['images']));

        $this->assertSoftDeleted('images', ['id' => $image->id]);

        $string = $response['post']['type'][0]['images'][0]['url'];
        Storage::assertExists(
            str_replace(substr($string, 0, 24), '', $string)
        );
    }

    public function test_can_update_post_with_post_type_of_poem()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();


        $poem = Poem::factory()
            ->for($account, 'addedby')
            ->create();

        $poemSections = PoemSection::factory()
        ->count(3)->create([
            'poem_id' => $poem->id
        ]);

        $poem->images()->attach($image);
        $poem->save();
            
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();
        
        $post->poems()->save($poem);
        
        $data = [
            "postId" => $post->id,
            'content' => 'edited',
            'type' => 'poem',
            'account' =>  $account->accountType,
            'accountId' => $account->id,
            'poemId' => $poem->id,
            'title' => 'edited poem',
            'typeFiles' => [
                UploadedFile::fake()->image('new_image.png')
            ],
            'removedTypeFiles' => json_encode([
                (object) [
                    'type' => 'image', 
                    'id' => $image->id
                ]
            ]),
            'editedSections' => json_encode([
                (object) [
                    'body' => 'hey', 
                    'poemSectionId' => $poemSections->first()->id
                ]
            ]),
            'removedSections' => json_encode([
                (object) [
                    'body' => '', 
                    'poemSectionId' => $poemSections->last()->id
                ]
            ])
        ];

        $response = $this->postJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
                'post' => [
                    'id' => $post->id,
                    'content' => 'edited',
                    'typeName' => 'poem',
                    'type' => [
                        [
                            'id' => $poem->id,
                            'title' => 'edited poem',
                        ]
                    ]
                ]
            ]);

        $this->assertEquals(2, count($response['post']['type'][0]['sections']));
        
        $this->assertEquals(1, count(
            array_filter(
                $response['post']['type'][0]['sections'],
                function($section) {
                    return $section['body'] === 'hey';
                }
            )
        ));

        $this->assertEquals(1, count($response['post']['type'][0]['images']));

        $this->assertSoftDeleted('images', ['id' => $image->id]);

        $string = $response['post']['type'][0]['images'][0]['url'];
        Storage::assertExists(
            str_replace(substr($string, 0, 24), '', $string)
        );
    }

    public function test_cannot_update_post_type_of_poem_without_require_data()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $poem = Poem::factory()
            ->for($account, 'addedby')
            ->create();

        $poemSections = PoemSection::factory()
        ->count(2)->create([
            'poem_id' => $poem->id
        ]);

        $poem->images()->attach($image);
        $poem->save();
            
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();
        
        $post->poems()->save($poem);
        
        $data = [
            "postId" => $post->id,
            'content' => 'edited',
            'type' => 'poem',
            'account' =>  $account->accountType,
            'accountId' => $account->id,
            'poemId' => $poem->id,
            'title' => 'edited poem',
            'removedTypeFiles' => json_encode([
                (object) [
                    'type' => 'image', 
                    'id' => $image->id
                ]
            ]),
            'removedSections' => json_encode([
                (object) [
                    'body' => '', 
                    'poemSectionId' => $poemSections->first()->id
                ],
                (object) [
                    'body' => '', 
                    'poemSectionId' => $poemSections->last()->id
                ]
            ])
        ];

        $response = $this->postJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'a poem requires at least one section or file (image, video or audio)',
            ]);

        $this->assertDatabaseCount('poem_sections', 2);

        $this->assertDatabaseCount('images', 1);
    }

    public function test_can_update_post_with_post_type_of_riddle()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $riddle = Riddle::factory()
            ->for($account, 'addedby')
            ->create();

        $riddle->images()->attach($image);
        $riddle->save();
            
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();
        
        $post->riddles()->save($riddle);
        
        $data = [
            "postId" => $post->id,
            'content' => 'edited',
            'type' => 'riddle',
            'account' =>  $account->accountType,
            'accountId' => $account->id,
            'riddleId' => $riddle->id,
            'body' => 'edited riddle',
            'typeFiles' => [
                UploadedFile::fake()->image('new_image.png')
            ],
            'removedTypeFiles' => json_encode([
                (object) [
                    'type' => 'image', 
                    'id' => $image->id
                ]
            ]),
        ];

        $response = $this->postJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
                'post' => [
                    'id' => $post->id,
                    'content' => 'edited',
                    'typeName' => 'riddle',
                    'type' => [
                        [
                            'id' => $riddle->id,
                            'body' => 'edited riddle',
                        ]
                    ]
                ]
            ]);

        $this->assertEquals(1, count($response['post']['type'][0]['images']));

        $this->assertSoftDeleted('images', ['id' => $image->id]);

        $string = $response['post']['type'][0]['images'][0]['url'];
        Storage::assertExists(
            str_replace(substr($string, 0, 24), '', $string)
        );
    }

    public function test_can_update_post_with_post_type_of_book()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $book = Book::factory()
            ->for($account, 'addedby')
            ->create();

        $book->images()->attach($image);
        $book->save();
            
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();
        
        $post->books()->save($book);
        
        $data = [
            "postId" => $post->id,
            'content' => 'edited',
            'type' => 'book',
            'account' =>  $account->accountType,
            'accountId' => $account->id,
            'bookId' => $book->id,
            'title' => 'edited book',
            'typeFiles' => [
                UploadedFile::fake()->image('new_image.png')
            ],
            'removedTypeFiles' => json_encode([
                (object) [
                    'type' => 'image', 
                    'id' => $image->id
                ]
            ]),
        ];

        $response = $this->postJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
                'post' => [
                    'id' => $post->id,
                    'content' => 'edited',
                    'typeName' => 'book',
                    'type' => [
                        [
                            'id' => $book->id,
                            'title' => 'edited book',
                        ]
                    ]
                ]
            ]);

        $this->assertEquals(1, count($response['post']['type'][0]['images']));

        $this->assertSoftDeleted('images', ['id' => $image->id]);

        $string = $response['post']['type'][0]['images'][0]['url'];
        Storage::assertExists(
            str_replace(substr($string, 0, 24), '', $string)
        );
    }

    public function test_can_update_post_with_post_type_of_activity_with_files_validation()
    {
        
        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $activity = Activity::factory()
            ->for($account, 'addedby')
            ->create();

        $activity->images()->attach($image);
        $activity->save();
            
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();
        
        $post->activities()->save($activity);
        
        $data = [
            "postId" => $post->id,
            'content' => 'edited',
            'type' => 'activity',
            'account' =>  $account->accountType,
            'accountId' => $account->id,
            'activityId' => $activity->id,
            'description' => 'edited activity',
            'removedTypeFiles' => json_encode([
                (object) [
                    'type' => 'image', 
                    'id' => $image->id
                ]
            ]),
        ];

        $response = $this->postJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'an activity should have at least one file (image, video or audio)',
            ]);
    }

    public function test_can_update_post_with_post_type_of_activity()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $activity = Activity::factory()
            ->for($account, 'addedby')
            ->create();

        $activity->images()->attach($image);
        $activity->save();
            
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();
        
        $post->activities()->save($activity);
        
        $data = [
            "postId" => $post->id,
            'content' => 'edited',
            'type' => 'activity',
            'account' =>  $account->accountType,
            'accountId' => $account->id,
            'activityId' => $activity->id,
            'description' => 'edited activity',
            'typeFiles' => [
                UploadedFile::fake()->image('new_image.png')
            ],
            'removedTypeFiles' => json_encode([
                (object) [
                    'type' => 'image', 
                    'id' => $image->id
                ]
            ]),
        ];

        $response = $this->postJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
                'post' => [
                    'id' => $post->id,
                    'content' => 'edited',
                    'typeName' => 'activity',
                    'type' => [
                        [
                            'id' => $activity->id,
                            'description' => 'edited activity',
                        ]
                    ]
                ]
            ]);

        $this->assertEquals(1, count($response['post']['type'][0]['images']));

        $this->assertSoftDeleted('images', ['id' => $image->id]);

        $string = $response['post']['type'][0]['images'][0]['url'];
        Storage::assertExists(
            str_replace(substr($string, 0, 24), '', $string)
        );
    }

    public function test_can_update_post_with_post_type_of_lesson_with_files_validation_less()
    {
        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $lesson = Lesson::factory()
            ->for($account, 'addedby')
            ->create();

        $lesson->images()->attach($image);
        $lesson->save();
            
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();
        
        $post->lessons()->save($lesson);
        
        $data = [
            "postId" => $post->id,
            'content' => 'edited',
            'type' => 'lesson',
            'account' =>  $account->accountType,
            'accountId' => $account->id,
            'lessonId' => $lesson->id,
            'title' => 'edited lesson',
            'published' => now()->toDateTimeString(),
            'removedTypeFiles' => json_encode([
                (object) [
                    'type' => 'image', 
                    'id' => $image->id
                ]
            ]),
        ];

        $response = $this->postJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => 'a lesson should have at least one file (image, video or audio)',
            ]);

    }

    public function test_can_update_post_with_post_type_of_lesson_with_files_validation_more()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $lesson = Lesson::factory()
            ->for($account, 'addedby')
            ->create();

        $lesson->images()->attach($image);
        $lesson->save();
            
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();
        
        $post->lessons()->save($lesson);
        
        $data = [
            "postId" => $post->id,
            'content' => 'edited',
            'type' => 'lesson',
            'account' =>  $account->accountType,
            'accountId' => $account->id,
            'lessonId' => $lesson->id,
            'title' => 'edited lesson',
            'published' => now()->toDateTimeString(),
            'removedTypeFiles' => json_encode([
                (object) [
                    'type' => 'image', 
                    'id' => $image->id
                ]
            ]),
            'typeFiles' => [
                UploadedFile::fake()->image('new_image1.png'),
                UploadedFile::fake()->image('new_image2.png'),
            ],
        ];

        $response = $this->postJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertStatus(422)
            ->assertJson([
                'message' => "lesson's images cannot be more than 1",
            ]);

    }

    public function test_can_update_post_with_post_type_of_lesson()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();

        $image = Image::factory()
            ->state([
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $lesson = Lesson::factory()
            ->for($account, 'addedby')
            ->create();

        $lesson->images()->attach($image);
        $lesson->save();
            
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();
        
        $post->lessons()->save($lesson);
        
        $data = [
            "postId" => $post->id,
            'content' => 'edited',
            'type' => 'lesson',
            'account' =>  $account->accountType,
            'accountId' => $account->id,
            'lessonId' => $lesson->id,
            'title' => 'edited lesson',
            'published' => now()->toDateTimeString(),
            'typeFiles' => [
                UploadedFile::fake()->image('new_image.png')
            ],
            'removedTypeFiles' => json_encode([
                (object) [
                    'type' => 'image', 
                    'id' => $image->id
                ]
            ]),
        ];

        $response = $this->postJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
                'post' => [
                    'id' => $post->id,
                    'content' => 'edited',
                    'typeName' => 'lesson',
                    'type' => [
                        [
                            'id' => $lesson->id,
                            'title' => 'edited lesson',
                        ]
                    ]
                ]
            ]);

        $this->assertEquals(1, count($response['post']['type'][0]['images']));

        $this->assertSoftDeleted('images', ['id' => $image->id]);

        $string = $response['post']['type'][0]['images'][0]['url'];
        Storage::assertExists(
            str_replace(substr($string, 0, 24), '', $string)
        );
    }

    public function test_can_delete_post_without_post_type()
    {
        Storage::fake('public');

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $image = Image::factory()
            ->state([
                'path' => "images/new_file.png",
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();
        
        UploadedFile::fake()->image('new_file.png')
            ->store($image->path);

        Event::fake();
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();

        $post->images()->attach($image);
        $post->save();
        
        $data = [
            "postId" => $post->id,
            'content' => 'edited',
            'account' =>  $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->deleteJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        $this->assertSoftDeleted('images', ['id' => $image->id]);

        Event::assertDispatched(DeletePost::class);
        
        // Storage::assertMissing($image->path);
    }

    public function test_can_delete_post_with_post_type_of_question()
    {

        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $image = Image::factory()
            ->state([
                'path' => "images/new_file.png",
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $question = Question::factory()
            ->create();

        Event::fake();
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();

        $question->questionable()->associate($post);
        $question->images()->attach($image);
        $question->save();
        
        $data = [
            "postId" => $post->id,
            'account' =>  $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->deleteJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        $this->assertSoftDeleted('images', ['id' => $image->id]);
        $this->assertSoftDeleted('questions', ['id' => $question->id]);

        Event::assertDispatched(DeletePost::class);
    }

    public function test_can_delete_post_with_post_type_of_poem()
    {
        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $image = Image::factory()
            ->state([
                'path' => "images/new_file.png",
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $poem = Poem::factory()
            ->has(
                PoemSection::factory()->count(2)
            )
            ->create();
        $poemSection = PoemSection::factory()
            ->state(['poem_id'=>$poem->id])->create();

        Event::fake();
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();

        $poem->poemable()->associate($post);
        $poem->images()->attach($image);
        $poem->save();
        
        $data = [
            "postId" => $post->id,
            'account' =>  $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->deleteJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        $this->assertSoftDeleted('images', ['id' => $image->id]);
        $this->assertSoftDeleted('poems', ['id' => $poem->id]);
        $this->assertSoftDeleted('poem_sections', ['id' => $poemSection->id]);

        Event::assertDispatched(DeletePost::class);
    }

    public function test_can_delete_post_with_post_type_of_riddle()
    {
        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $image = Image::factory()
            ->state([
                'path' => "images/new_file.png",
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $riddle = Riddle::factory()
            ->create();

        Event::fake();
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();

        $riddle->riddleable()->associate($post);
        $riddle->images()->attach($image);
        $riddle->save();
        
        $data = [
            "postId" => $post->id,
            'account' =>  $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->deleteJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        $this->assertSoftDeleted('images', ['id' => $image->id]);
        $this->assertSoftDeleted('riddles', ['id' => $riddle->id]);

        Event::assertDispatched(DeletePost::class);
    }

    public function test_can_delete_post_with_post_type_of_book()
    {
        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $image = Image::factory()
            ->state([
                'path' => "images/new_file.png",
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $book = book::factory()
            ->create();

        Event::fake();
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();

        $book->bookable()->associate($post);
        $book->images()->attach($image);
        $book->save();
        
        $data = [
            "postId" => $post->id,
            'account' =>  $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->deleteJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        $this->assertSoftDeleted('images', ['id' => $image->id]);
        $this->assertSoftDeleted('books', ['id' => $book->id]);

        Event::assertDispatched(DeletePost::class);
    }

    public function test_can_delete_post_with_post_type_of_activity()
    {
        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $image = Image::factory()
            ->state([
                'path' => "images/new_file.png",
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $activity = Activity::factory()
            ->create();

        Event::fake();
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();

        $activity->activityfor()->associate($post);
        $activity->images()->attach($image);
        $activity->save();
        
        $data = [
            "postId" => $post->id,
            'account' =>  $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->deleteJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        $this->assertSoftDeleted('images', ['id' => $image->id]);
        $this->assertSoftDeleted('activities', ['id' => $activity->id]);

        Event::assertDispatched(DeletePost::class);
    }

    public function test_can_delete_post_with_post_type_of_lesson()
    {
        $account = Learner::factory()
            ->for($this->user)
            ->state([
                'name'=> "{$this->user->first_name} {$this->user->last_name}"
            ])
            ->create();
        
        $image = Image::factory()
            ->state([
                'path' => "images/new_file.png",
                'addedby_type' => $account::class,
                'addedby_id' => $account->id,
            ])
            ->create();

        $links = Link::factory()
            ->for($account, 'addedby')->count(2)->create();

        $lesson = lesson::factory()
            ->for($account, 'addedby')
            ->create();

        Event::fake();
        $post = Post::factory()
            ->for($account, 'addedby')
            ->state(['content' => null])
            ->create();

        $lesson->lessonable()->associate($post);
        $lesson->images()->attach($image);
        $lesson->save();

        $links->each(function($link) use ($lesson) {
            $link->linkable()->associate($lesson);
            $link->save();
        });
        
        
        $data = [
            "postId" => $post->id,
            'account' =>  $account->accountType,
            'accountId' => $account->id,
        ];

        $response = $this->deleteJson("/api/post/{$post->id}", $data);

        $response
            ->dump()
            ->assertSuccessful()
            ->assertJson([
                'message' => 'successful',
            ]);

        $this->assertSoftDeleted('images', ['id' => $image->id]);
        $this->assertSoftDeleted('lessons', ['id' => $lesson->id]);
        $this->assertSoftDeleted('links', ['id' => $links->last()->id]);

        Event::assertDispatched(DeletePost::class);
    }
}
