<?php

namespace App\DTOs;

use App\User;
use Illuminate\Http\Request;

class PostsDTO
{
    public ?User $user = null;
    public ?string $postType = null;
    public ?string $mine = null;
    public ?string $followers = null;
    public ?string $followings = null;
    public ?string $attachment = null;
    public ?string $attachedWith = null;
    public ?string $attachedWithId = null;

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->user = $request->user();
        $self->postType = $request->postType;
        $self->mine = $request->has('mine');
        $self->followings = $request->has('followings');
        $self->followers = $request->has('followers');
        $self->attachments = $request->has('attachments');

        if ($self->attachments) {
            $self->attachedWith = self::getAttachmentClass($request);
            $self->attachedWithId = $request->attachedWithId;
        }

        return $self;
    }

    public static function getAttachmentClass($request)
    {
        if ($request->attachedWith === 'subjects') {
            return 'App\YourEdu\Subject';
        }
        
        if ($request->attachedWith === 'grades') {
            return 'App\YourEdu\Grade';
        }
        
        if ($request->attachedWith === 'curriculum') {
            return 'App\YourEdu\Curriculum';
        }

        return null;
    }
}
