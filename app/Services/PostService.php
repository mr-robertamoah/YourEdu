<?php

namespace App\Services;

use App\DTOs\ActivityDTO;
use App\DTOs\ActivityTrackDTO;
use App\DTOs\BookDTO;
use App\DTOs\LessonDTO;
use App\DTOs\PoemDTO;
use App\DTOs\PostDTO;
use App\DTOs\PostsDTO;
use App\DTOs\QuestionDTO;
use App\DTOs\RiddleDTO;
use App\Events\DeletePost;
use App\Events\NewPost;
use App\Events\UpdatePost;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\PostException;
use App\Jobs\DeletePostJob;
use App\Jobs\NewPostJob;
use App\Jobs\UpdatePostJob;
use App\Traits\ServiceTrait;
use App\YourEdu\Assessment;
use App\YourEdu\Discussion;
use App\YourEdu\Post;

class PostService
{
    use ServiceTrait;

    const PAGINATION = 10;

    public function createPost(PostDTO $postDTO)
    {
        try {
            
            $postDTO = $this->getAddedby($postDTO);

            $this->checkAccountAuthorization($postDTO);

            $this->checkRequiredData(
                postDTO: $postDTO
            );

            $post = $this->createOrUpdatePost($postDTO, 'create');

            $post = $this->addPostFiles($post, $postDTO);
            
            $post = $this->addPostAttachments($post, $postDTO);

            $postDTO = $postDTO->withPost($post);

            $post = $this->addPostType($post, $postDTO);

            $this->trackAdminActivity($postDTO, __METHOD__);

            $this->broadcastPost($post, $postDTO, 'create');

            return $this->postWith($post);

        } catch (\Throwable $th) {

            throw $th;
            $this->throwPostException(
                message: "oops! something happened.",
                data: $postDTO,
                deleteFiles: (bool) $postDTO->post
            );
        }        
    }

    private function checkRequiredData(Post $post = null, PostDTO $postDTO)
    {
        if (in_array($postDTO->type, $postDTO->types)) {
            return;
        }

        if ($postDTO->content !== '' && $postDTO->content) {
            return;
        }

        $postFilesDTO = FileService::countPossibleItemFiles($post, $postDTO);

        if ($postFilesDTO->totalFiles() > 0) {
            return;
        }

        $this->throwPostException(
            message: "a post requires a body and/or files",
            data: $postDTO
        );
    }

    private function checkAccountAuthorization(PostDTO $postDTO)
    {
        $userIds = $postDTO->addedby->getAuthorizedIds();

        if (!in_array($postDTO->userId, $userIds)) {
            $this->throwPostException(
                message: "you are not authorized to post with this account",
                data: $postDTO
            );
        }
    }

    private function throwPostException
    (
        string $message,
        $data = null,
        $deleteFiles = false
    )
    {
        throw new PostException(
            message: $message,
            data: $data,
            deleteFiles: $deleteFiles
        );
    }

    private function postWith(Post $post) : Post
    {
        return $post->load([
            'questions.images','questions.videos',
            'questions.audios','questions.files','activities.images','activities.videos',
            'activities.files','activities.audios','riddles.images','riddles.videos',
            'riddles.files','riddles.audios','poems.images','poems.videos',
            'poems.files','poems.audios','books.images','books.videos','books.files',
            'books.audios','addedby.profile'
        ]);
    }

    private function broadcastPost
    (
        Post $post = null,
        PostDTO $postDTO,
        string $method
    )
    {
        $postDTO = $postDTO->resetFiles();

        $postDTO->typeDTO = $postDTO->typeDTO?->resetFiles();

        if ($method === 'create') {
            
            broadcast(
                new NewPost($post)
            )->toOthers();
        }

        if ($method === 'udpate') {
            
            broadcast(
                new UpdatePost($post)
            )->toOthers();
        }

        if ($method === 'delete') {
            
            broadcast(
                new DeletePost($postDTO)
            )->toOthers();
        }
    }

    private function createOrUpdatePost
    (
        PostDTO $postDTO,
        string $method
    ) : Post
    {
        $data = [
            'content' => $postDTO->content,
        ];

        $post = null;

        if ($method === 'create') {
            $post = $postDTO->addedby->posts()->create($data);
        }

        if ($method === 'update') {
            $post = getYourEduModel('post',$postDTO->postId);
                
            $post?->update($data);
        }

        if (!$post) {
            throw new PostException(
                message: "failed to {$method} post",
                data: $postDTO
            );
        }

        return $post->refresh();
    }

