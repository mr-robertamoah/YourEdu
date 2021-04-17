<?php

namespace App\Services;

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

    public function createAdmin()
    {
        
    }
}