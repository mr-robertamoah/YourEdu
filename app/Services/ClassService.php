<?php 

namespace App\Services;

use App\DTOs\ClassData;
use App\Events\DeleteClassEvent;
use App\Events\NewClassEvent;
use App\Events\UpdateClassEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\ClassException;
use App\Http\Resources\ClassResource;
use App\Http\Resources\DashboardItemResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\ClassCreatedNotification;
use App\Traits\ClassCourseTrait;
use App\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use \Debugbar;

class ClassService
{
    use ClassCourseTrait;
    
    public function createCLass(ClassData $classData)
    {
        $mainAccount = getYourEduModel($classData->account,$classData->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$classData->account} not found with id {$classData->accountId}");
        }

        if (!$this->checkAccountOwnership($mainAccount,$classData->userId)) {
            throw new ClassException("you do not own this account");
        }

        $class = $mainAccount->addedClasses()->create([
            'name' => $classData->name,
            'max_learners' => $classData->maxLearners,
            'state' => $classData->owner === 'school' && 
                ($classData->account !== 'admin' && $classData->account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $classData->description,
        ]);

        if (is_null($class)) {
            throw new ClassException("class creation failed");
        }

        if ($classData->account === $classData->owner && $classData->accountId === $classData->ownerId) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getYourEduModel($classData->owner,$classData->ownerId);
            if (is_null($mainOwner)) {
                throw new AccountNotFoundException("{$classData->owner} not found with id {$classData->ownerId}");
            }
        }        

        $class->ownedby()->associate($mainOwner);

        //check if ownedby school and already attached to grade

        if ($classData->owner === 'school') {
            
            GradeService::gradeAttachItem($classData->gradeId,$mainOwner);
    
            $academicYear = $mainOwner->academicYears()->whereDate('start_date','<=',now())
                ->whereDate('end_date','>=',now())->latest()->first();
    
            if (!is_null($academicYear)) {
                $class->academicYear()->attach($academicYear);
            } else {
                throw new ClassException("There is no current academic year. Please create one to be able to continue with this.");
            }

            $class->structure = strlen($classData->structure) > 1 ? $classData->structure : 
                $class->ownedby->class_structure;
        } else {
            $class->structure = $classData->structure;
        }

        $class->save();

        $grade = getYourEduModel('grade',$classData->gradeId);
        if (is_null($grade)) {
            throw new AccountNotFoundException("grade not found with id {$classData->gradeId}");
        }

        $this->itemCreateUpdateMethodParts(
            $class,
            $mainAccount,
            $classData,
            __METHOD__
        );

        if ($class->state === 'PENDING') {
            $userIds = array_filter(
                $mainOwner->getAdminIds(),function($userId) use ($classData){
                return $userId !== $classData->userId;
            });
            $name = $mainOwner->name ?? $mainAccount->company_name;
            Notification::send(User::find($userIds), 
                new ClassCreatedNotification(
                    new UserAccountResource($mainAccount),
                    "created a class with the name: {$class->name} for grade: {$grade->name}, 
                    for {$name}. Please go to dashboard to approve or otherwise."
                )
            );
        }

        if ($classData->owner === 'school' && $class->state !== 'PENDING') {
            broadcast(new NewClassEvent([
                'account' => $classData->owner,
                'accountId' => $classData->ownerId,
                'class' => new ClassResource($class),
            ]))->toOthers();
        }

