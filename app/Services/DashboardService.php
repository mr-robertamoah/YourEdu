<?php

namespace App\Services;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\DashboardException;
use App\Exceptions\PaymentTypeException;
use App\Http\Resources\BanBroadcastResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\BanNotification;
use App\Notifications\SchoolGeneralNotification;
use App\User;
use App\YourEdu\Admin;
use App\YourEdu\ClassModel;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\School;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class DashboardService
{
    const PAGINATION_LENGTH = 10;

    public function banningAccount($action,$account,$accountId,$adminId,$authId,
        $banId = null,$state = null,$type = null,$dueDate = null)
    {
        $mainAccount =  $this->getModel($account,$accountId);

        $admin = getYourEduModel('admin',$adminId);
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
            $user =User::whereIn('id',$mainAccount->getAdminIds())->get();
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
        $mainAccount = $this->getModel($account,$accountId);

        $admin = getYourEduModel('admin',$adminId);
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
        $mainAccount =  $this->getModel($account,$accountId);

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
                if (!is_null(getYourEduModel('subject',$attachment->id))) {
                    SubjectService::subjectAttachItem($attachment->id,$mainAccount,$activity);
                }
            } else if ($attachment->type === 'grades') {
                if (!is_null(getYourEduModel('grade',$attachment->id))) {
                    GradeService::gradeAttachItem($attachment->id,$mainAccount);
                }
            }
        }
    }

    public function unattachAccount($account,$accountId,$item,$itemId)
    {
        $mainAccount =  $this->getModel($account,$accountId);

        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("$account not found with id {$accountId}");
        }
        $mainItem =  $this->getModel($item,$itemId);

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
        $mainItem = getYourEduModel($item,$itemId);
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
        $mainAccount = $this->getModel($account,$accountId);

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
        $mainAccount = $this->getModel($account,$accountId);

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

    private function getModel($account, $accountId)
    {
        $mainAccount = getYourEduModel($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }
        return $mainAccount;
    }

    public function getSectionItemData($item,$itemId)
    {
        $mainItem = getYourEduModel($item,$itemId);
        if (is_null($mainItem)) {
            throw new AccountNotFoundException("{$item} not found with id {$itemId}");
        }

        return $mainItem;
    }

    /**
     * this helps us get the items like classes, courses, extracurriculums, etc 
     * for facilitators, professionals, schools
     */
    public function getAccountSpecificItems($account,$accountId,$item)
    {
        $mainAccount = $this->getModel($account,$accountId);
        
        if ($item['for'] === 'lesson') {
            $data = new Collection();
            $data = $data->merge($mainAccount->ownedCourses()
                ->searchItems($item['search'])->get()
            );
            if ($item['two'] === 'extracurriculums') {
                $data = $data->merge(
                    $mainAccount->ownedExtracurriculums()->searchItems($item['search'])->get()
                );
            }
            if ($item['three'] === 'classes') {
                $query = ClassModel::query();
                $query->where('ownedby_type',$mainAccount::class)
                    ->where('ownedby_id',$mainAccount->id);
                if ($account === 'school') {     
                    $query->runningAcademicYears(); 
                }
                $data = $data->merge(
                    $query->hasCoursesOrSubjects()->searchItems($item['search'])->get()
                );
            }
            return paginate($data->sortByDesc('updated_at'), self::PAGINATION_LENGTH);
        } else if ($item['one'] === 'class') {
            $data = new Collection();
            $query = ClassModel::query();
            $query->where('ownedby_type',$mainAccount::class)
                ->where('ownedby_id',$mainAccount->id);
            if ($account === 'school') {     
                $query->runningAcademicYears(); 
            }
            $data = $data->merge($query->searchItems($item['search'])->get());

            return paginate($data->sortByDesc('updated_at'), self::PAGINATION_LENGTH);
        } else if ($item['one'] === 'courses') {
            $data = new Collection();
            $data = $data->merge(
                $mainAccount->ownedCourses()->searchItems($item['search'])->get()
            );
            
            if ($item['two'] === 'extracurriculums') {
                $data = $data->merge(
                    $mainAccount->ownedExtracurriculums()->searchItems($item['search'])->get()
                );
            }
            return paginate($data->sortByDesc('updated_at'), self::PAGINATION_LENGTH);
        }  else if ($item['one'] === 'subjects') {
            return paginate(SubjectService::getSubjects(),self::PAGINATION_LENGTH);
        } else if ($item['one'] === 'academicYear') {
            return $mainAccount->currentAcademicYears()->paginate(self::PAGINATION_LENGTH);
        }
    }

    /**
     * this helps to view more of a type of item (class, lesson, course)
     * belonging to or added by an account (facilitator, professional, school)
     */
    public function getAccountItems($account,$accountId,$item,$search)
    {
        $mainAccount = getYourEduModel($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} with id {$accountId} was not found");
        }

        $data = new Collection();
        if ($item === 'lessons') {
            $data = $mainAccount->ownedLessons()->searchItems($search)->get();
            if ($account === 'facilitator' || $account === 'professional') {
                $data = $data->merge($mainAccount->addedLessons()->searchItems($search)->get());
            }
        } else {
            $method = 'owned' . strtoupper(substr($item,0,1)) . substr($item,1);
            $data = $mainAccount->$method;
        }
        return paginate($data->unique()->sortByDesc('updated_at'), 2);
    }

    public function getItemDetails($item,$itemId,$authId)
    {   
        $mainItem = getYourEduModel($item,$itemId);
        if (is_null($mainItem)) {
            throw new AccountNotFoundException("{$item} with id {$itemId} not found.");
        }

        //if fails authId then check if item has any payment type
        if (!$authId) {
            if ($mainItem->hasPaymentTypes()) {
                throw new PaymentTypeException(
                    message: "Sorry, this ${$item} is not for free",
                    item: $mainItem
                );
            }
            if ($item === 'lesson' && !$mainItem->checkIfFreeOrIntro()) {
                throw new PaymentTypeException(
                    message: "Sorry, this ${$item} is not for free",
                    item: $mainItem
                );
            }
        } else if ($authId && !$mainItem->hasAccess($authId)) {
            throw new AccessDeniedException(
                message: "Sorry, you do not have access to this ${$item}.",
                item: $mainItem
            );
        }

        return $mainItem;
    }

    public function search
    (
        $account,
        $accountId,
        $search,
        $searchType,
        $for
    )
    {
        if ($for === 'collaboration') {
            return ProfileService::profileAccountsSearch(
                search: $search,
                searchType: $searchType,
                searcherAccount: $account,
                searcherAccountId: $accountId,
                for: $for,
            );
        }
    }
}