    private function addPostFiles
    (
        Post $post, 
        PostDTO $postDTO
    ) : Post
    {
        foreach ($postDTO->files as $file) {
            FileService::createAndAttachFiles(
                account: $postDTO->addedby,
                file: $file,
                item: $post
            );
        }

        return $post->refresh();
    }

    private function removePostFiles
    (
        Post $post, 
        PostDTO $postDTO
    ) : Post
    {
        foreach ($postDTO->removedFiles as $file) {
            FileService::deleteAndUnattachFiles(
                file: $file,
                item: $post
            );
        }

        return $post->refresh();
    }

    private function getAddedby($postDTO)
    {
        $account = $this->getModel($postDTO->account,$postDTO->accountId);
        
        if (!in_array($postDTO->userId, $account->getAuthorizedIds())) {
            $this->throwPostException(
                message: "The {$postDTO->account} account with id {$postDTO->account_id} doesn't belong to you.",
                data: $postDTO
            );
        }

        $postDTO->addedby = $account;

        return $postDTO;
    }

    private function addPostType
    (
        Post $post,
        PostDTO $postDTO
    ) : Post
    {
        if (!in_array($postDTO->type, $postDTO->types)) {
            return $post;
        }

        $postDTO->typeDTO->addedby = $post->addedby;

        switch ($postDTO->type) {
            case 'book':
                $this->addBookToPost($post, $postDTO);
                break;

            case 'riddle':
                $this->addRiddleToPost($post, $postDTO);
                break;
            
            case 'poem':
                $this->addPoemToPost($post, $postDTO);
                break;
            
            case 'lesson':
                $this->addLessonToPost($post, $postDTO);
                break;
            
            case 'activity':
                $this->addActivityToPost($post, $postDTO);
                break;
            
            case 'question':
                $this->addQuestionToPost($post, $postDTO);
                break;
            
            default:
                return $post;
        }

        return $post->refresh();
    }

    private function addPoemToPost(Post $post, PostDTO $postDTO)
    {
        $postDTO->typeDTO->poemable = $post;
        $postDTO->typeModel = (new PoemService)->createPoem(
            $postDTO->typeDTO
        );
    }

    private function addQuestionToPost(Post $post, PostDTO $postDTO)
    {
        $postDTO->typeDTO->questionable = $post;
        $postDTO->typeDTO->state = 'PENDING';
        $postDTO->typeModel = (new QuestionService)->createQuestion(
            $postDTO->typeDTO
        );
    }

    private function addLessonToPost(Post $post, PostDTO $postDTO)
    {
        $postDTO->typeDTO->lessonable = $post;
        $postDTO->typeModel = (new LessonService)->createLesson(
            $postDTO->typeDTO
        );
    }

    private function addRiddleToPost(Post $post, PostDTO $postDTO)
    {
        $postDTO->typeDTO->riddleable = $post;
        $postDTO->typeModel = (new RiddleService)->createRiddle(
            $postDTO->typeDTO
        );
    }

    private function addActivityToPost(Post $post, PostDTO $postDTO)
    {
        $postDTO->typeDTO->activityfor = $post;
        $postDTO->typeModel = (new ActivityService)->createActivity(
            $postDTO->typeDTO
        );
    }

    private function addBookToPost(Post $post, PostDTO $postDTO)
    {
        $postDTO->typeDTO->bookable = $post;
        $postDTO->typeModel = (new BookService)->createBook(
            $postDTO->typeDTO
        );
    }

    private function addPostAttachments
    (
        Post $post, 
        PostDTO $postDTO
    ) : Post
    {
        foreach ($postDTO->attachments as $attachment) {
            
            (new AttachmentService)->attach(
                $postDTO->addedby,
                $post,
                getYourEduModel($attachment->item,$attachment->itemId)
            );
        }

        return $post->refresh();
    }

    private function removePostAttachments
    (
        Post $post, 
        PostDTO $postDTO
    ) : Post
    {
        foreach ($postDTO->removedAttachments as $attachment) {
            
            (new AttachmentService)->detach(
                $post,
                getYourEduModel($attachment->item,$attachment->itemId)
            );
        }

        return $post->refresh();
    }

