<?php

namespace App\YourEdu;

use App\Traits\FlaggableTrait;
use App\Traits\HasCommentsTrait;
use App\Traits\HasParticipantsTrait;
use App\Traits\ItemFilesTrait;
use App\Traits\HasSocialMediaTrait;
use Database\Factories\DiscussionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    use SoftDeletes, 
        HasFactory,
        ItemFilesTrait,
        FlaggableTrait,
        HasSocialMediaTrait,
        HasParticipantsTrait,
        HasCommentsTrait;

    const PAGINATION = 10;

    protected $fillable = [
        'title', 'preamble', 'restricted', 'type','allowed',
    ];

    protected $casts = [
        'restricted' => 'boolean'
    ];

    protected $appends = [
        'addedby'
    ];

    public function getAddedbyAttribute()
    {
        return $this->raisedby;
    }

    public function discussionfor()
    {
        return $this->morphTo();
    }

    public function raisedby()
    {
        return $this->morphTo();
    }

    public function messages()
    {
        return $this->morphMany(Message::class,'messageable');
    }

    public function likes(){
        return $this->morphMany(Like::class,'likeable');
    }

    public function files()
    {
        return $this->morphToMany(File::class,'fileable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function audios()
    {
        return $this->morphToMany(Audio::class,'audioable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function videos()
    {
        return $this->morphToMany(Video::class,'videoable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function images()
    {
        return $this->morphToMany(Image::class,'imageable')
        ->withPivot(['state'])->withTimestamps();
    }

    public function beenSaved()
    {
        return $this->morphMany(Save::class,'saveable');
    }

    public function attachments()
    {
        return $this->morphMany(PostAttachment::class,'attachable');
    }

    public function getAdmin($userId)
    {
        if ($this->raisedby->user_id == $userId) {
            return $this->raisedby;
        }

        return $this->participants()
            ->whereAdmin()
            ->whereParticipantByUserId($userId)
            ->first()?->accountable;
    }

    public function getAdmins()
    {
        $admins = $this->participants()
            ->with('accountable')
            ->whereAdmin()
            ->get()->pluck('accountable');

        $admins = $admins->merge($this->raisedby);

        return $admins;
    }

    public function isAdmin($userId)
    {
        return $this->isOwner($userId) ||
            $this->isAdminParticipant($userId);
    }

    public function isNotAdmin($userId)
    {
        return ! $this->isAdmin($userId);
    }

    public function isAdminParticipant($userId)
    {
        return $this->participants()
            ->whereAdmin()
            ->whereParticipantByUserId($userId)
            ->exists();
    }

    public function getAdminParticipants()
    {
        return $this->participants()
            ->whereAdmin()
            ->get();
    }

    public function getMessages()
    {
        return $this->messages()
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function getAcceptedMessages()
    {
        return $this->messages()
            ->whereState('ACCEPTED')
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function getMessagesByState($state)
    {
        return $this->messages()
            ->whereState($state)
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function getPaginatedMessages()
    {
        return $this->messages()
            ->orderBy('updated_at', 'desc')
            ->paginate(self::PAGINATION);
    }

    public function getPaginatedAcceptedMessages()
    {
        return $this->messages()
            ->whereState('ACCEPTED')
            ->orderBy('updated_at', 'desc')
            ->paginate(self::PAGINATION);
    }

    public function getPaginatedMessagesByState($state)
    {
        return $this->messages()
            ->whereState($state)
            ->orderBy('updated_at', 'desc')
            ->paginate(self::PAGINATION);
    }

    public function scopeWhereSocial($query)
    {
        return $query->whereNull('discussionfor_type');
    }

    public function scopeWhereNotSocial($query)
    {
        return $query->whereNotNull('discussionfor_type');
    }

    public function scopeWithRelations($query)
    {
        return $query->with([
            'images','videos','audios','files','comments','flags','attachments',
            'beenSaved','messages','raisedby.profile','requests.requestfrom']);
    }

    protected static function newFactory()
    {
        return DiscussionFactory::new();
    }
}
