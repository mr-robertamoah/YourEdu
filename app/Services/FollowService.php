<?php

namespace App\Services;

use App\DTOs\FollowDTO;
use App\DTOs\RequestDTO;
use App\Events\DeleteFollow;
use App\Events\NewFollow;
use App\Events\NewFollowBack;
use App\Exceptions\FollowException;
use App\Traits\ServiceTrait;
use App\YourEdu\Follow;
use App\YourEdu\Request;
use App\YourEdu\School;
use \Debugbar;
use Illuminate\Database\Eloquent\Builder;

class FollowService
{
    use ServiceTrait;

    public function getFollowers($userId)
    {
        $followers = Follow::query()
            ->withAccounts()
            ->whereHasMorph('followable', '*', function($query) use ($userId) {
                $query->whereUser($userId);
            })
            ->whereNotNull('followedby_type')
            ->get();

        return $followers;
    }

    public function getFollowings($userId)
    {
        $followings = Follow::query()
            ->withAccounts()
            ->whereHasMorph('followedby', '*', function($query) use ($userId) {
                $query->whereUser($userId);
            })
            ->whereNotNull('followable_type')
            ->get();

        return $followings;
    }

    private function getFollow($followDTO)
    {
        if (is_not_null($followDTO->follow)) {
            return $followDTO->follow;
        }

        return $this->getModel('follow', $followDTO->followId);
    }

    private function checkFollowOwnership($followDTO)
    {
        if ($followDTO->follow->followedby->isUser($followDTO->userId)) {
            return;
        }

        $this->throwFollowException(
            message: "charlie you cannot unfollow because you are not the one following 😏",
            data: $followDTO
        );
    }

    public function unfollow(FollowDTO $followDTO)
    {
        $follow = $this->getFollow($followDTO);

        $followDTO = $followDTO->withFollow($follow);

        $this->checkFollowOwnership($followDTO);

        $follow->delete();
        
        $followDTO->methodType = 'deleted';
        $this->broadcastFollow($followDTO);
    }

    public function followBack(FollowDTO $followDTO)
    {
        $request = $this->getFollowRequest($followDTO);
        
        $followDTO = $followDTO->withFollow($request->requestable);

        $followDTO = $followDTO->withFollowable($request->requestfrom);

        $followDTO = $followDTO->withFollowedby($request->requestto);

        $this->checkAccountOwnership(
            $request->requestto,
            $followDTO->userId
        );
        
        $follow = $this->associateFollowableToFollow($followDTO);

        $follow = $this->associateFollowedbyToFollow($followDTO);

        $request->update([
            'state' => 'ACCEPTED'
        ]);

        $this->broadcastFollow($followDTO);

        return $this->withLoadedRelationships($follow);        
    }

    private function withLoadedRelationships($follow)
    {
        return $follow->load(['followedby.profile','followable.profile']);
    }

    private function getFollowRequest($followDTO)
    {
        $followDTO = $this->setFollowable($followDTO);
        
        $followDTO = $this->setFollowedby($followDTO);
        
        $requests = $followDTO->followedby->pendingFollowRequestsSentByAccount($followDTO->followable);
        
        if ($requests->count()) {
            return $requests->first();
        }
        
        $this->throwAccountNotFoundException(
            "follow request was not found"
        );
    }

    private function checkFollowRequestOwnership($request, $followDTO)
    {
        if ($request->requestto->isUser($followDTO->userId)) {
            return;
        }

        $this->throwFollowException(
            message: "charlie you cannot decline this request because it is not for you 😏",
            data: $followDTO
        );
    }

    public function declineFollowRequest(FollowDTO $followDTO)
    {
        $request = $this->getFollowRequest($followDTO);

        $this->checkFollowRequestOwnership($request, $followDTO);

        $request->update([
            'state' => 'DECLINED'
        ]);
    }

    private function throwFollowException($message, $data = null)
    {
        throw new FollowException(
            message: $message,
            data: $data
        );
    }

    private function ensureNotFollowingOwnAccount($followDTO)
    {
        if ($followDTO->followable->user_id !== $followDTO->followedby->user_id) {
            return;
        }
        
        $this->throwFollowException(
            message: "unsuccessful. you cannot follow one of your own accounts",
            data: $followDTO
        );
    }

