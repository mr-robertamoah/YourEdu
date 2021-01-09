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
use App\Traits\ClassCourseTrait;
use App\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ClassService
{
    use ClassCourseTrait;
    
    public function createCLass($account,$accountId,$id,$classData)
    {
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        if ($mainAccount->user_id !== $id) {
            throw new ClassException("you do not own this account");
        }

        $class = $mainAccount->addedClasses()->create([
            'name' => $classData['name'],
            'max_learners' => $classData['maxLearners'],
            'state' => $classData['owner'] === 'school' && 
                ($account !== 'admin' || $account !== 'school') 
                ? 'PENDING' : 'ACCEPTED',
            'description' => $classData['description'],
        ]);

        if (is_null($class)) {
            throw new ClassException("class creation failed");
        }

        if ($account === $classData['owner'] && $accountId === $classData['ownerId']) {
            $mainOwner = $mainAccount;
        } else {
            $mainOwner = getAccountObject($classData['owner'],$classData['ownerId']);
            if (is_null($mainOwner)) {
                throw new AccountNotFoundException("{$classData['owner']} not found with id {$classData['ownerId']}");
            }
        }        

        $class->ownedby()->associate($mainOwner);

        //check if ownedby school and already attached to grade

        if ($classData['owner'] === 'school') {
            
            GradeService::gradeAttachItem($classData['gradeId'],$mainOwner);
    
            $academicYear = $mainOwner->academicYears()->whereDate('start_date','<=',now())
                ->whereDate('end_date','>=',now())->latest()->first();
    
            if (!is_null($academicYear)) {
                $class->academicYear()->attach($academicYear);
            } else {
                throw new ClassException("There is no current academic year. Please create one to be able to continue with this.");
            }

            $class->structure = $class->ownedby->class_structure ?? $classData['structure'];
        } else {
            $class->structure = $classData['structure'];
        }

        $class->save();

        $grade = getAccountObject('grade',$classData['gradeId']);
        if (is_null($grade)) {
            throw new AccountNotFoundException("grade not found with id {$classData['gradeId']}");
        }

        if ($classData['facilitate']) {
            $mainAccount->classes()->attach($class);
            $mainAccount->save();

            //check if facilitator is already attached to grade
            GradeService::gradeAttachItem($classData['gradeId'],$mainAccount);
        }

        $this->setPayment(
            item: $class,
            addedby: $mainAccount,
            paymentType: $classData['type'],
            paymentData: $classData['paymentData'],
            feeableData: [
                'feeable' => $classData['feeable'],
                'feeableId' => $classData['feeableId']
            ]
        );

        //attach class to grade
        GradeService::gradeAttachItem($classData['gradeId'],$class);

        //create auto discussion 
        $this->createAutoDiscussion(
            item: $class,
            account: $account,
            accountId: $accountId,
            discussionData: $classData['discussionData'],
            files: $classData['discussionFiles'],
        );

        //track school activities
        if ($account === 'admin') {
            (new ActivityTrackService())->createActivityTrack(
                $class,$mainOwner,$mainAccount,__METHOD__
            );
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

        if ($classData['owner'] === 'school' && $class->state !== 'PENDING') {
            broadcast(new NewClassEvent([
                'account' => $classData['owner'],
                'accountId' => $classData['ownerId'],
                'class' => new ClassResource($class),
            ]))->toOthers();
        }

        return $class;
    }

    public function getClass()
    {
        
    }

    public function updateClass(
        $account,
        $accountId,
        $classId,
        $userId, 
        $classData,
        $gradeId,
    ) {
        //check account
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$account not found with id {$classId}");
        }

        $class = getAccountObject('class',$classId);
        if (is_null($class)) {
            throw new AccountNotFoundException("class not found with id {$classId}");
        }

        if (!$this->checkAuthorization($class,$userId)) {
            throw new ClassException("You are not authorized to edit the class with id {$classId}");
        }

        $class->update([
            'name' => $classData['name'],
            'max_learners' => $classData['maxLearners'],
            'state' => Str::upper($classData['state']),
            'description' => $classData['description'],
        ]);

        if ($gradeId) {
            $grade = getAccountObject('grade',$gradeId);
            if (is_null($grade)) {
                throw new AccountNotFoundException("grade not found with id {$gradeId}");
            }
            
            //attach class to grade
            GradeService::gradeAttachItem($gradeId,$class);
        }

        if ($account === 'admin') {
            (new ActivityTrackService())->createActivityTrack(
                $class,$class->classOwner,$mainAccount,__METHOD__
            );
        }
        
        $this->broadcastUpdate($class->ownedby,[],$class);

        return $class;
    }

    public function deleteClass($classId,$userId,$adminId,$action)
    {
        $class = getAccountObject('class',$classId);
        if (is_null($class)) {
            throw new AccountNotFoundException("class not found with id {$classId}");
        }

        if (!$this->checkAuthorization($class,$userId)) {
            throw new ClassException("You are not authorized to edit the class with id {$classId}");
        }

        if ($adminId) {
            $admin = getAccountObject('admin',$adminId);
            (new ActivityTrackService())->createActivityTrack(
                $class,$class->classOwner,$admin,__METHOD__
            );
        }
        
        $ownedby = $class->ownedby;
        //change state if its being used by annother else delete
        if ($action === 'undo') {
            return $this->changeState($ownedby,null,$class,'accepted');
        } else if($action === 'delete') {
            if ($this->paymentMadeFor($class) || $this->usedByAnother($class)) {
                return $this->changeState($ownedby,null,$class,'deleted');
            } else {

                $class->delete();
                
                broadcast(new DeleteClassEvent([
                    'account' => getAccountString($ownedby),
                    'accountId' => $ownedby->id,
                    'classId' => $classId,
                ]))->toOthers();
            }
        }
    }

    private function broadcastUpdate($ownedby,$classes,$class)
    {
        broadcast(new UpdateClassEvent([
            'account' => getAccountString($ownedby),
            'accountId' => $ownedby->id,
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

    private function usedByAnother($class)
    {
        if (
            $class->schools()->wherePivot('resource','1')->count() ||
            $class->classes->wherePivot('resource','1')->count()
        ) 
        {
            return true;
        }
        return false;
    }
}
