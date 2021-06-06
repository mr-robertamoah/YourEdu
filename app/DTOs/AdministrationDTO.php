<?php

namespace App\DTOs;

use App\User;
use App\YourEdu\Admin;
use App\YourEdu\School;

class AdministrationDTO
{
    public ?string $account = null;
    public ?string $accountId = null;
    public ?string $name = null;
    public ?string $role = null;
    public ?string $level = null;
    public ?string $title = null;
    public ?string $state = null;
    public ?string $description = null;
    public ?string $schoolId = null;
    public ?Admin $administrator = null;
    public ?School $school = null;
    public ?User $creator = null;

    public static function new()
    {
        return new static;
    }

    public static function createFromData
    (
        $account = null,
        $accountId = null,
        $schoolId = null,
        $description = null,
        $level = null,
        $title = null,
        $role = null,
        $name = null,
        $state = null,
    )
    {
        $self = new static;

        $self->account = $account;
        $self->accountId = $accountId;
        $self->state = $state;
        $self->name = $name;
        $self->role = $role;
        $self->title = $title;
        $self->level = $level;
        $self->description = $description;
        $self->schoolId = $schoolId;

        return $self;
    }

    public function withSchool(School $school)
    {
        $clone = clone $this;

        $clone->school = $school;

        return $clone;
    }

    public function withAdministrator(Admin $administrator)
    {
        $clone = clone $this;

        $clone->administrator = $administrator;

        return $clone;
    }

    public function withCreator(User $creator)
    {
        $clone = clone $this;

        $clone->creator = $creator;

        return $clone;
    }
}
