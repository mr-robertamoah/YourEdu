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
            'participants' => $this->when($this->isSocial(), DiscussionParticipantResource::collection($this->participants)),
            'pendingJoinParticipants' => $this->when($this->isSocial(), DiscussionPendingParticipantsResource::collection(
                $this->pendingJoinParticipants->pluck('requestfrom'))),
            'flags' => $this->when($this->isSocial(), FlagResource::collection($this->flags)),
        ];
    }
}
