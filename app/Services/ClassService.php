<?php 

namespace App\Services;

use App\DTOs\ClassDTO;
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
    
    public function createCLass(ClassDTO $classDTO)
    {
        $mainAccount = getYourEduModel($classDTO->account,$classDTO->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$classDTO->account} not found with id {$classDTO->accountId}");
        }

        $this->checkAccountOwnership($mainAccount,$classDTO);

        $class = $mainAccount->addedClasses()->create([
            'name' => $classDTO->name,
            'max_learners' => $classDTO->maxLearners,
            'state' => $classDTO->owner === 'school' && 
                ($classDTO->account !== 'admin' && $classDTO->account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $classDTO->description,
        ]);

        if (is_null($class)) {
            throw new ClassException("class creation failed");
        }

        if ($classDTO->account === $classDTO->owner && $classDTO->accountId === $classDTO->ownerId) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getYourEduModel($classDTO->owner,$classDTO->ownerId);
            if (is_null($mainOwner)) {
                throw new AccountNotFoundException("{$classDTO->owner} not found with id {$classDTO->ownerId}");
            }
        }        

        $class->ownedby()->associate($mainOwner);

        if ($classDTO->owner === 'school') {
            
            GradeService::gradeAttachItem($classDTO->gradeId,$mainOwner);
    
            $academicYear = $mainOwner->academicYears()->whereDate('start_date','<=',now())
                ->whereDate('end_date','>=',now())->latest()->first();
    
            if (!is_null($academicYear)) {
                $class->academicYear()->attach($academicYear);
            } else {
                throw new ClassException("There is no current academic year. Please create one to be able to continue with this.");
            }

            $class->structure = strlen($classDTO->structure) > 1 ? $classDTO->structure : 
                $class->ownedby->class_structure;
        } else {
            $class->structure = $classDTO->structure;
        }

        $class->save();

        $grade = getYourEduModel('grade',$classDTO->gradeId);
        if (is_null($grade)) {
            throw new AccountNotFoundException("grade not found with id {$classDTO->gradeId}");
        }

        $this->itemCreateUpdateMethodParts(
            $class,
            $mainAccount,
            $classDTO,
            __METHOD__
        );

        if ($class->state === 'PENDING') {
            $userIds = array_filter(
                $mainOwner->getAdminIds(),function($userId) use ($classDTO){
                return $userId !== $classDTO->userId;
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

        if ($classDTO->owner === 'school' && $class->state !== 'PENDING') {
            broadcast(new NewClassEvent([
                'account' => $classDTO->owner,
                'accountId' => $classDTO->ownerId,
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

            GradeService::gradeAttachItem($gradeId,$account);
        }

        if ($gradeId && $class) {
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
        ClassDTO $classDTO,
        $method
    )
    {
        $this->attachFacilitator(
            account: $mainAccount,
            class: $class,
            facilitate: $classDTO->facilitate,
            gradeId: $classDTO->gradeId
        );

        $this->setPayment(
            item: $class,
            addedby: $mainAccount,
            paymentType: $classDTO->type,
            paymentData: $classDTO->paymentData,
            academicYears: $classDTO->academicYears
        );

        //attach academic years
        $this->createMainAttachments( 
            attachments: $classDTO->academicYears,
            method: 'classes',
            itemId: $class->id,
            userId: $classDTO->userId
        );

        //attach subjects or courses
        $this->createMainAttachments(
            attachments: $classDTO->items,
            method: 'classes',
            itemId: $class->id,
            userId: $classDTO->userId
        );

        //create auto discussion 
        $this->createAutoDiscussion(
            item: $class,
            itemData: $classDTO
        );

        //track school activities
        if ($classDTO->account === 'admin') {
            (new ActivityTrackService())->trackActivity(
                $class,$class->ownedby,$mainAccount,$method
            );
        }
    }

    public function updateClass(ClassDTO $classDTO) 
    {
        //check account
        $mainAccount = getYourEduModel($classDTO->account,$classDTO->accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$classDTO->account not found with id {$classDTO->accountId}");
        }

        $this->checkAccountOwnership($mainAccount,$classDTO);

        $class = getYourEduModel('class',$classDTO->classId);
        if (is_null($class)) {
            throw new AccountNotFoundException("class not found with id {$classDTO->classId}");
        }

        if (!$this->doesntHaveAuthorization($class,$classDTO->userId)) {
            throw new ClassException("You are not authorized to edit the class with id {$classDTO->classId}");
        }

        $class->update([
            'name' => $classDTO->name,
            'max_learners' => $classDTO->maxLearners,
            'state' => Str::upper($classDTO->state),
            'description' => $classDTO->description,
            'structure' => $classDTO->structure,
        ]);

        $this->itemCreateUpdateMethodParts(
            $class,
            $mainAccount,
            $classDTO,
            __METHOD__
        );

        if ($classDTO->removedGradeId) { //unattach the previous grade
            GradeService::gradeUnattachItem($classDTO->removedGradeId,$class);
        }

        //set payment information
        $this->removePayment(
            item: $class,
            paymentData: $classDTO->removedPaymentData,
        );
        
        //for academic years from which we may detach class
        $this->removeMainAttachments(
            attachments: $classDTO->removedAcademicYears,
            method: 'classes',
            itemId: $class->id,
        );
        
        //for courses or subjects from which we may detach class
        $this->removeMainAttachments(
            attachments: $classDTO->removedItems,
            method: 'classes',
            itemId: $class->id,
        );
        
        $this->broadcastUpdate($class);

        return $class->refresh();
    }

    public function deleteClass(ClassDTO $classDTO)
    {
        $class = getYourEduModel('class',$classDTO->classId);
        if (is_null($class)) {
            throw new AccountNotFoundException("class not found with id {$classDTO->classId}");
        }

        if (!$this->doesntHaveAuthorization($class,$classDTO->userId)) {
            throw new ClassException("You are not authorized to edit the class with id {$classDTO->classId}");
        }

        if ($classDTO->adminId) {
            $admin = getYourEduModel('admin',$classDTO->adminId);
            (new ActivityTrackService())->trackActivity(
                $class,$class->ownedby,$admin,__METHOD__
            );
        }
        
        //change state if its being used by annother else delete
        if ($classDTO->action === 'undo') {
            return $this->changeState($class,'accepted');
        } else if($classDTO->action === 'delete') {
            if ($this->paymentMadeFor($class) || $this->usedByAnother($class)) {
                return $this->changeState($class,'deleted');
            } else {
                
                broadcast(new DeleteClassEvent([
                    'account' => class_basename_lower($class->ownedby),
                    'accountId' => $class->ownedby->id,
                    'classId' => $classDTO->classId,
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