        return $class;
    }

    private function attachFacilitator($account,$class,$gradeId,$facilitate)
    {
        if ($facilitate) {
            $account->classes()->attach($class);
            $account->save();

            //check if facilitator is already attached to grade
            GradeService::gradeAttachItem($gradeId,$account);
        }

        //attach class to grade
        if ($gradeId && $class) { //unattach the previous grade
            $class->grades()->attach($gradeId);
            $class->save();
        }
    }

    public function getClass()
    {
        
    }

    private function itemCreateUpdateMethodParts
    (
        $class,
        $mainAccount,
        ClassData $classData,
        $method
    )
    {
        $this->attachFacilitator(
            account: $mainAccount,
            class: $class,
            facilitate: $classData->facilitate,
            gradeId: $classData->gradeId
        );

        $this->setPayment(
            item: $class,
            addedby: $mainAccount,
            paymentType: $classData->type,
            paymentData: $classData->paymentData,
            academicYears: $classData->academicYears
        );

        //attach academic years
        $this->createMainAttachments( 
            attachments: $classData->academicYears,
            method: 'classes',
            itemId: $class->id,
            userId: $classData->userId
        );

        //attach subjects or courses
        $this->createMainAttachments(
            attachments: $classData->items,
            method: 'classes',
            itemId: $class->id,
            userId: $classData->userId
        );

        //create auto discussion 
        $this->createAutoDiscussion(
            item: $class,
            itemData: $classData
        );

        //track school activities
        if ($classData->account === 'admin') {
            (new ActivityTrackService())->createActivityTrack(
                $class,$class->ownedby,$mainAccount,$method
            );
        }
    }

    public function updateClass(ClassData $classData) 
    {
        //check account
        $mainAccount = getYourEduModel($classData->account,$classData->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$classData->account not found with id {$classData->accountId}");
        }

        if (!$this->checkAccountOwnership($mainAccount,$classData->userId)) {
            throw new ClassException("you do not own this account");
        }

        $class = getYourEduModel('class',$classData->classId);
        if (is_null($class)) {
            throw new AccountNotFoundException("class not found with id {$classData->classId}");
        }

        if (!$this->checkAuthorization($class,$classData->userId)) {
            throw new ClassException("You are not authorized to edit the class with id {$classData->classId}");
        }

        $class->update([
            'name' => $classData->name,
            'max_learners' => $classData->maxLearners,
            'state' => Str::upper($classData->state),
            'description' => $classData->description,
            'structure' => $classData->structure,
        ]);

        $this->itemCreateUpdateMethodParts(
            $class,
            $mainAccount,
            $classData,
            __METHOD__
        );

        if ($classData->removedGradeId) { //unattach the previous grade
            GradeService::gradeUnattachItem($classData->removedGradeId,$class);
        }

        //set payment information
        $this->removePayment(
            item: $class,
            paymentData: $classData->removedPaymentData,
        );
        
        //for academic years from which we may detach class
        $this->removeMainAttachments(
            attachments: $classData->removedAcademicYears,
            method: 'classes',
            itemId: $class->id,
        );
        
        //for courses or subjects from which we may detach class
        $this->removeMainAttachments(
            attachments: $classData->removedItems,
            method: 'classes',
            itemId: $class->id,
        );
        
        $this->broadcastUpdate($class);

        return $class->refresh();
    }

    public function deleteClass(ClassData $classData)
    {
        $class = getYourEduModel('class',$classData->classId);
        if (is_null($class)) {
            throw new AccountNotFoundException("class not found with id {$classData->classId}");
        }

        if (!$this->checkAuthorization($class,$classData->userId)) {
            throw new ClassException("You are not authorized to edit the class with id {$classData->classId}");
        }

        if ($classData->adminId) {
            $admin = getYourEduModel('admin',$classData->adminId);
            (new ActivityTrackService())->createActivityTrack(
                $class,$class->ownedby,$admin,__METHOD__
            );
        }
        
        //change state if its being used by annother else delete
        if ($classData->action === 'undo') {
            return $this->changeState($class,'accepted');
        } else if($classData->action === 'delete') {
            if ($this->paymentMadeFor($class) || $this->usedByAnother($class)) {
                return $this->changeState($class,'deleted');
            } else {
                
                broadcast(new DeleteClassEvent([
                    'account' => class_basename_lower($class->ownedby),
                    'accountId' => $class->ownedby->id,
                    'classId' => $classData->classId,
                ]))->toOthers();

                $class->delete();
            }
        }
    }

    private function broadcastUpdate($class)
    {
        broadcast(new UpdateClassEvent([
            'account' => class_basename_lower($class->ownedby),
            'accountId' => $class->ownedby->id,
            'class' => new ClassResource($class),
            'classResource' => new DashboardItemResource($class),
        ]))->toOthers();
    }

    private function paymentMadeFor($class)
    {
        return true;
        if ($class->whereHas('payments')->count()) {
            return true;
        }
        return false;
    }

    private function usedByAnother($class)
    {
        if (
            $class->schools()->wherePivot('resource','1')->count() ||
            $class->classes()->wherePivot('resource','1')->count()
        ) 
        {
            return true;
        }
        return false;
    }
}
