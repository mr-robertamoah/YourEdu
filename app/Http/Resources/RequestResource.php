<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \Debugbar;
use Illuminate\Support\Arr;

class RequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        $data['id'] = $this->id;

        if ($this->isFollowRequest()) {
            $data['account_type'] = class_basename_lower($this->requestfrom_type);
            $data['account_id'] = $this->requestfrom_id;
            $data['myAccount'] = class_basename_lower($this->requestto_type);
            $data['myAccountId'] = $this->requestto_id;
            $data['myName'] = $this->requestto->profile->name;
            $data['name'] = $this->requestfrom->profile->name;
            $data['url'] = $this->requestfrom->profile->url;
            $data['isAccount'] = true;
            $data['userId'] = $this->requestfrom->user_id;

            return $data;
        }

        if ($this->isMessageRequest()) {

            $images = null;
            $videos = null;
            $audios = null;
            $files = null;

            $data['userId'] = $this->requestfrom->user_id;
            if ($this->requestable->images()->exists()) {
                $images = ImageResource::collection($this->requestable->images);
            }
            if ($this->requestable->videos()->exists()) {
                $videos = VideoResource::collection($this->requestable->videos);
            }
            if ($this->requestable->audios()->exists()) {
                $audios = AudioResource::collection($this->requestable->audios);
            }
            if ($this->requestable->files()->exists()) {
                $files = FileResource::collection($this->requestable->files);
            }

            $data['isMessage'] = true;
            $data['images'] = $images;
            $data['videos'] = $videos;
            $data['audios'] = $audios;
            $data['files'] = $files;
            $data['message'] = $this->requestable->message;
            $data['messageId'] = $this->requestable->id;
            $data['fromable_type'] = $this->requestfrom_type;
            $data['fromable_id'] = $this->requestfrom_id;
            $data['fromable_name'] = $this->requestfrom->profile->name;
            $data['fromable_url'] = $this->requestfrom->profile->url;

            return $data;
        }

        if ($this->isDiscussionRequest()) {
            $userId = $request->user()?->id;

            $isAdmin = $this->requestable->isAdmin($userId);
            if ($isAdmin) {
                $data['type'] = 'join';
                $data['message'] = "has requested to join your discussion with title: {$this->title}";
            }

            if (!$isAdmin) {
                $data['type'] = 'invitation';
                $data['message'] = "has invited you to be take part in the discussion with title: {$this->title}";
            }

            $data['isDiscussionRequest'] = true;
            $data['item'] = 'discussion';
            $data['createdAt'] = $this->requestable->created_at->diffForHumans();
            $data['myAccount'] = new UserAccountResource($this->getMyAccount($userId));
            $data['account'] = new UserAccountResource($this->getOtherAccount($userId));
            $data['itemId'] = $this->requestable->id;

            return $data;
        }

        if ($this->isAssessmentRequest()) {

            $userId = $request->user()?->id;
            $data['account'] = new UserAccountResource($this->getOtherAccount($userId));
            $data['myAccount'] = new UserAccountResource($this->getMyAccount($userId));

            $markerMessage = '';
            if ($this->isMarkerRequest()) {
                $data['marker'] = true;
                $markerMessage = ', as marker';
            }

            $isAddedby = $this->requestable->addedby->user_id === $userId;
            if ($isAddedby) {
                $data['type'] = 'join';
                $data['message'] = "has requested to join your assessment with name: {$this->name}{$markerMessage}";
            }

            if (!$isAddedby) {
                $data['type'] = 'invitation';
                $data['message'] = "has invited you to be take part in the assessment with name: {$this->name}{$markerMessage}";
            }

            $data['isAssessmentRequest'] = true;
            $data['userId'] = $this->requestfrom->user_id;
            $data['item'] = 'assessment';
            $data['createdAt'] = $this->requestable->created_at->diffForHumans();
            $data['itemId'] = $this->requestable->id;

            return $data;
        }

        if (!$this->data) {
            return $data;
        }

        $requestDTO = unserialize($this->data);

        $data['images'] = ImageResource::collection($this->images);
        $data['videos'] = VideoResource::collection($this->videos);
        $data['audios'] = AudioResource::collection($this->audios);
        $data['files'] = FileResource::collection($this->files);
        $data['salaries'] = PaymentTypeResource::collection($this->salaries);
        $data['commissions'] = PaymentTypeResource::collection($this->commissions);
        $data['fees'] = PaymentTypeResource::collection($this->fees);
        $data['discounts'] = PaymentTypeResource::collection($this->discounts);
        $data['action'] = $requestDTO->action;
        $data['message'] = $requestDTO->message;
        $data['state'] = strtolower($this->state);
        $data['createdAt'] = $this->created_at->diffForHumans();
        $data['account'] = new UserAccountResource($this->requestfrom);
        $data['myAccount'] = new UserAccountResource($this->requestto);

        return $data;
    }
}
