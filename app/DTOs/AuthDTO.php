<?php

namespace App\DTOs;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthDTO
{
    public ?string $answer = null;
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $questionId = null;
    public ?User $user = null;
    public ?QuestionDTO $questionDTO = null;
    public ?AnswerDTO $answerDTO = null;
    public ?UserDTO $userDTO = null;
    public ?UserDTO $extraUserDTO = null;
    public ?UserDTO $parentUserDTO = null;
    public ?UserDTO $adminUserDTO = null;
    public ?UserAccountDTO $userAccountDTO = null;
    public ?UserAccountDTO $extraUserAccountDTO = null;
    public ?UserAccountDTO $parentUserAccountDTO = null;
    public ?UserAccountDTO $adminUserAccountDTO = null;

    public static function new()
    {
        return new static;
    }

    public function addAccountData($request)
    {
        $this->userAccountDTO = self::getUserAccountDTO($request);

        return $this;
    }

    public function addData
    (
        $user = null,
        $answer = null,
        $questionId = null,
        $account = null,
        $accountId = null,
    )
    {
        $this->user = $user;
        $this->questionId = $questionId;
        $this->answer = $answer;
        $this->account = $account;
        $this->accountId = $accountId;

        return $this;
    }

    public static function createFromRequest(Request $request)
    {
        $self = new static;

        $self->user = $request->user();
        $self->questionId = $request->questionId;
        $self->questionDTO = static::getQuestionDTO($request);
        $self->answerDTO = static::getAnswerDTO($request);
        $self->userDTO = static::getUserDTO($request);
        $self->parentUserDTO = static::getParentUserDTO($request);
        $self->extraUserDTO = static::getExtraUserDTO($request);
        $self->adminUserDTO = static::getAdminUserDTO($request);
        $self->userAccountDTO = static::getUserAccountDTO($request);
        $self->extraUserAccountDTO = static::getExtraUserAccountDTO($request);
        $self->adminUserAccountDTO = static::getAdminUserAccountDTO($request);
        $self->parentUserAccountDTO = static::getParentUserAccountDTO($request);

        return $self;
    }

    public function withUser($user)
    {
        $clone = clone $this;

        $clone->user = $user;

        return $clone;
    }

    public static function getQuestionDTO($request)
    {
        if (is_null($request->questionData)) {
            return null;
        }

        $data = $request->questionData;

        if (is_string($data)) {
            $data = json_decode($data);
        }

        return QuestionDTO::new()
            ->addData(
                body: $data->body ?? null,
                hint: $data->hint ?? null
            );
    }

    public static function getAnswerDTO($request)
    {
        if (is_null($request->answerData)) {
            return null;
        }

        $data = $request->answerData;

        if (is_string($data)) {
            $data = json_decode($data);
        }

        return AnswerDTO::new()
            ->addData(
                answer: $data->answer
            );
    }

    public static function getUserDTO($request)
    {
        if (is_null($request->userData)) {
            return null;
        }

        $data = $request->userData;

        if (is_string($data)) {
            $data = json_decode($data);
        }

        return UserDTO::createFromData($data);
    }

    public static function getUserAccountDTO($request)
    {
        if (is_null($request->accountData)) {
            return null;
        }

        $data = $request->accountData;

        if (is_string($data)) {
            $data = json_decode($data);
        }

        return UserAccountDTO::createFromData($data);
    }

    public static function getExtraUserDTO($request)
    {
        if (is_null($request->extraUserData)) {
            return null;
        }

        $data = $request->extraUserData;

        if (is_string($data)) {
            $data = json_decode($data);
        }

        return UserDTO::createFromData($data);
    }

    public static function getExtraUserAccountDTO($request)
    {
        if (is_null($request->extraAccountData)) {
            return null;
        }

        $data = $request->extraAccountData;

        if (is_string($data)) {
            $data = json_decode($data);
        }

        return UserAccountDTO::createFromData($data);
    }

    public static function getParentUserDTO($request)
    {
        if (is_null($request->parentUserData)) {
            return null;
        }

        $data = $request->parentUserData;

        if (is_string($data)) {
            $data = json_decode($data);
        }

        return UserDTO::createFromData($data);
    }

    public static function getParentUserAccountDTO($request)
    {
        if (is_null($request->parentAccountData)) {
            return null;
        }

        $data = $request->parentAccountData;

        if (is_string($data)) {
            $data = json_decode($data);
        }

        return UserAccountDTO::new()
            ->addData(
                create: 'parent',
                name: $data->name ?? null,
                role: $data->role ?? null,
            );
    }

    public static function getAdminUserDTO($request)
    {
        if (is_null($request->adminUserData)) {
            return null;
        }

        $data = $request->adminUserData;

        if (is_string($data)) {
            $data = json_decode($data);
        }

        return UserDTO::createFromData($data);
    }

    public static function getAdminUserAccountDTO($request)
    {
        if (is_null($request->adminAccountData)) {
            return null;
        }

        $data = $request->adminAccountData;

        if (is_string($data)) {
            $data = json_decode($data);
        }

        return UserAccountDTO::createFromData($data)
            ->addSalaryData($data->salaryData);
    }

    public function hasExtraUser()
    {
        return (bool) $this->extraUserDTO;
    }

    public function doesntHaveExtraUser()
    {
        return ! $this->hasExtraUser();
    }

    public function hasUserAccount()
    {
        return (bool) $this->userAccountDTO;
    }

    public function doesntHaveUserAccount()
    {
        return ! $this->hasUserAccount();
    }

    public function hasExtraUserAccount()
    {
        return (bool) $this->extraUserAccountDTO;
    }

    public function doesntHaveExtraUserAccount()
    {
        return ! $this->hasExtraUserAccount();
    }

    public function hasParentUser()
    {
        return (bool) $this->parentUserDTO;
    }

    public function doesntHaveParentUser()
    {
        return ! $this->hasParentUser();
    }

    public function hasParentUserAccount()
    {
        return (bool) $this->parentUserAccountDTO;
    }

    public function doesntHaveParentUserAccount()
    {
        return ! $this->hasParentUserAccount();
    }

    public function hasAdminUser()
    {
        return (bool) $this->adminUserDTO;
    }

    public function doesntHaveAdminUser()
    {
        return ! $this->hasAdminUser();
    }

    public function hasAdminUserAccount()
    {
        return (bool) $this->adminUserAccountDTO;
    }

    public function doesntHaveAdminUserAccount()
    {
        return ! $this->hasAdminUserAccount();
    }
}
