<?php

namespace App\Services;

use App\DTOs\DashboardItemSearchDTO;
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
        $mainItem = $this->getModel($item,$itemId);

        return $mainItem;
    }

    private function searchOwnedCourses
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher->ownedCourses()
            ->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchOwnedCourseSections
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher
            ->whereOwnedCourseSections()
            ->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchOwnedOrFacilitatingCourseSections
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher
            ->whereOwnedOrFacilitatingCourseSections()
            ->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchOwnedOrFacilitatingCourses
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher
            ->whereOwnedOrFacilitatingCourses()->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchOwnedAndFacilitatingCourses
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher->whereOwnedAndFacilitatingCourses()
            ->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchOwnedLessons
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher->ownedLessons()
            ->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchOwnedOrFacilitatingLessons
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher
            ->whereOwnedOrFacilitatingLessons()
            ->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchPrograms
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher->ownedPrograms()
            ->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchOwnedExtracurriculums
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher->ownedExtracurriculums()->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchOwnedOrFacilitatingExtracurriculums
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher
            ->whereOwnedOrFacilitatingExtracurriculums()->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchOwnedClasses
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        $query = $dashboardItemSearchDTO->searcher
            ->ownedClasses();
        
        if ($dashboardItemSearchDTO->account === 'school') {     
            $query->runningAcademicYears(); 
        }
        
        return $query->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchOwnedAndFacilitatingClasses
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher
            ->whereOwnedAndFacilitatingClasses()->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchOwnedSubjects
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher
            ->whereOwnedClassSubjects()
            ->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function searchOwnedOrFacilitatingClasses
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        $query = $dashboardItemSearchDTO->searcher
            ->whereOwnedOrFacilitatingClasses()->searchItems(
                $dashboardItemSearchDTO->search
            );
        
        if ($dashboardItemSearchDTO->account === 'school') {     
            $query->runningAcademicYears(); 
        }
        
        return $query->searchItems(
            $dashboardItemSearchDTO->search
        )->get();
    }

    private function searchOwnedOrFacilitatingSubjects
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher
            ->whereOwnedOrFacilitatingClassSubjects()
            ->searchItems(
                $dashboardItemSearchDTO->search
            )->get();
    }

    private function getSearchSubjects
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher->usesFacilitationDetails() ?
            $this->searchOwnedOrFacilitatingSubjects($dashboardItemSearchDTO) :
            $this->searchOwnedSubjects($dashboardItemSearchDTO);
    }

    private function getSearchClasses
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher->usesFacilitationDetails() ?
            $this->searchOwnedOrFacilitatingClasses($dashboardItemSearchDTO) :
            $this->searchOwnedClasses($dashboardItemSearchDTO);
    }

    private function getSearchExtracurriculums
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher->usesFacilitationDetails() ?
            $this->searchOwnedOrFacilitatingExtracurriculums($dashboardItemSearchDTO) :
            $this->searchOwnedExtracurriculums($dashboardItemSearchDTO);
    }

    private function getSearchCourseSections
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher->usesFacilitationDetails() ?
            $this->searchOwnedOrFacilitatingCourseSections($dashboardItemSearchDTO) :
            $this->searchOwnedCourseSections($dashboardItemSearchDTO);
    }

    private function getSearchCourses
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher->usesFacilitationDetails() ?
            $this->searchOwnedOrFacilitatingCourses($dashboardItemSearchDTO) :
            $this->searchOwnedCourses($dashboardItemSearchDTO);
    }

    private function getSearchLessons
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        return $dashboardItemSearchDTO->searcher->usesFacilitationDetails() ?
            $this->searchOwnedOrFacilitatingLessons($dashboardItemSearchDTO) :
            $this->searchOwnedLessons($dashboardItemSearchDTO);
    }

    private function getLessonSpecificItems
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        $data = new Collection();
        
        if ($dashboardItemSearchDTO->searchCourses) {
            $data = $data->merge(
                $dashboardItemSearchDTO->searcher->usesFacilitationDetails() ?
                $this->searchOwnedAndFacilitatingCourses($dashboardItemSearchDTO) :
                $this->searchOwnedCourses($dashboardItemSearchDTO)
            );
        }

        if ($dashboardItemSearchDTO->searchExtracurriculums) {
            $data = $data->merge(
                $dashboardItemSearchDTO->searcher->usesFacilitationDetails() ?
                $this->searchOwnedOrFacilitatingExtracurriculums($dashboardItemSearchDTO) :
                $this->searchOwnedExtracurriculums($dashboardItemSearchDTO)
            );
        }
        
        if ($dashboardItemSearchDTO->searchClasses) {            
            $data = $data->merge(
                $dashboardItemSearchDTO->searcher->usesFacilitationDetails() ?
                $this->searchOwnedAndFacilitatingClasses($dashboardItemSearchDTO) :
                $this->searchOwnedClasses($dashboardItemSearchDTO)
            );
        }
        
        return $this->paginate($data);
    }

    private function getAssessmentSpecificItems
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        $data = new Collection();
        
        if ($dashboardItemSearchDTO->searchLessons) {
            $data = $data->merge(
                $this->getSearchLessons($dashboardItemSearchDTO)
            );
        }

        if ($dashboardItemSearchDTO->searchCourses) {
            $data = $data->merge(
                $this->getSearchCourses($dashboardItemSearchDTO)
            );
        }

        if ($dashboardItemSearchDTO->searchCourseSections) {
            $data = $data->merge(
                $this->getSearchCourseSections($dashboardItemSearchDTO)
            );
        }

        if ($dashboardItemSearchDTO->searchExtracurriculums) {
            $data = $data->merge(
                $this->getSearchExtracurriculums($dashboardItemSearchDTO)
            );
        }
        
        if ($dashboardItemSearchDTO->searchClasses) {            
            $data = $data->merge(
                $this->getSearchClasses($dashboardItemSearchDTO)
            );
        }
        
        if ($dashboardItemSearchDTO->searchSubjects) {            
            $data = $data->merge(
                $this->getSearchSubjects($dashboardItemSearchDTO)
            );
        }
        
        return $this->paginate($data);
    }

    private function getCourseSpecificItems
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        $data = new Collection();

        if ($dashboardItemSearchDTO->searchClasses) {            
            $data = $data->merge(
                $this->searchOwnedClasses($dashboardItemSearchDTO)
            );
        }

        return $this->paginate($data);
    }

    private function getProgramSpecificItems
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        $data = new Collection();
        if ($dashboardItemSearchDTO->searchCourses) {            
            $data = $data->merge(
                $this->searchOwnedCourses($dashboardItemSearchDTO)
            );
        }
        
        if ($dashboardItemSearchDTO->searchExtracurriculums) {
            $data = $data->merge(
                $this->searchOwnedExtracurriculums($dashboardItemSearchDTO)
            );
        }
        return $this->paginate($data);
    }

    private function getClassSpecificItems
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        if ($dashboardItemSearchDTO->searchSubjects) {
            return $this->paginate(SubjectService::getSubjects());
        }

        $data = new Collection();
        if ($dashboardItemSearchDTO->searchCourses) {
            $data = $data->merge(
                $this->searchOwnedCourses($dashboardItemSearchDTO)
            );
        }
        
        if ($dashboardItemSearchDTO->searchAcademicYears) {
            $data = $data->merge(
                $dashboardItemSearchDTO->searcher->currentAcademicYears()
                    ->get()
            );
        }

        return $this->paginate($data);
    }

    private function getExtracurriculumSpecificItems
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        $data = new Collection();
        
        if ($dashboardItemSearchDTO->searchClasses) {            
            $data = $data->merge(
                $this->searchOwnedClasses($dashboardItemSearchDTO)
            );
        }

        if ($dashboardItemSearchDTO->searchPrograms) {            
            $data = $data->merge(
                $this->searchPrograms($dashboardItemSearchDTO)
            );
        }

        return $this->paginate($data);
    }

    private function paginate($data)
    {
        return paginate(
            $data->unique()->sortByDesc('updated_at'), 
            self::PAGINATION_LENGTH
        );
    }

    /**
     * this helps us get the items like classes, courses, extracurriculums, etc 
     * for facilitators, professionals, schools
     */
    public function getAccountSpecificItems
    (
        DashboardItemSearchDTO $dashboardItemSearchDTO
    )
    {
        try {
            $dashboardItemSearchDTO = $dashboardItemSearchDTO->withSearcher(
                    $this->getModel(
                    $dashboardItemSearchDTO->account,
                    $dashboardItemSearchDTO->accountId
                )
            );
            
            if ($dashboardItemSearchDTO->for === 'lesson') {
                return $this->getLessonSpecificItems($dashboardItemSearchDTO);
            } 
            
            if ($dashboardItemSearchDTO->for === 'assessment') {
                return $this->getAssessmentSpecificItems($dashboardItemSearchDTO);
            } 
            
            if ($dashboardItemSearchDTO->for === 'class') {
                return $this->getClassSpecificItems($dashboardItemSearchDTO);
            }
            
            if ($dashboardItemSearchDTO->for === 'program') {
                return $this->getProgramSpecificItems($dashboardItemSearchDTO);
            }
            
            if ($dashboardItemSearchDTO->for === 'course') {
                return $this->getCourseSpecificItems($dashboardItemSearchDTO);
            }
            
            if ($dashboardItemSearchDTO->for === 'extracurriculum') {
                return $this->getExtracurriculumSpecificItems($dashboardItemSearchDTO);
            }

            $this->throwDashboardException(
                message: "there is insufficient data for this request.",
                data: $dashboardItemSearchDTO
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function throwDashboardException
    (
        string $message,
        $data = null
    )
    {
        throw new DashboardException(
            message: $message,
            data: $data
        );
    }

    /**
     * this helps to view more of a type of item (class, lesson, course)
     * belonging to or added by an account (facilitator, professional, school)
     */
    public function getAccountItems($account,$accountId,$item,$search)
    {
        $mainAccount = $this->getModel($account,$accountId);

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
        $mainItem = $this->getModel($item,$itemId);

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