<?php

namespace App\Services;

use App\DTOs\AdministrationDTO;
use App\Exceptions\AdministrationException;
use App\Http\Resources\AdminResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\AdminResponseNotification;
use App\Notifications\FacilitatorResponseNotification;
use App\Traits\ServiceTrait;
use App\User;
use App\YourEdu\School;
use Illuminate\Support\Facades\Notification;
use  Illuminate\Support\Str;
use  \Debugbar;

class AdministrationService
{
    use ServiceTrait;
    /**
     * school send admin request to a user.
     *
     * @param  User  $user
     * @param  School  $sender
     * @param  array  $adminDetails
     * @param  array  $fileDetails
     * @return \App\YourEdu\Request
     */
    public function sendUserAdminRequest(User $user,School $sender,array $adminDetails,
        $fileDetails)
    {
        $request = $sender->requestsSent()->create([
            'state' => 'PENDING'
        ]);
        $request->requestto()->associate($user);

        $data = [];
        $data['adminDetails'] = $adminDetails;
        if (count($fileDetails)) {
            foreach ($fileDetails as $requestFile) {
                $file = FileService::createAndAttachFiles(
                    account: $sender,
                    file: $requestFile,
                    item: $request
                );
                $data['file'][] = [
                    'id' => $file->id, 
                    'type' => class_basename_lower($file)
                ];
            }
        }

        $request->data = serialize($data);
        $request->save();

        return $request;
    }

    private function throwAdministrationException($message, $data = null)
    {
        throw new AdministrationException(
            message: $message,
            data: $data
        );
    }

    /**
     * Respond to a request for you to be an admin
     * 
     * @param string $requestId
     * @param string $action
     * @param string $accountType
     * @param int $id
     * 
     * @return object
     */

    public function adminRequestResponse(string $requestId,string $action,
        string $accountType,int $id,string $type)
    {
        $request = $this->getModel('request',$requestId);
        
        if ($type !== 'admin') {
            if ($request->requestto->user_id !== $id ||$request->requestto->owner_id !== $id) {
                $this->throwAdministrationException(
                    'the request you are trying to respond to was not addressed to you.'
                );
            }
        }
        $requestData = unserialize($request->data);
        $request->state = Str::upper($action);
        $request->save();

        
        $name = $request->requestto->username ? $request->requestto->username : $request->requestto->username;
        $message = "{$name} {$action} your request to be an admin of your {$accountType}";
        if ($accountType === 'school') {
            $fromUserIds = $request->requestfrom->getAdminIds();
            $admin = null;
            $requestData['adminDetails']['user_id'] = $id;
            if ($action === 'accepted') {
                $admin = $request->requestfrom->admins()->create($requestData['adminDetails']);
                self::createEmployment($admin,$request->requestto,
                    $requestData['adminDetails']['salary']);
            }     
            
            Notification::send(User::whereIn('id',$fromUserIds)->get(),
                new AdminResponseNotification(
                    $message,
                    is_null($admin) ? null : new AdminResource($admin),
                    [
                    'account' => 'school',
                    'accountId' => $request->requestfrom->id,
                    ]
                ));
        } else if ($accountType === 'facilitator') {
            $fromUserIds = $request->requestfrom->getAdminIds();
            if ($action === 'accepted') {
                self::createEmployment($request->requestfrom,$request->requestto,
                    $requestData['salary']);
            }  
            Notification::send(User::whereIn('id',$fromUserIds)->get(),
                new FacilitatorResponseNotification(
                    $message,
                    new UserAccountResource($request->requestto),
                    [
                    'account' => 'school',
                    'accountId' => $request->requestfrom->id,
                    ]
                ));  
        }

        return $request->requestfrom;
    }

    private function trackSchoolAdmin($administrationDTO)
    {

    }

    private static function createEmployment($employee,$employer,$salary)
    {
        $employment = $employee->employed()->create([
            'start_date' => now()
        ]);
        $employment->employer()->associatte($employer);
        $employment->salary()->create([
            'amount' => $salary
        ]);
    }

    public function createAdmin(AdministrationDTO $administrationDTO)
    {
        $administrationDTO = $this->setCreator($administrationDTO);

        $admin = $this->addAdministrator($administrationDTO);

        $this->checkAdministrator($admin, $administrationDTO);

        $administrationDTO = $administrationDTO->withAdministrator($admin);

        $this->attachAdminToSchool($administrationDTO);

        return $admin;
    }

    public function attachAdminToSchool($administrationDTO)
    {
        $school = null;
        if (is_not_null($administrationDTO->school)) {
            $school = $administrationDTO->school;
        }
        
        if (is_not_null($administrationDTO->schoolId)) {
            $school = $this->getModel('school', $administrationDTO->schoolId);
        }

        if (is_null($school)) {
            return;
        }

        $this->validateAdministratorAsSchooladmin($administrationDTO);

        $school->admins()->attach($administrationDTO->administrator);
        $school->save();
    }

    private function validateAdministratorAsSchooladmin($administrationDTO)
    {
        if ($administrationDTO->administrator?->isSchooladmin()) {
            return;
        }

        $this->throwAdministrationException(
            message: "you are not authorized. this administrator is required to be a school administrator",
            data: $administrationDTO
        );
    }

    public function setAdminState($administrator, $administrationDTO)
    {
        $this->validateCreatorAsSupervisor($administrationDTO);

        $administrator->state = $administrationDTO->state;
        $administrator->save();

        return $administrator;
    }

    private function validateCreatorAsSupervisor($administrationDTO)
    {
        if ($administrationDTO->creator->is_superadmin ||
            $administrationDTO->creator->is_supervisoradmin) {
            return;
        }

        $this->throwAdministrationException(
            message: "you are not authorized. you are required to be a YourEdu supervisor or super admin",
            data: $administrationDTO
        );
    }

    private function addAdministrator($administrationDTO)
    {
        return $administrationDTO->creator->admins()->create([
            'name' => $administrationDTO->name,
            'role' => strtoupper($administrationDTO->role),
            'title' => $administrationDTO->title,
            'level' => $administrationDTO->level ?: 1,
            'description' => $administrationDTO->description,
            'state' => "ACTIVE"
        ]);
    }

    private function checkAdministrator($administrator, $administrationDTO)
    {
        if (is_not_null($administrator)) {
            return;
        }

        $this->throwAdministrationException(
            message: "failed creating administrator",
            data: $administrationDTO
        );
    }

    private function setCreator(AdministrationDTO $administrationDTO)
    {
        if (is_not_null($administrationDTO->creator)) {
            return $administrationDTO;
        }

        return $administrationDTO->withCreator(
            $this->getModel($administrationDTO->account, $administrationDTO->accountId)
        );
    }
}