    private function ensureNotAlreadyFollowingFollowable($followDTO)
    {
        if ($followDTO->followable->notFollowedbyUser($followDTO->userId)) {
            return;
        }

        $this->throwFollowException(
            message: "unsuccessful. you already follow this account",
            data: $followDTO
        );
    }

    private function addFollow()
    {
        return Follow::create();
    }

    private function associateFollowableToFollow($followDTO)
    {
        $followDTO->follow->followable()->associate($followDTO->followable);
        $followDTO->follow->save();

        return $followDTO->follow;
    }

    private function associateFollowedbyToFollow($followDTO)
    {
        $followDTO->follow->followedby()->associate($followDTO->followedby);
        $followDTO->follow->save();

        return $followDTO->follow;
    }

    private function ensureFollowableDoesntHavePendingRequestFromFollowedby($followDTO)
    {
        if ($followDTO->followable->doesntHavePendingFollowRequestsSentByUser($followDTO->userId)) {
            return;
        }

        $this->throwFollowException(
            message: "unsuccessful. you have a pending request to this account."
        );
    }

    private function setFollowedby(FollowDTO $followDTO)
    {
        if (is_not_null($followDTO->followedby)) {
            return $followDTO;
        }

        return $followDTO->withFollowedby(
            $this->getModel($followDTO->myAccount, $followDTO->myAccountId)
        );
    }

    private function setFollowable(FollowDTO $followDTO)
    {
        if (is_not_null($followDTO->followable)) {
            return $followDTO;
        }

        return $followDTO->withFollowable(
            $this->getModel($followDTO->otherAccount, $followDTO->otherAccountId)
        );
    }

    public function follow(FollowDTO $followDTO)
    {
        $followDTO = $this->setFollowedby($followDTO);
        
        $this->checkAccountOwnership(
            $followDTO->followedby,
            $followDTO->userId
        );

        $followDTO = $this->setFollowable($followDTO);
       
        $this->ensureNotFollowingOwnAccount($followDTO);
        
        $this->ensureNotAlreadyFollowingFollowable($followDTO);

        $this->ensureFollowableDoesntHavePendingRequestFromFollowedby($followDTO);
        
        $follow = $this->addFollow();

        $followDTO = $followDTO->withFollow($follow);

        $follow = $this->associateFollowableToFollow($followDTO);

        $follow = $this->associateFollowedbyToFollow($followDTO);

        $this->createFollowRequest($followDTO);

        $followDTO->methodType = 'created';
        $this->broadcastFollow($followDTO);

        return $this->withLoadedRelationships($follow);
    }

    private function createFollowRequest($followDTO)
    {
        if ($followDTO->followedby->hasPendingFollowRequestsSentByUser($followDTO->followable->user_id)) {
            return;
        }

        $requestDTO = RequestDTO::new()
            ->withRequestable($this->addFollow())
            ->withRequestto($followDTO->followable)
            ->withRequestfrom($followDTO->followedby);

        (new RequestService)->createFollowRequest($requestDTO);
    }

    private function broadcastFollow($followDTO)
    {
        $event = $this->getEvent($followDTO);
        
        broadcast($event)->toOthers();
    }

    private function getEvent($followDTO)
    {
        if ($followDTO->methodType == 'created') {
            return new NewFollow($followDTO);
        }
        
        if ($followDTO->methodType == 'deleted') {
            return new DeleteFollow($followDTO);
        }

        return new NewFollowBack($followDTO);
    }

    public function followRequests($userId)
    {
        $schoolOwnerIds = School::whereHas('admins', function(Builder $query) use ($userId){
            $query->where('user_id',$userId);
        })->get()->pluck('owner_id')->toArray();

        $requests = Request::query()
            ->whereFollowRequest()
            ->wherePending()
            ->whereHasMorph('requestto','*',function($builder,$type) use ($userId, $schoolOwnerIds) {
                if ($type === 'App\YourEdu\School') {
                    $builder->whereIn('owner_id', $schoolOwnerIds);
                } else {
                    $builder->where('user_id',$userId);
                }
            })
            ->latest()
            ->paginate(10);

        return $requests;
    }

    public static function updateFollowFillable
    (
        Follow $follow,
        $fillable,
        $data
    ) : Follow
    {
        $follow->$fillable = $data;
        $follow->save();
        
        return $follow;
    }
}