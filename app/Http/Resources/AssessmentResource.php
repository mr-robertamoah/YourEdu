<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'isAssessment' => true,
            'id' => $this->id,
            'name' => $this->name,
            'state' => $this->state,
            'description' => $this->description,
            'totalMark' => $this->total_mark,
            'duration' => $this->duration,
            'publishedAt' => $this->published_at?->diffForHumans(),
            'dueAt' => $this->due_at?->diffForHumans(),
            'addedby' => new UserAccountResource($this->addedby),
            'assessmentSections' => AssessmentSectionResource::collection(
                $this->assessmentSections()->orderedByPosition()->get()
            ),
            'createdAt' => $this->created_at->diffForHumans(),
            'restricted' => $this->restricted,
            'type' => $this->type,
            'discussions' => $this->discussions->count(),
            'worksCount' => $this->works->count(),
            'items' => DashboardItemMiniResource::collection($this->items()),
            'comments' => $this->when($this->isSocial(), $this->latestComments()),
            'commentsCount' => $this->when($this->isSocial(), $this->commentsCount()),
            'markers' => $this->when($this->isSocial(), UserAccountResource::collection($this->markers())),
            'saves' => $this->when($this->isSocial(), SaveResource::collection($this->saves())),
            'answeredbyUserIds' => $this->when($this->isSocial(), $this->answeredbyUserIds()),
            'likes' => $this->when($this->isSocial(), LikeResource::collection($this->likes)),
            'participants' => $this->when($this->isSocial(), ParticipantResource::collection($this->nonPendingParticipants())),
            'pendingParticipants' => $this->when($this->isSocial(), DiscussionPendingParticipantsResource::collection(
                $this->pendingParticipantAccounts()
            )),
            'timer' => $this->when(
                $request->user() && $this->hasTimerAddedbyUser($request->user()->id),
                new TimerResource($this->timerAddedbyUser($request->user()->id)),
                null
            ),
            'flags' => $this->when($this->isSocial(), FlagResource::collection($this->flags)),
        ];
    }
}
