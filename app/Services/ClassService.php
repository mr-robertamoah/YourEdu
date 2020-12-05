<?php 

namespace App\Services;

use App\Events\DeleteClassEvent;
use App\Events\NewClassEvent;
use App\Events\UpdateClassEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\ClassException;
use App\Http\Resources\ClassResource;
use App\Http\Resources\DashboardItemResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\ClassCreatedNotification;
use App\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ClassService
{
    public function createCLass($account,$accountId,$id,$owner,$ownerId,
        $name,$description,$gradeId,$facilitate,$maxLearners,
        $type,$paymentData,$feeable,$feeableId,$structure)
    {
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        if ($mainAccount->user_id !== $id) {
            throw new ClassException("you do not own this account");
        }

        $class = $mainAccount->addedClasses()->create([
            'name' => $name,
            'max_learners' => $maxLearners,
            'state' => $owner === 'school' && 
                ($account !== 'admin' || $account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $description,
        ]);

        if (is_null($class)) {
            throw new ClassException("class creation failed");
        }

        if ($account === $owner && $accountId === $ownerId) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getAccountObject($owner,$ownerId);
            if (is_null($mainOwner)) {
                throw new AccountNotFoundException("{$owner} not found with id {$ownerId}");
            }
        }        

        $class->ownedby()->associate($mainOwner);

        //check if ownedby school and already attached to grade

        if ($owner === 'school') {
            
            GradeService::gradeAttachItem($gradeId,$mainOwner);
    
            $academicYear = $mainOwner->academicYears()->whereDate('start_date','<=',now())
                ->whereDate('end_date','>=',now())->latest()->first();
    
            if (!is_null($academicYear)) {
                $class->academicYear()->attach($academicYear);
            } else {
                throw new ClassException("There is no current academic year. Please create one to be able to continue with this.");
            }

            $class->structure = $class->ownedby->class_structure;
        } else {
            $class->structure = $structure;
        }

        $class->save();

        $grade = getAccountObject('grade',$gradeId);
        if (is_null($grade)) {
            throw new AccountNotFoundException("grade not found with id {$gradeId}");
        }

        if ($facilitate) {
            $mainAccount->classes()->attach($class);
            $mainAccount->save();

            //check if facilitator is already attached to grade
            GradeService::gradeAttachItem($gradeId,$mainAccount);
        }

        if ($class->state === 'PENDING') {
            $userIds = array_filter(getAdminIds($mainOwner),function($userId) use ($id){
                return $userId !== $id;
            });
            Notification::send(User::find($userIds), new ClassCreatedNotification(
                new UserAccountResource($mainAccount),
                "created a class with the name: {$class->name} for grade: {$grade->name}, for {$mainOwner->name}. Please go to dashboard to approve or otherwise."
            ));
        }

        $this->setClassPayment($class,$mainAccount,$type,$paymentData,[
            'feeable' => $feeable,
            'feeableId' => $feeableId]);

        //attach class to grade
        GradeService::gradeAttachItem($gradeId,$class);

        //track school activities
        if ($account === 'admin') {
            (new ActivityTrackService())->createActivityTrack(
                $class,$mainOwner,$mainAccount,__METHOD__
            );
        }

        if ($owner === 'school') {
            broadcast(new NewClassEvent([
                'account' => $owner,
                'accountId' => $ownerId,
                'class' => new ClassResource($class),
            ]))->toOthers();
        }

        return $class;
    }

    private function checkClassAuthorization($class,$userId)
    {
        $account = getAccountString($class->ownedby);

        if ($account === 'school') {
            if (in_array($userId,getAdminIds($class->ownedby))) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($class->ownedby->user_id === $userId) {
                return true;
            } else {
                return false;
            }
        }
    }

    private function setClassPayment($class,$addedby,$paymentType,$paymentData,$feeableData = [])
    {
        if ($paymentType == 'price') {
            if (count($paymentData)) {
                foreach ($paymentData as $priceData) {
                    (new PriceService)->setPrice($class,$priceData,$addedby);
                }
            }
        } else if ($paymentType == 'fee') {
            (new FeeService)->setFee($class,$paymentData,$addedby,$feeableData);
        }
    }

    public function getClass()
    {
        
    }

    public function updateClass($classId,$userId,$name,$description,$state,$maxLearners,
        $gradeId,$adminId)
    {
        $class = getAccountObject('class',$classId);
        if (is_null($class)) {
            throw new AccountNotFoundException("class not found with id {$classId}");
        }

        if (!$this->checkClassAuthorization($class,$userId)) {
            throw new ClassException("You are not authorized to edit the class with id {$classId}");
        }

        $class->update([
            'name' => $name,
            'max_learners' => $maxLearners,
            'state' => ($state),
            'description' => $description,
        ]);

        if ($gradeId) {
            $grade = getAccountObject('grade',$gradeId);
            if (is_null($grade)) {
                throw new AccountNotFoundException("grade not found with id {$gradeId}");
            }
            
            //attach class to grade
            GradeService::gradeAttachItem($gradeId,$class);
        }

        if ($adminId) {
            $admin = getAccountObject('admin',$adminId);
            (new ActivityTrackService())->createActivityTrack(
                $class,$class->classOwner,$admin,__METHOD__
            );
        }
        
        broadcast(new UpdateClassEvent([
            'account' => getAccountString($class->ownedby),
            'accountId' => $class->ownedby->id,
            'class' => new ClassResource($class),
            'classResource' => new DashboardItemResource($class),
        ]))->toOthers();

        return $class;
    }

    public function deleteClass($classId,$userId,$adminId)
    {
        $class = getAccountObject('class',$classId);
        if (is_null($class)) {
            throw new AccountNotFoundException("class not found with id {$classId}");
        }

        if (!$this->checkClassAuthorization($class,$userId)) {
            throw new ClassException("You are not authorized to edit the class with id {$classId}");
        }

        if ($adminId) {
            $admin = getAccountObject('admin',$adminId);
            (new ActivityTrackService())->createActivityTrack(
                $class,$class->classOwner,$admin,__METHOD__
            );
        }
        
        $class->delete();
        
        broadcast(new DeleteClassEvent([
            'account' => getAccountString($class->ownedby),
            'accountId' => $class->ownedby->id,
            'classId' => $classId,
        ]))->toOthers();
    }
}