    private function trackAdminActivity
    (
        PostDTO $postDTO,
        $method
    )
    {
        if (!$postDTO->adminId) {
            return;
        }

        $admin = getYourEduModel('admin', $postDTO->adminId);

        if (is_null($admin)) {
            return;
        }

        (new ActivityTrackService)->trackActivity(
            ActivityTrackDTO::createFromData(
                performedby: $admin,
                activity: $postDTO->post,
                activityfor: $postDTO->addedby,
                action: $method
            )
        );
    }

    public function updatePost(PostDTO $postDTO)
    {
        try {

            $postDTO = $this->getAddedby($postDTO);

            $this->checkAccountAuthorization($postDTO);

            $post = $this->createOrUpdatePost($postDTO, 'update');

            $this->checkRequiredData(
                post: $post,
                postDTO: $postDTO
            );

            $postDTO = $postDTO->withPost($post);

            $post = $this->addPostFiles($post, $postDTO);

            $post = $this->removePostFiles($post, $postDTO);

            $post = $this->updatePostType($post, $postDTO);
            
            $post = $this->addPostAttachments($post, $postDTO);
            
            $post = $this->removePostAttachments($post, $postDTO);

            $this->trackAdminActivity($postDTO, __METHOD__);

            $this->broadcastPost($post, $postDTO, 'update');

            return $this->postWith($post);

        } catch (\Throwable $th) {  
            throw $th;
            $this->throwPostException(
                message: "oops! something happened.",
                data: $postDTO,
                deleteFiles: true
            );
        }
    }

    private function updatePostsPoem(Post $post, PostDTO $postDTO)
    {
        $postDTO->typeDTO->poemable = $post;
        $postDTO->typeModel = (new PoemService)->updatePoem(
            $postDTO->typeDTO
        );
    }

    private function updatePostsQuestion(Post $post, PostDTO $postDTO)
    {
        $postDTO->typeDTO->questionable = $post;
        $postDTO->typeDTO->state = 'PENDING';
        $postDTO->typeModel = (new QuestionService)->updateQuestion(
            $postDTO->typeDTO
        );
    }

    private function updatePostsLesson(Post $post, PostDTO $postDTO)
    {
        $postDTO->typeDTO->lessonable = $post;
        $postDTO->typeModel = (new LessonService)->updateLesson(
            $postDTO->typeDTO
        );
    }

    private function updatePostsRiddle(Post $post, PostDTO $postDTO)
    {
        $postDTO->typeDTO->riddleable = $post;
        $postDTO->typeModel = (new RiddleService)->updateRiddle(
            $postDTO->typeDTO
        );
    }

    private function updatePostsActivity(Post $post, PostDTO $postDTO)
    {
        $postDTO->typeDTO->activityfor = $post;
        $postDTO->typeModel = (new ActivityService)->updateActivity(
            $postDTO->typeDTO
        );
    }

    private function updatePostsBook(Post $post, PostDTO $postDTO)
    {
        $postDTO->typeDTO->bookable = $post;
        $postDTO->typeModel = (new BookService)->updateBook(
            $postDTO->typeDTO
        );
    }

    private function updatePostType
    (
        Post $post,
        PostDTO $postDTO
    ) : Post
    {
        if (!in_array($postDTO->type, $postDTO->types)) {
            return $post;
        }

        $postDTO->typeDTO->addedby = $post->addedby;

        switch ($postDTO->type) {
            case 'book':
                $this->updatePostsBook($post, $postDTO);
                break;

            case 'riddle':
                $this->updatePostsRiddle($post, $postDTO);
                break;
            
            case 'poem':
                $this->updatePostsPoem($post, $postDTO);
                break;
            
            case 'lesson':
                $this->updatePostsLesson($post, $postDTO);
                break;
            
            case 'activity':
                $this->updatePostsActivity($post, $postDTO);
                break;
            
            case 'question':
                $this->updatePostsQuestion($post, $postDTO);
                break;
            
            default:
                return $post;
        }
        
        return $post->refresh();
    }

    public function deletePost(PostDTO $postDTO)
    {
        try {
            $postDTO = $this->getAddedby($postDTO);

            $this->checkAccountAuthorization($postDTO);

            $post = $this->getPost($postDTO);

            $postDTO = $postDTO->withPost($post);

            $postDeletionStatus = $post->delete();
            
            $post = $this->deletePostType($post, $postDTO);

            $this->deletePostFiles($post);

            if (!$postDeletionStatus) {
                $this->throwPostException(
                    message: "deletion of the post failed.",
                    data: $postDTO
                );
            }

            $this->trackAdminActivity($postDTO, __METHOD__);

            $this->broadcastPost(
                postDTO: $postDTO,
                method: 'delete'
            );
        } catch (\Throwable $th) {
            throw $th;
            $this->throwPostException(
                message: "oops! something happened.",
                data: $postDTO,
                deleteFiles: true
            );
        }
    }

