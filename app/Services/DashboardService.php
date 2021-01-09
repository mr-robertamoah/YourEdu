<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\DashboardException;
use App\Http\Resources\BanBroadcastResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\BanNotification;
use App\Notifications\SchoolGeneralNotification;
use App\User;
use App\YourEdu\Admin;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\School;
use \Debugbar;
use Illuminate\Support\Facades\Notification;

class DashboardService
{
    const PAGINATION_LENGTH = 10;

    public function banningAccount($action,$account,$accountId,$adminId,$authId,
        $banId = null,$state = null,$type = null,$dueDate = null)
    {
        $mainAccount =  $this->checkAccount($account,$accountId);

        $admin = getAccountObject('admin',$adminId);
        if (is_null($admin)) {
            throw new AccountNotFoundException("administrator not found with id {$adminId}");
        }

        if ($action === 'ban') {
            $ban = (new BanService())->ban(
                $admin,$authId,$mainAccount,null,$state,$type,$dueDate
            );
        } else if ($action === 'unban') {
            $ban = (new BanService())->unban($admin,$authId,$banId);
        }

        //notify user
        if ($account === 'user') {
            $user = $mainAccount;
        } else if ($account === 'school') {
            $user =User::whereIn('id',getAdminIds($mainAccount))->get();
        } else {
            $user = $mainAccount->user;
        }

        Notification::sendNow($user,new BanNotification(
            new BanBroadcastResource($ban)));

        return [
            'ban' => $ban,
            'account' => $mainAccount,
        ];
    }

    public function getAccountActivities($account,$accountId,$adminId)
    {
        $mainAccount = $this->checkAccount($account,$accountId);

        $admin = getAccountObject('admin',$adminId);
        if (is_null($admin)) {
            throw new AccountNotFoundException("administrator not found with id {$adminId}");
        }

        $activities = $mainAccount->posts()->doesntHaveType()->get();
        $activities = $activities->merge($mainAccount->comments);
        $activities = $activities->merge($mainAccount->discussions);
        $activities = $activities->merge($mainAccount->messagesSent()
            ->where('messageable_type','App\YourEdu\Discussion')->get());
        $activities = $activities->merge($mainAccount->answers()
            ->whereHasMorph('answerable','App\YourEdu\Question',function($query){
                $query->where('questionable_type','App\YourEdu\Post');
            })->get());
        $activities = $activities->merge($mainAccount->likings);
        $activities = $activities->merge($mainAccount->questionsAdded()
            ->where('questionable_type','App\YourEdu\Post')->get());
        $activities = $activities->merge($mainAccount->flagsRaised);
        $activities = $activities->merge($mainAccount->activitiesAdded);
        $activities = $activities->merge($mainAccount->poemsAdded);
        $activities = $activities->merge($mainAccount->riddlesAdded);
        $activities = $activities->merge($mainAccount->lessonsAdded()
            ->where('lessonable_type','App\YourEdu\Post')->get());

        return paginate($activities->sortByDesc('updated_at'),self::PAGINATION_LENGTH);
    }

    public function attachAccount($account,$accountId,$attahcments)
    {
        $mainAccount =  $this->checkAccount($account,$accountId);

        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$account not found with id {$accountId}");
        }

        // iterate thru attachments, attach and return the attachments
        $mainAttachments = [];
        $activity = $account === 'learner' ? 'take' : 
            $account = 'facilitator' ? 'facilitate' :
            $account = 'school' ? 'offer' : '';

