<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use \Debugbar;

class DiscussionPostResource extends JsonResource
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
        $data = [];

        if ($this->images()->exists()) {
            $images = ImageResource::collection($this->images);
        } 
        if ($this->videos()->exists()) {
            $videos = VideoResource::collection($this->videos);
        } 
        if ($this->audios()->exists()) {
            $audios = AudioResource::collection($this->audios);
        } 
        if ($this->files()->exists()) {
            $files = FileResource::collection($this->files);
        }

        $data['images'] = $images;
        $data['videos'] = $videos;
        $data['audios'] = $audios;
        $data['files'] = $files;
        $data['id'] = $this->id;
        $data['updated_at'] = $this->updated_at;
        $data['created_at'] = $this->created_at;
        $data['likes'] = LikeResource::collection($this->likes);
        $data['flags'] = FlagResource::collection($this->flags);
        $data['saves'] = SaveResource::collection($this->beenSaved);
        $data['attachments'] = PostAttachmentResource::collection($this->attachments);
        $data['comments'] = CommentResource::collection($this->comments()
            ->orderby('updated_at','desc')->take(1)->get());

        if (!is_null($this->postedby_id)) {
            $type = null;
            $typeName = null;
            if ($this->books()->exists()) {
                $typeName = 'book';
                $type = BookResource::collection($this->books()->latest()->get());
            } else if ($this->poems()->exists()) {
                $typeName = 'poem';
                $type = PoemResource::collection($this->poems()->latest()->get());
            } else if ($this->riddles()->exists()) {
                $typeName = 'riddle';
                $type = RiddleResource::collection($this->riddles()->latest()->get());
            } else if ($this->activities()->exists()) {
                $typeName = 'activity';
                $type = ActivityResource::collection($this->activities()->latest()->get());
            } else if ($this->questions()->exists()) {
                $typeName = 'question';
                $type = QuestionResource::collection($this->questions()->latest()->get());
            } else if ($this->lessons()->exists()) {
                $typeName = 'lesson';
                $type = LessonResource::collection($this->lessons()->latest()->get());
            }
            $data['isPost'] = true;
            $data['content'] = $this->content;
            $data['type'] = $type;
            $data['typeName'] = $typeName;
            $data['postedby_id'] = $this->postedby_id;
            $data['postedby_type'] = $this->postedby_type;
            $data['postedby'] = $this->postedby->name;
            $data['profile_url'] = $this->postedby->profile->url;
            $data['comments_number'] = $this->comments()->count();
        } else {
            $data['title'] = $this->title;
            $data['restricted'] = $this->restricted;
            $data['type'] = $this->type;
            $data['allowed'] = $this->allowed;
            $data['preamble'] = $this->preamble;
            $data['isDiscussion'] = true;
            $data['raisedby_user_id'] = $this->raisedby->user_id;
            $data['raisedby_id'] = $this->raisedby_id;
            $data['pendingJoinParticipants'] = DiscussionPendingParticipantsResource::collection(
                $this->pendingJoinParticipants->pluck('requestfrom'));
            $data['raisedby_type'] = $this->raisedby_type;
            $data['raisedby'] = $this->raisedby->name;
            $data['profile_url'] = $this->raisedby->profile->url;
            $data['participants'] = DiscussionParticipantResource::collection($this->participants);
            $data['messages_count'] = $this->messages()->count();
            $data['messages'] = DiscussionMessageResource::collection($this->messages()
                ->where('state','ACCEPTED')
                ->orderby('updated_at','desc')->take(2)->get());
            Debugbar::info($this->participants);
        }

        return $data;
    }
}
