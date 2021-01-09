<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //this should be used for the details of dashboard items
        $data = [];
        $data['type'] = getAccountString($this->resource);
        $data['id'] = $this->id;

        if ($data['type'] === 'class') {      
            $data['name'] = $this->name;
            $data['addedby'] = new UserAccountResource($this->addedby);
            $data['ownedby'] = new UserAccountResource($this->ownedby);
            $data['description'] = $this->description;
            $data['maxLearners'] = $this->max_learners;
            $data['state'] = $this->state;
            $data['curricula'] = $this->curricula;
            $data['grades'] = GradeResource::collection($this->grades);
            $data['lessons'] = LessonResource::collection($this->lessons);
            $data['facilitators'] = UserAccountResource::collection($this->facilitators);
            $data['learners'] = UserAccountResource::collection($this->learners);
            $data['sections'] = $this->sections;
            $data['collaborations'] = $this->collaborations;
            $data['academicYear'] = DashboardAcademicYearResource::collection($this->academicYear);
            $data['discussions'] = $this->discussions;
            $data['fees'] = $this->fees;
            $data['subjects'] = SubjectResource::collection($this->subjects);
            $data['extracurriculums'] = $this->extracurriculums;
        } else if ($data['type'] === 'course') {      
            $data['name'] = $this->name;
            $data['state'] = $this->state;
            $data['addedby'] = new UserAccountResource($this->addedby);
            $data['ownedby'] = new UserAccountResource($this->ownedby);
            $data['description'] = $this->description;
            $data['grades'] = DashboardAttachmentResource::collection($this->grades);
            $data['courses'] = DashboardAttachmentResource::collection($this->courses);
            $data['programs'] = DashboardAttachmentResource::collection($this->programs);
            $data['lessons'] = LessonResource::collection($this->lessons);
            $data['facilitators'] = UserAccountResource::collection($this->facilitators);
            $data['learners'] = UserAccountResource::collection($this->learners);
            $data['parents'] = UserAccountResource::collection($this->parents);
            $data['professionals'] = UserAccountResource::collection($this->professionals);
            $data['collaborations'] = $this->collaborations;
            $data['discussions'] = $this->discussions;
            $data['prices'] = PaymentTypeResource::collection($this->prices);
            $data['subscriptions'] = PaymentTypeResource::collection($this->subscriptions);
        } else if ($data['type'] === 'school') {    
            $data['name'] = $this->company_name;
            $data['classes'] = ClassResource::collection($this->ownedClasses);
            $data['academicYearSection'] = $this->academicYearSections;
            $data['about'] = $this->about;
            $data['courses'] = $this->courses;
            $data['role'] = $this->role;
            $data['types'] = $this->types;
            $data['learners'] = DashboardSchoolAccountResource::collection($this->learners);
            $data['professionals'] = DashboardSchoolAccountResource::collection($this->professionals);
            $data['facilitators'] = DashboardSchoolAccountResource::collection($this->facilitators);
            $data['discussions'] = $this->discussions;
            $data['extracurriculums'] = $this->extracurriculums;
        } else if ($data['type'] === 'post') {
            $type = null;
            $typeName = $data['type'];
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

            $data['type'] = $type;
            $data['typeName'] = $typeName;
            $data['content'] = $this->content;
            if ($this->images()->exists()) {
                $data['images'] = ImageResource::collection($this->images);
            }
            if ($this->videos()->exists()) {
                $data['videos'] = VideoResource::collection($this->videos);
            }
            if ($this->audios()->exists()) {
                $data['audios'] = AudioResource::collection($this->audios);
            }
            if ($this->videos()->exists()) {
                $data['files'] = FileResource::collection($this->files);
            }
            $data['likes'] = LikeResource::collection($this->likes);
            $data['comments_number'] = $this->comments()->count();
            $data['comments'] = CommentResource::collection($this->comments()
                ->orderby('updated_at','desc')->take(1)->get());
            $data['postedby'] = $this->postedby->name;
            $data['postedby_type'] = $this->postedby_type;
            $data['postedby_id'] = $this->postedby_id;
            $data['profile_url'] = $this->postedby->profile->url;
            $data['flags'] = FlagResource::collection($this->flags);
            $data['saves'] = SaveResource::collection($this->beenSaved);
            $data['attachments'] = PostAttachmentResource::collection($this->attachments);
        } else if ($data['type'] === 'comment') {
            if ($this->images()->exists()) {
                $data['images'] = ImageResource::collection($this->images);
            }
            if ($this->videos()->exists()) {
                $data['videos'] = VideoResource::collection($this->videos);
            }
            if ($this->audios()->exists()) {
                $data['audios'] = AudioResource::collection($this->audios);
            }
            if ($this->videos()->exists()) {
                $data['files'] = FileResource::collection($this->files);
            }

            $data['body'] = $this->body;
            $data['likes'] = LikeResource::collection($this->likes);
            $data['comments'] = $this->comments()->count();
            $data['flags'] = FlagResource::collection($this->flags);
            $data['saves'] = SaveResource::collection($this->beenSaved);
            $data['commentedby'] = $this->commentedby->name;
            $data['profile_url'] = $this->commentedby->profile->url;
            $data['commentedby_type'] = $this->commentedby_type;
            $data['commentedby_id'] = $this->commentedby_id;
            $data['commentable_type'] = $this->commentable_type;
            $data['commentable_id'] = $this->commentable_id;
        } else if ($data['type'] === 'answer') {
            $data['possible_answer'] = $this->possible_answer_id;
            $data['answer'] = $this->answer;
            $data['avg_score'] = $this->marks()->avg('score');
            $data['max_score'] = $this->marks()->max('score');
            $data['min_score'] = $this->marks()->min('score');
            $data['marks'] = MarkResource::collection($this->marks);
            $data['url'] = $this->answeredby->profile->url;
            $data['images'] = ImageResource::collection($this->images);
            $data['videos'] = VideoResource::collection($this->videos);
            $data['audios'] = AudioResource::collection($this->audios);
            $data['likes'] = LikeResource::collection($this->likes);
            $data['comments_number'] = $this->comments()->count();
            $data['answeredby_name'] = $this->answeredby->name;
            $data['answerable_type'] = $this->answerable_type;
            $data['answerable_id'] = $this->answerable_id;
        }

        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;

        return $data;
    }
}
