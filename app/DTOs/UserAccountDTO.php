<?php

namespace App\DTOs;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserAccountDTO
{
    public ?User $user = null;
    public ?Model $account = null;
    public ?string $create = null;
    public ?string $state = null;
    public ?string $title = null;
    public ?string $level = null;
    public ?SalaryDTO $salaryDTO = null;
    public ?string $name = null;
    public ?string $description = null;
    public ?string $role = null;
    public ?string $about = null;
    public ?string $otherName = null;
    public ?string $types = null;
    public ?string $classStructure = null;
    public ?string $accountClass = null;

    public static function new()
    {
        return new static;
    }

    public function addData
    (
        $name = null,
        $role = null,
        $types = null,
        $description = null,
        $about = null,
        $otherName = null,
        $create = null,
        $classStructure = null,
    )
    {
        $this->name = $name;
        $this->description = $description;
        $this->role = $role;
        $this->types = $types;
        $this->create = $create;
        $this->otherName = $otherName;
        $this->classStructure = $classStructure;
        $this->about = $about;

        return $this;
    }

    public static function createFromData($data)
    {
        $self = new static;
    
        $self->name = $data->name ?? null;
        $self->create = $data->create ?? null;
        $self->title = $data->title ?? null;
        $self->level = $data->level ?? null;
        $self->description = $data->description ?? null;
        $self->role = $data->role ?? null;
        $self->types = $data->types ?? null;
        $self->otherName = $data->otherName ?? null;
        $self->classStructure = $data->classStructure ?? null;
        $self->about = $data->about ?? null;

        return $self;
    }

    public function addSalaryData($data)
    {
        if (is_null($data)) {
            return null;
        }

        if (is_string($data)) {
            $data = json_decode($data);
        }

        return SalaryDTO::createFromData(
            amount: $data->amount ?? null,
            period: $data->period ?? null,
            name: $data->name ?? null,
        );
    }

    public function aboutToCreate($accountType)
    {
        return $accountType === $this->create;
    }

    public function setAccountClass()
    {
        $accountClass = capitalize($this->create);

        if ($this->create === 'parent') {
            $accountClass = 'ParentModel';
        }

        $this->accountClass = "App\\YourEdu\\$accountClass";

        return $this;
    }

    public function withAccount($account)
    {
        $clone = clone $this;

        $clone->account = $account;

        return $clone;
    }

    public function withUser($user)
    {
        $clone = clone $this;

        $clone->user = $user;

        return $clone;
    }
}
