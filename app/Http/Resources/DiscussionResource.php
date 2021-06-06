<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscussionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $images = null;
        $videos = null;
        $audios = null;
        $files = null;

        if ($this->images->count()) {
            $images = ImageResource::collection($this->images);
        } 
        if ($this->videos()->exists()) {
            $videos = VideoResource::collection($this->videos);
        } 
        if ($this->audios()->exists()) {
            $audios = AudioResource::collection($this->audios);
        } 
        if ($this->videos()->exists()) {
            $files = FileResource::collection($this->files);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'preamble' => $this->preamble,
            'restricted' => $this->restricted,
            'type' => $this->type,
            'allowed' => strtolower($this->allowed),
            'isDiscussion' => true,
            'likes' => LikeResource::collection($this->likes),
            'messages' => DiscussionMessageResource::collection($this->messages()
                ->where('state','ACCEPTED')
                ->orderby('updated_at','desc')->take(2)->get()),
            'comments' => CommentResource::collection($this->comments()
                ->orderby('updated_at','desc')->take(1)->get()),
            'raisedby' => new UserAccountResource($this->raisedby),
            'flags' => FlagResource::collection($this->flags),
            'saves' => SaveResource::collection($this->beenSaved),
            'attachments' => PostAttachmentResource::collection($this->attachments),
            'participants' => DiscussionParticipantResource::collection($this->participants),
            'pendingJoinParticipants' => DiscussionPendingParticipantsResource::collection(
                $this->pendingJoinParticipants->pluck('requestfrom')),
            'images' => $images,
            'videos' => $videos,
            'audios' => $audios,
            'files' => $files,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
