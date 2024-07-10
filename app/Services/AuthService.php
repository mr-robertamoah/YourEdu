<?php

namespace App\Services;

use App\DTOs\AuthDTO;
use App\DTOs\UserAccountDTO;
use App\DTOs\UserDTO;
use App\Exceptions\AuthException;
use App\Traits\ServiceTrait;
use App\User;
use App\YourEdu\Admin;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\School;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    use ServiceTrait;

    const VALID_ACCOUNT_TYPES = ['learner', 'parent', 'facilitator', 'professional', 'school'];
    const TOKEN_KEY = "YourEdu";
    const MAX_USER_QUESTIONS = 3;

    public function editUser(AuthDTO $authDTO)
    {
        $this->checkUser($authDTO);

        $authDTO->userDTO = $authDTO->userDTO->setUpUpdateInput();

        $authDTO->userDTO = $authDTO->userDTO
            ->withUser($authDTO->user);

        $this->updateUser($authDTO->userDTO);
        
        return $authDTO->user->refresh();
    }
    
    private function updateUser($userDTO)
    {
        $this->validatePassword($userDTO, false);

        $this->validateDob($userDTO);

        $this->validateUsername($userDTO);

        $userDTO->user->update($userDTO->updateInput);
    }

    private function checkUser($dto)
    {
        if (is_not_null($dto->user)) {
            return;
        }

        $this->throwAuthException(
            message: "sorry 😞, user not found.",
            data: $dto
        );
    }

    private function throwAuthException($message, $data = null)
    {
        throw new AuthException(
            message: $message,
            data: $data
        );
    }
    
    private function ensureUserQuestionsHasntReachedMax($authDTO)
    {
        if ($authDTO->user->numberOfAddedQuestions() < self::MAX_USER_QUESTIONS) {
            return;
        }

        $this->throwAuthException(
            message: "sorry 😞, your questions has already reached the maximum limit.",
            data: $authDTO
        );
    }

    public function createUserQuestion(AuthDTO $authDTO)
    {
        $this->checkUser($authDTO);

        $this->ensureUserQuestionsHasntReachedMax($authDTO);

        $authDTO->questionDTO = $authDTO->questionDTO
            ->withAddedby($authDTO->user);

        $question = (new QuestionService)->createQuestion($authDTO->questionDTO);

        return $question;
    }

    public function deleteUserQuestion(AuthDTO $authDTO)
    {
        $this->checkUser($authDTO);

        $question = $this->getUserQuestion($authDTO);

        $question->answers()->delete();

        $question->delete();
    }

    public function deleteUserAnswer(AuthDTO $authDTO)
    {
        $this->checkUser($authDTO);

        $answer = $this->getUserAnswer($authDTO);

        $answer->delete();
    }

    public function getUserAnswer(AuthDTO $authDTO)
    {
        $answer = $authDTO->user->getAnswerById($authDTO->answerId);
        
        if (is_not_null($answer)) {
            return $answer;
        }

        $this->throwAuthException(
            message: "the answer either isn't yours or was not found.",
            data: $authDTO
        );
    }

    private function ensureQuestionIsntAnswered($question, $authDTO)
    {
        if ($question->doesntHaveAnswerFrom($authDTO->user)) {
            return;
        }

        $this->throwAuthException(
            message: "you have already answered this question.",
            data: $authDTO
        );
    }

    public function getUserQuestion($authDTO)
    {
        $question = $authDTO->user->getAddedQuestionById($authDTO->questionId);

        if (is_not_null($question)) {
            return $question;
        }

        $this->throwAuthException(
            message: "the question you tried answering either isn't yours or was not found.",
            data: $authDTO
        );
    }

    public function createUserAnswer(AuthDTO $authDTO)
    {
        $this->checkUser($authDTO);

        $question = $this->getUserQuestion($authDTO);

        $this->ensureQuestionIsntAnswered($question, $authDTO);
        
        $authDTO->answerDTO = $authDTO->answerDTO
            ->dontCheckAnswerType()
            ->withAnsweredby($authDTO->user)
            ->withAnswerable($question);

        $answer = (new AnswerService)->createAnswer($authDTO->answerDTO);

        return $answer;
    }

    public function createExtraUserAccount(AuthDTO $authDTO)
    {
        if ($authDTO->doesntHaveExtraUser()) {
            return null;
        }

        $authDTO->extraUserDTO = $authDTO->extraUserDTO
            ->addData(referrerId: $authDTO->user->id);

        $extraUser = $this->createUser($authDTO->extraUserDTO);

        if ($authDTO->doesntHaveExtraUserAccount()) {
            return $extraUser;
        }

        $authDTO->extraUserAccountDTO = $authDTO
            ->extraUserAccountDTO?->withUser($extraUser);

        $extraAccount = $this->createUserAccount($authDTO->extraUserAccountDTO);

        if ($extraAccount->accountType !== 'learner') {
            return $extraAccount;
        }

        $parent = $this->createParentUserAccount($authDTO);

        $this->validateParentRole($authDTO);

        $this->attachWardToParent(
            $parent, 
            $extraAccount, 
            $authDTO->parentUserAccountDTO->role
        );

        return $extraAccount;
    }

    private function validateParentRole(AuthDTO $authDTO)
    {
        if (in_array($authDTO->parentUserAccountDTO->role, ParentModel::PARENTING_ROLE)) {
            return;
        }

        $this->throwAuthException(
            message: "sorry 😞, {$authDTO->parentUserAccountDTO->role} is not a valid parenting role.",
            data: $authDTO
        );
    }

    private function attachWardToParent($parent, $ward, $role = 'guardian')
    {
        if (is_null($parent) || is_null($ward)) {
            return;
        }

        if ($parent->accountType != 'parent') {
            return;
        }
        
        if ($ward->accountType != 'learner') {
            return;
        }

        $parent->parenting($ward, $role);
    }

    public function createParentUserAccount(AuthDTO $authDTO)
    {
        if ($authDTO->doesntHaveParentUser()) {
            return null;
        }

        $authDTO->parentUserDTO = $authDTO->parentUserDTO
            ->addData(referrerId: $authDTO->user->id);

        $parentUser = $this->createUser($authDTO->parentUserDTO);

        if ($authDTO->doesntHaveParentUserAccount()) {
            return $parentUser;
        }

        $authDTO->parentUserAccountDTO = $authDTO->parentUserAccountDTO
            ->withUser($parentUser);

        $parentAccount = $this->createUserAccount($authDTO->parentUserAccountDTO);

        return $parentAccount;
    }

    private function createAdminUserAccount(AuthDTO $authDTO)
    {
        if ($authDTO->doesntHaveAdminUser()) {
            return null;
        }

        $authDTO->adminUserDTO = $authDTO->adminUserDTO
            ->addData(referrerId: $authDTO->user->id);

        $adminUser = $this->createUser($authDTO->adminUserDTO);

        if ($authDTO->doesntHaveAdminUserAccount()) {
            return $adminUser;
        }

        $authDTO->adminUserAccountDTO = $authDTO->adminUserAccountDTO
            ->withUser($adminUser);

        $this->ensureUserIsAdult($authDTO->adminUserAccountDTO);

        $adminAccount = $this->createUserAccount($authDTO->adminUserAccountDTO);

        (new SalaryService)->set(
            $authDTO->adminUserAccountDTO->salaryDTO
                ->withAddedby($authDTO->user)
                ->withOwnedby($adminAccount)
                ->withDashboardItem(
                    $this->getModel(
                        $authDTO->adminUserAccountDTO->salaryDTO->dashboardItemDTO->account,
                        $authDTO->adminUserAccountDTO->salaryDTO->dashboardItemDTO->accountId,
                    )
                )
        );

        return $adminAccount;
    }

    private function checkData(AuthDTO $authDTO)
    {
        if ($authDTO->hasUserAccount()) {
            return;
        }

        if ($authDTO->hasExtraUser()) {
            return;
        }

        $this->throwAuthException(
            message: "sorry 😞, you don't have enough data to perform this action",
            data: $authDTO
        );
    }

    public function createAccount(AuthDTO $authDTO)
    {
        $this->checkUser($authDTO);

        $this->checkData($authDTO);

        $authDTO->userAccountDTO = $authDTO
            ->userAccountDTO?->withUser($authDTO->user);

        $account = $this->createUserAccount($authDTO->userAccountDTO);
        
        $this->createExtraUserAccount($authDTO);

        return $account;
    }

    public function updateAccount(AuthDTO $authDTO)
    {
        $this->checkUser($authDTO);

        $account = $this->getModel($authDTO->account, $authDTO->accountId);

        $this->ensureUserHasAccessToAccount($account, $authDTO);

        return $this->updateUserAccount($account,$authDTO);
    }

    private function checkUserAccountDTO($authDTO)
    {
        if (is_not_null($authDTO->userAccountDTO)) {
            return;
        }

        $this->throwAuthException(
            message: "sorry 😞, you do not have the required data to perform this action.",
            data: $authDTO
        );
    }

    private function updateUserAccount($account, AuthDTO $authDTO)
    {
        $this->checkUserAccountDTO($authDTO);

        $this->validateRole($authDTO->userAccountDTO);

        $this->validateTypes($authDTO->userAccountDTO);

        $this->validateState($authDTO->userAccountDTO);

        if ($authDTO->userAccountDTO->name && $account->hasAttribute('name')) {
            $account->name = $authDTO->userAccountDTO->name;
        }

        if ($authDTO->userAccountDTO->name && $account->hasAttribute('company_name')) {
            $account->company_name = $authDTO->userAccountDTO->name;
        }

        if ($authDTO->userAccountDTO->classStructure && $account->hasAttribute('class_structure')) {
            $account->class_structure = $authDTO->userAccountDTO->classStructure;
        }

        if ($authDTO->userAccountDTO->role && $account->hasAttribute('role')) {
            $account->role = Str::upper($authDTO->userAccountDTO->role);
        }

        if ($authDTO->userAccountDTO->level && $account->hasAttribute('level')) {
            $account->level = $authDTO->userAccountDTO->level;
        }

        if ($authDTO->userAccountDTO->about && $account->hasAttribute('about')) {
            $account->about = $authDTO->userAccountDTO->about;
        }

        if ($authDTO->userAccountDTO->title && $account->hasAttribute('title')) {
            $account->title = $authDTO->userAccountDTO->title;
        }

        if ($authDTO->userAccountDTO->state && $account->hasAttribute('state')) {
            $account->state = Str::upper($authDTO->userAccountDTO->state);
        }

        if ($authDTO->userAccountDTO->types && $account->hasAttribute('types')) {
            $account->types = $authDTO->userAccountDTO->types;
        }

        if ($authDTO->userAccountDTO->description && $account->hasAttribute('description')) {
            $account->description = $authDTO->userAccountDTO->description;
        }

        $account->save();

        return $account;
    }

    private function ensureUserIsAdult($dto)
    {
        if ($dto->user->isAdult()) {
            return;
        }

        $minimumAge = User::MINIMUM_ADULT_AGE;
        $this->throwAuthException(
            message: "sorry 😞, {$dto->user->name} must be an adult of at least {$minimumAge} years.",
            data: $dto
        );
    }

    private function ensureUserHasAccessToAccount($account, $authDTO)
    {
        if ($account->isUser($authDTO->user->id)) {
            return;
        }
        
        if ($account->isAuthorizedUser($authDTO->user->id) && 
            $account->isNotAdult()) {
            return;
        }
        
        if ($account->accountType === 'admin' && 
            $authDTO->user->hasSchoolWithAdmin($account)) {
            return;
        }

        $this->throwAuthException(
            message: "sorry 😞, {$account->accountType} account with id {$account->id} does'nt belong to you.",
            data: $authDTO
        );
    }

    public function deleteAccount(AuthDTO $authDTO)
    {
        $this->checkUser($authDTO);

        $account = $this->getModel($authDTO->account, $authDTO->accountId);

        $this->ensureUserHasAccessToAccount($account, $authDTO);

        $account->delete();
    }

    public function unregister(UserDTO $userDTO)
    {
        $this->checkUser($userDTO);

        $this->revokeUserCurrentToken($userDTO);

        $this->deleteUserAccounts($userDTO);

        $userDTO->user->delete();
    }

    public function deleteUserAccounts($userDTO)
    {
        $userDTO->user->allAccounts()->each->delete();
    }

    private function revokeUserCurrentToken($dto)
    {
        $dto->user->revokeToken();
    }
    
    public function ensureUserHasAccountSlot($userAccountDTO)
    {
        if ($userAccountDTO->user->hasLearnerSlot() && 
            $userAccountDTO->aboutToCreate('learner')) {
            return;
        }
        
        if ($userAccountDTO->user->hasParentSlot() && 
            $userAccountDTO->aboutToCreate('parent')) {
            return;
        }
        
        if ($userAccountDTO->user->hasFacilitatorSlot() && 
            $userAccountDTO->aboutToCreate('facilitator')) {
            return;
        }
        
        if ($userAccountDTO->user->hasProfessionalSlot() && 
            $userAccountDTO->aboutToCreate('professional')) {
            return;
        }
        
        if ($userAccountDTO->user->hasSchoolSlot() && 
            $userAccountDTO->aboutToCreate('school')) {
            return;
        }

        $this->throwAuthException(
            message: "sorry 😞, there is no more slot to create {$userAccountDTO->create} account",
            data: $userAccountDTO
        );
    }

    public function validateAccountType($userAccountDTO)
    {
        if (in_array($userAccountDTO->create, self::VALID_ACCOUNT_TYPES)) {
            return;
        }

        $this->throwAuthException(
            message: "sorry 😞, {$userAccountDTO->create} is not a valid account type",
            data: $userAccountDTO
        );
    }

    private function ensureUserIsAdultWhenRequired($userAccountDTO)
    {
        if (in_array($userAccountDTO->create, ['learner', 'facilitator', 'professional'])) {
            return;
        }

        $this->ensureUserIsAdult($userAccountDTO);
    }

    private function validateRole($userAccountDTO)
    {
        if (! in_array($userAccountDTO->create, ['professional'])) {
            return;
        }

        if (in_array($userAccountDTO->role, Professional::ROLES)) {
            return;
        }

        $this->throwAuthException(
            message: "{$userAccountDTO->role} is not a valid role for a professionals",
            data: $userAccountDTO
        );
    }

    private function validateState($userAccountDTO)
    {
        if (! in_array($userAccountDTO->create, ['admin'])) {
            return;
        }

        if (in_array($userAccountDTO->state, Admin::STATES)) {
            return;
        }

        $this->throwAuthException(
            message: "{$userAccountDTO->state} is not a valid state for an admininstator",
            data: $userAccountDTO
        );
    }

    private function validateTypes($userAccountDTO)
    {
        if (! in_array($userAccountDTO->create, ['school'])) {
            return;
        }

        if (! is_array($userAccountDTO->types)) {
            $userAccountDTO->types = ['traditional'];
        }

        foreach ($userAccountDTO->types as $type) {

            if (in_array($type, School::TYPES)) {
                continue;
            }

            $this->throwAuthException(
                message: "school can only have traditional and virtual types.",
                data: $userAccountDTO
            );
        }
    }

    public function createUserAccount(?UserAccountDTO $userAccountDTO)
    {
        if (is_null($userAccountDTO)) {
            return;
        }

        $this->checkUser($userAccountDTO);

        $this->validateAccountType($userAccountDTO);
        
        $this->ensureUserIsAdultWhenRequired($userAccountDTO);

        $this->validateRole($userAccountDTO);

        $this->validateTypes($userAccountDTO);

        $this->ensureUserHasAccountSlot($userAccountDTO);
        
        $userAccountDTO = $userAccountDTO->setAccountClass();

        $account = new $userAccountDTO->accountClass;

        if (in_array($account->accountType, ['learner', 'parent', 'facilitator', 'professional'])) {
            $account->name = $userAccountDTO->name ?: $userAccountDTO->user->name;
            $account->user_id = $userAccountDTO->user->id;
        }
        
        if ($account->accountType === 'professional') {
            $account->description = $userAccountDTO->description;
            $account->role = Str::upper($userAccountDTO->role);
            $account->other_name = $userAccountDTO->otherName;
        }
        
        if ($account->accountType === 'school') {
            $account->company_name = $userAccountDTO->name;
            $account->types = $userAccountDTO->types;
            $account->about = $userAccountDTO->about;
            $account->class_structure = $userAccountDTO->classStructure;
        }

        $account->save();

        return $account;
    }

    private function validatePassword($userDTO, $confirm = true)
    {
        if (is_null($userDTO->password)) {
            $userDTO->password = '';
        }

        if (Str::length($userDTO->password) < 6) {
            $message = "the password should be more than 6 characters";
        }

        if ($userDTO->password !== $userDTO->passwordConfirmation && $confirm) {
            $message = "password and password confirmation should be the same.";
        }

        if (! isset($message)) {
            return;
        }

        $this->throwAuthException(
            message: $message,
            data: $userDTO
        );
    }

    private function validateUsername($userDTO)
    {
        if (is_null($userDTO->username)) {
            $message = 'username is a required 😏. please provide one';
        }

        if (User::where('username', $userDTO->username)->exists()) {
            $message = "the username: {$userDTO->username} provided already exists. please use a different one.";
        }

        if (! isset($message)) {
            return;
        }

        $this->throwAuthException(
            message: $message,
            data: $userDTO
        );
    }

    private function validateDob(UserDTO $userDTO)
    {
        if (is_null($userDTO->dob)) {
            return;
        }

        if ($userDTO->dob->diffInYears(now()) < 5) {
            $message = "please the age of a user cannot be less than 5 years";
        }

        if (! isset($message)) {
            return;
        }

        $this->throwAuthException(
            message: $message,
            data: $userDTO
        );
    }

    public function createUser(UserDTO $userDTO)
    {
        $this->validateDob($userDTO);

        $this->validateUsername($userDTO);

        $this->validatePassword($userDTO);

        $userDTO = $userDTO->setUpcreateInput();

        $user = User::create($userDTO->createInput);
        
        if (is_not_null($user)) {
            return $user;
        }
        
        $this->throwAuthException(
            message: "User was not created or found.",
            data: $userDTO
        );
    }

    private function createLoginsAndToken(UserDTO | AuthDTO $dto)
    {
        $dto->user->logins()->create();

        return $dto->user->createToken(self::TOKEN_KEY);
    }

    private function attachAccessTokenToUser($personalToken, $dto)
    {
        $dto->user->withAccessToken($personalToken->accessToken);

        return $dto->user;
    }

    public function register(UserDTO $userDTO)
    {
        $user = $this->createUser($userDTO);

        $userDTO = $userDTO->withUser($user);
        
        // $personalToken = $this->createLoginsAndToken($userDTO);

        // $user = $this->attachAccessTokenToUser($personalToken, $userDTO);

        return $user;
    }

    public function login(UserDTO $userDTO)
    {
        $user = User::query()
            ->when($userDTO->username, function($query) use ($userDTO) {
                $query->where('username',$userDTO->username);
            })->when($userDTO->email, function($query) use ($userDTO) {
                $query->where('email',$userDTO->email);
            })->first();

        $userDTO = $userDTO->withUser($user);

        $this->checkUser($userDTO);
            
        if (Hash::check($userDTO->password, $user->password)) {

            // $personalToken = $this->createLoginsAndToken($userDTO);

            // $user = $this->attachAccessTokenToUser($personalToken, $userDTO);

            return $user;
        }        
        
        $this->throwAuthException(
            message: 'Please check username/email and password combinations.',
            data: $userDTO
        );
    }

    public function logout(AuthDTO $authDTO)
    {
        $this->checkUser($authDTO);

        // $this->revokeUserCurrentToken($authDTO);
    }

    private function ensureQuestionHasAnswer($question, $authDTO)
    {
        if ($question->hasAnswerLike($authDTO->answer)) {
            return;
        }

        $this->throwAuthException(
            message: "sorry 😞, the question you answered does'nt have {$authDTO->answer} as an answer.",
            data: $authDTO
        );
    }

    public function getUserUsingSecretAnswerPair(AuthDTO $authDTO)
    {
        $question = $this->getModel('question', $authDTO->questionId);

        $this->ensureQuestionHasAnswer($question, $authDTO);

        $user = $question->answerLike($authDTO->answer)->answeredby;

        $authDTO = $authDTO->withUser($user);

        $personalToken = $this->createLoginsAndToken($authDTO);

        $user = $this->attachAccessTokenToUser($personalToken, $authDTO);

        return $user;
    }

    public function getUserQuestions(AuthDTO $authDTO)
    {
        $this->checkUser($authDTO);

        return $authDTO->user->questionsAdded;
    }
}
