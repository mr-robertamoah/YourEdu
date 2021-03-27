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
use App\Traits\DashboardItemServiceTrait;
use App\User;
use App\YourEdu\ClassModel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use \Debugbar;

class ClassService
{
    use DashboardItemServiceTrait;
    
    public function createCLass(ClassDTO $classDTO)
    {
        $classDTO = $classDTO->withAddedby(
            $this->getModel($classDTO->account,$classDTO->accountId)
        );

        $this->checkAccountOwnership($classDTO->addedby,$classDTO);

        $class = $classDTO->addedby->addedClasses()->create([
            'name' => $classDTO->name,
            'max_learners' => $classDTO->maxLearners,
            'state' => $classDTO->owner === 'school' && 
                ($classDTO->account !== 'admin' && $classDTO->account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $classDTO->description,
        ]);

        if (is_null($class)) {
            $this->throwClassException(
                message: "class creation failed",
                data: $classDTO
            );
        }

        $classDTO = $this->setDtoOwnedby($classDTO);

        $class = $this->updateOwnedby($class, $classDTO);
        
        $class = $this->setClassStructure($class, $classDTO);
        
        $class = $this->addSchoolDetails($class, $classDTO);
        
        $classDTO->method = __METHOD__;
        $class = $this->addMainClassDetails(
            $class,
            $classDTO,
        );

        $classDTO->methodType = 'created';
        $this->notifySchoolAdmins($class, $classDTO);

        $this->broadcastClass($class, $classDTO);

        return $class;
    }

    private function addSchoolDetails
    (
        $class,
        $classDTO
    )
    {
        if ($classDTO->ownedby->accountType !== 'school') {
            return;
        }

        GradeService::gradeAttachItem($classDTO->gradeId,$classDTO->ownedby);

        $class = $this->attachAcademicYear($class, $classDTO);

        return $class;
    }

    private function setClassStructure
    (
        $class,
        $classDTO
    )
    {
        $structure = $classDTO->structure;

        if ($classDTO->ownedby->accountType === 'school') {
            $class->structure = strlen($classDTO->structure) > 1 ? $classDTO->structure : 
                $class->ownedby->class_structure;
        }

        $class->structure = $structure;
        $class->save();

        return $class;
    }

    private function attachAcademicYear
    (
        $class,
        $classDTO
    )
    {
        $academicYear = $classDTO->ownedby->academicYears()
            ->whereDate('start_date','<=',now())
            ->whereDate('end_date','>=',now())->latest()->first();

        if (is_null($academicYear)) {
            $this->throwClassException(
                message: "There is no current academic year. Please create one to be able to continue with this.",
                data: $classDTO
            );
        }
        
        $class->academicYears()->attach($academicYear);
        $class->save();

        return $class->refresh();
    }

    private function notifySchoolAdmins
    (
        ClassModel $class,
        ClassDTO $classDTO,
    )
    {
        if ($classDTO->ownedby->accountType === 'school') {
        }
        $userIds = array_filter($classDTO->ownedby->getAdminIds(),function($id) use ($classDTO){
            return $id !== $classDTO->userId;
        });

        $notification = $this->getNotification(
            $class, $classDTO
        );

        if (is_null($notification)) {
            return;
        }

        Notification::send(
            User::whereIn('id',$userIds)->get(), 
            $notification
        );
    }

    private function getNotification
    (
        $class,
        $classDTO,
    )
    {
        $name = $classDTO->ownedby->company_name;
        $gradeMessage = $class->grades->first() ?
            "for grade: {$class->grades->first()->name}" : '';
        if ($classDTO->methodType === 'created') {
            return new ClassCreatedNotification(
                new UserAccountResource($classDTO->addedby),
                "created a class with the name: {$class->name}{$gradeMessage}, 
                for {$name}. Please go to dashboard to approve or otherwise."
            );
        }

        return null;
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

    private function addMainClassDetails
    (
        $class,
        ClassDTO $classDTO,
    )
    {
        $this->attachFacilitator(
            account: $classDTO->addedby,
            class: $class,
            facilitate: $classDTO->facilitate,
            gradeId: $classDTO->gradeId
        );

        $this->setPayment(
            item: $class,
            addedby: $classDTO->addedby,
            paymentType: $classDTO->type,
            paymentData: $classDTO->paymentData,
            academicYears: $classDTO->academicYears
        );

        $this->attachToItems( 
            attachments: $classDTO->academicYears,
            attachable: $class,
            dto: $classDTO
        );

        $this->attachToItems(
            attachments: $classDTO->items,
            attachable: $class,
            dto: $classDTO
        );

        $class = $this->createAutoDiscussion(
            item: $class,
            itemData: $classDTO
        );

        $this->trackSchoolAdmin($class, $classDTO);

        return $class;
    }

    public function updateClass(ClassDTO $classDTO) 
    {
        $classDTO = $classDTO->withAddedby(
            $this->getModel($classDTO->account,$classDTO->accountId)
        );
        
        $this->checkAccountOwnership($classDTO->addedby,$classDTO);

        $class = $this->getModel('class',$classDTO->classId);
        
        $this->checkClassAuthorization($class, $classDTO);

        $class->update([
            'name' => $classDTO->name,
            'max_learners' => $classDTO->maxLearners,
            'state' => strlen($classDTO->state) ? 
                Str::upper($classDTO->state) : "PENDING",
            'description' => $classDTO->description,
            'structure' => $classDTO->structure,
        ]);

        $classDTO->method = __METHOD__;
        $this->addMainClassDetails(
            $class,
            $classDTO,
        );

        if ($classDTO->removedGradeId) {
            GradeService::gradeUnattachItem($classDTO->removedGradeId,$class);
        }

        $this->removePayment(
            item: $class,
            paymentData: $classDTO->removedPaymentData,
        );
        
        $this->detachFromItems(
            attachments: $classDTO->removedAcademicYears,
            attachable: $class,
        );
        
        $this->detachFromItems(
            attachments: $classDTO->removedItems,
            attachable: $class,
        );
        
        $classDTO->methodType = 'updated';
        $this->broadcastClass($class, $classDTO);

        return $class->refresh();
    }

    private function checkClassAuthorization
    (
        $class,
        $classDTO
    )
    {
        if ($this->hasAuthorization($class, $classDTO->userId)) {
            return;
        }

        $this->throwClassException(
            message: "You are not authorized to edit the class with id {$classDTO->classId}",
            data: $classDTO
        );
    }

    private function throwClassException
    (
        $message,
        $data = null
    )
    {
        throw new ClassException(
            message: $message,
            data: $data
        );
    }

    private function broadcastClass
    (
        $class,
        $classDTO,
    )
    {
        $event = $this->getEvent($class, $classDTO);

        if (is_null($event)) {
            return;
        }

        broadcast($event)->toOthers();
    }

    private function getEvent
    (
        $class,
        $classDTO,
    )
    {
        if ($classDTO->methodType === 'created') {
            return new NewClassEvent([
                'account' => $classDTO->owner,
                'accountId' => $classDTO->ownerId,
                'class' => new ClassResource($class),
            ]);
        }

        if ($classDTO->methodType === 'updated') {
            return new UpdateClassEvent([
                'account' => class_basename_lower($class->ownedby),
                'accountId' => $class->ownedby->id,
                'class' => new ClassResource($class),
                'classResource' => new DashboardItemResource($class),
            ]);
        }

        if ($classDTO->methodType === 'deleted') {
            return new DeleteClassEvent([
                'account' => class_basename_lower($class->ownedby),
                'accountId' => $class->ownedby->id,
                'classId' => $classDTO->classId,
            ]);
        }

        return null;
    }

    public function deleteClass(ClassDTO $classDTO)
    {
        ray($classDTO)->green();
        $class = $this->getModel('class',$classDTO->classId);
        
        $this->checkClassAuthorization($class,$classDTO);

        $classDTO->method = __METHOD__;
        $this->trackSchoolAdmin($class, $classDTO);
        
        if ($classDTO->action === 'undo') {
            return $this->changeState($class,'accepted');
        }
        
        if ($this->paymentMadeFor($class) || $this->usedByAnotherItem($class)) {
            return $this->changeState($class,'deleted');
        }
        
        $this->deleteDiscussion($class, $classDTO);

        $classDTO->methodType = 'deleted';
        $this->broadcastClass($class, $classDTO);

        return $class->delete();
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
        if ($class->whereHas('payments')->count()) {
            return true;
        }
        return false;
    }

    private function usedByAnotherItem($class)
    {
        if (
            $class->schools()->wherePivot('resource',true)->count() ||
            $class->classes()->wherePivot('resource',true)->count()
        ) 
        {
            return true;
        }
        return false;
    }
}
