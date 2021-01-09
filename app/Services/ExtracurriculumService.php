<?php

namespace App\Services;

use App\Events\DeleteExtracurriculumEvent;
use App\Events\NewExtracurriculumEvent;
use App\Events\UpdateExtracurriculumEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\ExtracurriculumException;
use App\Http\Resources\ExtracurriculumResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\ExtracurriculumCreatedNotification;
use App\Traits\ClassCourseTrait;
use App\User;
use App\YourEdu\Extracurriculum;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ExtracurriculumService 
{
    use ClassCourseTrait;
    /**
     * create an extracurriculum
     * @return Extracurriculum
     */
    public function createExtracurriculum($account,$accountId,$id,$extracurriculumData) : Extracurriculum
    {
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        if ($mainAccount->user_id !== $id) {
            throw new ExtracurriculumException("you do not own this account");
        }

        $extracurriculum = $mainAccount->addedExtracurriculums()->create([
            'name' => $extracurriculumData['name'],
            'state' => $extracurriculumData['owner'] === 'school' && 
                ($account !== 'admin' || $account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $extracurriculumData['description'],
        ]);

        if (is_null($extracurriculum)) {
            throw new ExtracurriculumException("extracurriculum creation failed");
        }

        if ($account === $extracurriculumData['owner'] && $accountId === $extracurriculumData['ownerId']) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getAccountObject($extracurriculumData['owner'],$extracurriculumData['ownerId']);
            if (is_null($mainOwner)) {
                throw new AccountNotFoundException("{$extracurriculumData['owner']} not found with id {$extracurriculumData['ownerId']}");
            }
        }        

        $extracurriculum->ownedby()->associate($mainOwner);
        $extracurriculum->save();

        if ($extracurriculumData['facilitate']) {
            self::extracurriculumAttachItem($extracurriculum->id,$mainAccount,'facilitate');
        }

        //attachments like extracurriculums programs grades
        $this->createAttachments(
            $extracurriculum,
            $mainAccount,
            $extracurriculumData['attachments'],
            $extracurriculumData['facilitate']
        );

        //for classes we may attach extracurriculum to
        if (is_array($extracurriculumData['classes'])) {
            foreach ($extracurriculumData['classes'] as $cl) {
                $actualClass = getAccountObject('class',$cl->id);
                if (!is_null($actualClass)) {                
                    $actualClass->extracurriculums()->attach($extracurriculum->id);
                    $actualClass->save();
                }
            }
        } else {
            $extracurriculumData['classes'] = [];
        }

        //set payment information
        $this->setPayment(
            item: $extracurriculum,
            addedby: $mainAccount,
            paymentType: $extracurriculumData['type'],
            paymentData: $extracurriculumData['paymentData'],
        );

        //create auto discussion 
        $this->createAutoDiscussion(
            item: $extracurriculum,
            account: $account,
            accountId: $accountId,
            discussionData: $extracurriculumData['discussionData'],
            files: $extracurriculumData['discussionFiles'],
        );

        //track school activities
        if ($account === 'admin') {
            (new ActivityTrackService())->createActivityTrack(
                $extracurriculum,$mainOwner,$mainAccount,__METHOD__
            );
        }

        if ($extracurriculum->state === 'PENDING') { //it is only pending if it belongs to school and it is created by a non owner or non admin
            $userIds = array_filter(getAdminIds($mainOwner),function($userId) use ($id){
                return $userId !== $id;
            });
            Notification::send(User::whereIn('id',$userIds)->get(), 
                new ExtracurriculumCreatedNotification(
                    new UserAccountResource($mainAccount),
                    "created a extracurriculum with the name: {$extracurriculum->name}, 
                    for {$mainOwner->name}. Please go to dashboard to approve or otherwise."
                )
            );
        }

        if ($extracurriculumData['owner'] === 'school') {
            broadcast(new NewExtracurriculumEvent([
                'account' => $extracurriculumData['owner'],
                'accountId' => $extracurriculumData['ownerId'],
                'classes' => $extracurriculumData['classes'],
                'extracurriculum' => new ExtracurriculumResource($extracurriculum),
            ]))->toOthers();
        }

        return $extracurriculum;
    }

    /**
     * attach extracurriculum and facilitator to attachments like course, programs, grades
     */
    
    public function getExtracurriculum($extracurriculumId)
    {
        $extracurriculum = getAccountObject('extracurriculum',$extracurriculumId);
        if (is_null($extracurriculum)) {
            throw new AccountNotFoundException("extracurriculum not found with id {$extracurriculumId}");
        }

        return $extracurriculum;
    }
    
    public function getExtracurriculums()
    {
        
    }
    
    public function updateExtracurriculum($account,$accountId,$extracurriculumId,$userId,$extracurriculumData)
    {
        //check account
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$account not found with id {$extracurriculumId}");
        }
        //check extracurriculum
        $extracurriculum = getAccountObject('extracurriculum',$extracurriculumId);
        if (is_null($extracurriculum)) {
            throw new AccountNotFoundException("extracurriculum not found with id {$extracurriculumId}");
        }

        //check authorization
        $this->checkExtracurriculumAuthorization($extracurriculum,$userId);

        //update extracurriculum attributes
        $extracurriculum->update([
            'name' => $extracurriculumData['name'],
            'state' => Str::upper($extracurriculumData['state']),
            'description' => $extracurriculumData['description'],
        ]);

        //update extracurriculum relations
        if ($extracurriculumData['facilitate']) { //facilitate
            self::extracurriculumAttachItem($extracurriculumId,$mainAccount,'facilitate');
        } else {
            self::extracurriculumUnattachItem($extracurriculumId,$mainAccount);
        }

        $this->createAttachments( //attachments like extracurriculums programs grades
            $extracurriculum,
            $mainAccount,
            $extracurriculumData['attachments'],
            $extracurriculumData['facilitate']
        );  

        //track school activities
        if ($account === 'admin') {
            (new ActivityTrackService())->createActivityTrack(
                $extracurriculum,$extracurriculum->ownedby,$mainAccount,__METHOD__
            );
        }   

        //broadcast
        $ownedby = $extracurriculum->ownedby;
        $classes = $extracurriculum->classes;

        if (getAccountString($extracurriculum) === 'school') {            
            broadcast(new UpdateExtracurriculumEvent([
                'account' => getAccountString($ownedby),
                'accountId' => $ownedby->id,
                'classes' => $classes,
                'extracurriculumId' => $extracurriculumId,
            ]))->toOthers();
        }   

        //return extracurriculum
        return $extracurriculum;
    }

    public function checkExtracurriculumAuthorization($extracurriculum,$userId)
    {
        if (!$this->checkAuthorization($extracurriculum,$userId)) {
            throw new ExtracurriculumException("You are not authorized to edit or delete the extracurriculum with id {$extracurriculum->id}");
        }
    }

    public function deleteextracurriculum($extracurriculumId,$userId,$adminId,$action)
    {
        $extracurriculum = getAccountObject('extracurriculum',$extracurriculumId);
        if (is_null($extracurriculum)) {
            throw new AccountNotFoundException("extracurriculum not found with id {$extracurriculumId}");
        }

        $this->checkExtracurriculumAuthorization($extracurriculum,$userId);

        if ($adminId) {
            $admin = getAccountObject('admin',$adminId);
            (new ActivityTrackService())->createActivityTrack(
                $extracurriculum,$extracurriculum->ownedby,$admin,__METHOD__
            );
        }
        $ownedby = $extracurriculum->ownedby;

        if ($action === 'undo') {
            return $this->changeState($ownedby,null,$extracurriculum,'accepted');
        } else if($action === 'delete') {
            //check if someone has subsribed or paid or used by a program
            if ($this->paymentMadeFor($extracurriculum) || $this->usedByAnother($extracurriculum)) {
                return $this->changeState($ownedby,null,$extracurriculum,'deleted');
            } else {

                $extracurriculum->delete();
                
                broadcast(new DeleteExtracurriculumEvent([
                    'account' => getAccountString($ownedby),
                    'accountId' => $ownedby->id,
                    'extracurriculumId' => $extracurriculumId,
                ]))->toOthers();

                return null;
            }
        }
    }

    private function changeCourseState($ownedby,$classes,$course,$state)
    {
        $course->update(['state' => Str::upper($state)]);
                
        $this->broadcastUpdate($ownedby,$classes,$course);

        return $course;
    }

    private function broadcastUpdate($ownedby,$classes,$course)
    {
        broadcast(new UpdateExtracurriculumEvent([
            'account' => getAccountString($ownedby),
            'accountId' => $ownedby->id,
            'course' => new ExtracurriculumResource($course),
        ]))->toOthers();
    }

    private function paymentMadeFor($extracurriculum)
    {
        if (
            $extracurriculum->whereHas('payments')
                ->orWhereHas('programs',function($query) {
                    $query->whereHas('payments');
                })
                ->orWhereHas('classes',function($query) {
                    $query->whereHas('payments');
                })
                ->first()
        ) {
            return true;
        }
        return false;
    }

    private function usedByAnother($extracurriculum)
    {
        if ($extracurriculum->programs->whereNotNull('ownedby_type')->first() ||
            $extracurriculum->classes->whereNotNull('ownedby_type')->first()) {
            return true;
        }
        return false;
    }

    public static function extracurriculumAttachItem($extracurriculumId,$item,$activity)
    {
        if (is_null(
            $item->extracurriculums->where('id',$extracurriculumId)->first()
        )) {
            $item->extracurriculums()->attach($extracurriculumId,['activity' => Str::upper($activity)]);
            $item->save();
        }
    }

    public static function extracurriculumUnattachItem($extracurriculumId,$item)
    {
        if (!is_null(
            $item->extracurriculums->where('id',$extracurriculumId)->first()
        )) {
            $item->extracurriculums()->detach($extracurriculumId);
            $item->save();
        }
    }
}