    private function deletePostFiles
    (
        Post $post,
    ) : Post
    {
        FileService::deleteYourEduItemFiles(
            item: $post,
        );

        return $post->refresh();
    }

    private function deletePostType
    (
        Post $post,
        PostDTO $postDTO
    ) : Post
    {
        if ($post->hasNoTypes()) {
            return $post;
        }

        if ($post->questions?->count()) {
            $this->deletePostsQuestion($post, $postDTO);
        }

        if ($post->poems?->count()) {
            $this->deletePostsPoem($post, $postDTO);
        }

        if ($post->books?->count()) {
            $this->deletePostsBook($post, $postDTO);
        }

        if ($post->riddles?->count()) {
            $this->deletePostsRiddle($post, $postDTO);
        }

        if ($post->lessons?->count()) {
            $this->deletePostsLesson($post, $postDTO);
        }

        if ($post->activities?->count()) {
            $this->deletePostsActivity($post, $postDTO);
        }
        
        return $post->refresh();
    }

    private function deletePostsPoem(Post $post)
    {
        (new PoemService)->deletePoem(
            PoemDTO::new()
                ->withPoem($post->poems->first())
        );
    }

    private function deletePostsQuestion(Post $post)
    {
        (new QuestionService)->deleteQuestion(
            QuestionDTO::createFromData()
                ->withQuestion($post->questions->first())
        );
    }

    private function deletePostsLesson(Post $post)
    {
        (new LessonService)->deleteLesson(
            LessonDTO::new()
                ->withLesson($post->lessons->first())
        );
    }

    private function deletePostsRiddle(Post $post)
    {
        (new RiddleService)->deleteRiddle(
            RiddleDTO::new()
                ->withRiddle($post->riddles->first())
        );
    }

    private function deletePostsActivity(Post $post)
    {
        (new ActivityService)->deleteActivity(
            ActivityDTO::new()
                ->withActivity($post->activities->first())
        );
    }

    private function deletePostsBook(Post $post)
    {
        (new BookService)->deleteBook(
            BookDTO::new()
                ->withBook($post->books->first())
        );
    }

    public function getPost(PostDTO $postDTO) : Post
    {
        return $this->getModel('post', $postDTO->postId);
    }

    public function getPosts(PostsDTO $postsDTO)
    {
        return $this->getItems($postsDTO);
    }

    public function getUserPosts(PostsDTO $postsDTO)
    {
        $this->checkUser($postsDTO);

        return $this->getItems($postsDTO);
    }

    private function checkUser($postsDTO)
    {
        if (is_not_null($postsDTO->user)) {
            return;
        }

        $this->throwPostException(
            message: "sorry 😞, you are not a user.",
            data: $postsDTO
        );
    }

    public function getItems(PostsDTO $postsDTO)
    {
        $flagUserIds = $postsDTO->user ? [$postsDTO->user?->id] : null;

        if ($postsDTO->user?->isLearner()) {
            $flagUserIds = $postsDTO->user->learner->getAuthorizedIds();
        }

        $items = Post::query()
        ->wherePostTypes($postsDTO->postType)
        ->whereFiltered($postsDTO)
        ->wherePublished()
        ->whereDoesntHaveApprovedFlags()
        ->whereDoesntHaveFlagsFrom($flagUserIds)
        ->withRelations()
        ->get();

        $items = $items->merge(
            Discussion::query()
            ->whereSocial()
            ->whereFiltered($postsDTO)
            ->whereDoesntHaveApprovedFlags()
            ->whereDoesntHaveFlagsFrom($flagUserIds)
            ->withRelations()
            ->get()
        );

        $items = $items->merge(
            Assessment::query()
            ->whereSocial()
            ->wherePublished()
            ->whereFiltered($postsDTO)
            ->whereDoesntHaveApprovedFlags()
            ->whereDoesntHaveFlagsFrom($flagUserIds)
            ->withRelations()
            ->get()
        );

        return paginate($items->sortByDesc('updated_at'), self::PAGINATION);
    }
}