        foreach ($attahcments as $attachment) {
            if ($attachment->type === 'subjects') {
                if (!is_null(getAccountObject('subject',$attachment->id))) {
                    SubjectService::subjectAttachItem($attachment->id,$mainAccount,$activity);
                }
            } else if ($attachment->type === 'grades') {
                if (!is_null(getAccountObject('grade',$attachment->id))) {
                    GradeService::gradeAttachItem($attachment->id,$mainAccount);
                }
            }
        }
    }

    public function unattachAccount($account,$accountId,$item,$itemId)
    {
        $mainAccount =  $this->checkAccount($account,$accountId);

        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$account not found with id {$accountId}");
        }
        $mainItem =  $this->checkAccount($item,$itemId);

        if (is_null($mainItem)) {
            throw new AccountNotFoundException("$item not found with id {$itemId}");
        }

        if ($item === 'subject') {
            SubjectService::subjectUnattachItem($itemId,$mainAccount);
        } else if ($item === 'grade') {
            GradeService::gradeUnattachItem($itemId,$mainAccount);
        } else if ($item === 'program') {
            ProgramService::programUnattachItem($itemId,$mainAccount);
        } else if ($item === 'course') {
            CourseService::courseUnattachItem($itemId,$mainAccount);
        } else if ($item === 'learner' || $item === 'parent' || $item === 'admin' ||
            $item === 'facilitator' || $item === 'professional') {
            $item = "{$item}s";
            $mainAccount->$item()->detach($mainItem);
            $mainAccount->save();

            Notification::sendNow(
                $mainItem->user,
                new SchoolGeneralNotification(
                    message: 'your account has been removed from the school',
                    data: new UserAccountResource($mainItem),
                ),
            );
        }
    }

    public function itemGet($item,$itemId)
    {
        $mainItem = getAccountObject($item,$itemId);
        if (is_null($mainItem)) {
            throw new AccountNotFoundException("{$item} not found with id {$itemId}");
        }

        if ($item === 'question') {
            $mainItem = $mainItem->questionable;
        } else if ($item === 'activity') {
            $mainItem = $mainItem->activityfor;
        } else if ($item === 'lesson') {
            $mainItem = $mainItem->lessonable;
        } else if ($item === 'discussion') {
            $mainItem = $mainItem->discussionable;
        } else if ($item === 'poem') {
            $mainItem = $mainItem->poemable;
        } else if ($item === 'riddle') {
            $mainItem = $mainItem->riddleable;
        }

        //todo eager loading
        return $mainItem;
    }

    public function getUsersOrAdmins($account,$accountId,$userId,$type)
    {
        $mainAccount = $this->checkAccount($account,$accountId);

        if ($mainAccount->user_id !== $userId) {
            throw new DashboardException("you do not own {$account} account with id {$accountId}");
        }

        if ($type === 'admins') {
            return Admin::where('role','SUPERVISOR')->paginate(self::PAGINATION_LENGTH);
        } else if ($type === 'users') {
            return User::whereDoesntHave('admins',function($query) use ($userId){
                $query->whereIn('role',['SUPERADMIN','SUPERVISOR']);
            })->paginate(self::PAGINATION_LENGTH);
        } else if ($type === 'accounts') {
            $accounts = Learner::all();
            $accounts = $accounts->merge(ParentModel::all());
            $accounts = $accounts->merge(Facilitator::all());
            $accounts = $accounts->merge(Professional::all());
            $accounts = $accounts->merge(School::all());

            return paginate($accounts,self::PAGINATION_LENGTH);
        }
    }

    public function getAccountDetails(string $account,$accountId,$id,$owner)
    {
        $mainAccount = $this->checkAccount($account,$accountId);

        if ($owner) {            
            $this->verifyAccess($mainAccount,$id);
        }

        return $mainAccount;
    }

    private function verifyAccess($account,$id)
    {
        if ($account->user_id) {
            if ($account->user_id != $id) {
                throw new DashboardException("you are not the owner of this account");
            }
        } else {
            if ($account->owner_id != $id && 
                $account->admins()->where('user_id',$id)->count() > 1) {
                    throw new DashboardException("you are not the owner or admin of this account");
            }
        }
    }

    private function checkAccount($account, $accountId)
    {
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }
        return $mainAccount;
    }

    public function getSectionItemData($item,$itemId)
    {
        $mainItem = getAccountObject($item,$itemId);
        if (is_null($mainItem)) {
            throw new AccountNotFoundException("{$item} not found with id {$itemId}");
        }

        return $mainItem;
    }

    public function getAccountSpecificItem($account,$accountId,$item,$userId)
    {
        $mainAccount = $this->checkAccount($account,$accountId);

        if ($item === 'class') {
            return $mainAccount->ownedClasses()->whereHas('academicYear',function($query) {
                $query
                    ->whereDate('start_date','<',now())
                    ->whereDate('end_date','>=',now());
            })->paginate(self::PAGINATION_LENGTH);
        } else if ($item === 'academicYear') {
            return $mainAccount->currentAcademicYears()->paginate(self::PAGINATION_LENGTH);
        }
    }
}