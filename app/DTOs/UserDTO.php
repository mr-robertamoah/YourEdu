<?php

namespace App\DTOs;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserDTO
{
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $otherNames = null;
    public ?string $gender = null;
    public ?string $email = null;
    public ?string $referrerId = null;
    public ?string $username = null;
    public ?string $passwordConfirmation = null;
    public ?string $password = null;
    public ?Carbon $dob = null;
    public ?User $user = null;
    public array $updateInput = [];
    public array $createInput = [];

    public function addData
    (
        $referrerId = null,
    )
    {
        $this->referrerId = $referrerId;

        return $this;
    }

    public static function createFromData($data)
    {
        $self = new static;
    
        $self->password = $data->password ?? null;
        $self->passwordConfirmation = $data->passwordConfirmation ?? null;
        $self->firstName = $data->firstName ?? null;
        $self->lastName = $data->lastName ?? null;
        $self->gender = $data->gender ?? null;
        $self->email = $data->email ?? null;
        $self->otherNames = $data->otherNames ?? null;
        $self->username = $data->username ?? null;
        $self->dob = isset($data->dob) && is_not_null($data->dob) ? 
            Carbon::parse($data->dob) : null;

        return $self;
    }

    public static function createFromRequest(Request $request)
    {
        $self = new static;
    
        $self->password = $request->password ?? null;
        $self->passwordConfirmation = $request->passwordConfirmation ?? null;
        $self->firstName = $request->firstName ?? null;
        $self->lastName = $request->lastName ?? null;
        $self->gender = $request->gender ?? null;
        $self->email = $request->email ?? null;
        $self->otherNames = $request->otherNames ?? null;
        $self->username = $request->username ?? null;
        $self->user = $request->user();
        $self->dob = $request->dob ? Carbon::parse($request->dob) : null;

        return $self;
    }

    public function setUpUpdateInput()
    {
        $clone = $this;

        if (is_not_null($this->firstName)) {
            $clone->updateInput['first_name'] = $clone->firstName;
        }

        if (is_not_null($this->lastName)) {
            $clone->updateInput['last_name'] = $clone->lastName;
        }

        if (is_not_null($this->otherNames)) {
            $clone->updateInput['other_names'] = $clone->otherNames;
        }

        if (is_not_null($this->gender)) {
            $clone->updateInput['gender'] = $clone->gender;
        }

        if (is_not_null($this->email)) {
            $clone->updateInput['email'] = $clone->email;
        }

        if (is_not_null($this->dob)) {
            $clone->updateInput['dob'] = $clone->dob ?
                $clone->dob->toDateTimeString() : null;
        }

        return $clone;
    }

    public function setUpcreateInput()
    {
        $clone = $this;

        $clone->createInput['first_name'] = $clone->firstName;

        $clone->createInput['last_name'] = $clone->lastName;

        $clone->createInput['other_names'] = $clone->otherNames;

        $clone->createInput['gender'] = $clone->gender;

        $clone->createInput['email'] = $clone->email;

        $clone->createInput['username'] = $clone->username;

        $clone->createInput['referrer_id'] = $clone->referrerId;

        $clone->createInput['password'] = bcrypt($clone->password);

        $clone->createInput['dob'] = $clone->dob ?
            $clone->dob->toDateTimeString() : null;

        return $clone;
    }

    public function withUser($user)
    {
        $clone = clone $this;

        $clone->user = $user;

        return $clone;
    }
}
