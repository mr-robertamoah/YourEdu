<?php 

namespace App\Services;

use App\Exceptions\AccountException;
use App\Exceptions\AccountNotFoundException;
use App\User;
use App\YourEdu\Profile;
use \Debugbar;
use Illuminate\Support\Str;

class AccountService
{
    const PAGINATION_LENGTH = 10;

    public function createAccountWithCreator($mainCreator,$creator,$create,$accountUser,
        $accountData)
    {        
        if ($creator !== 'user') {

            if (class_basename_lower(get_class($mainCreator)) !== $creator) {
                throw new AccountException("account not {$creator}");
            }
        }

        $mainAccount = null;
        if ($create !== 'admin') {            
            $accountData['create'] = $create;
            $accountData['user'] = $accountUser;
            $mainAccount = $this->createMainAccount($accountData);
        }

        if ($creator === 'school') {    
            $requestData = [];
            $recepient = null;
            if ($create === 'admin') {
                $requestData = $accountData;
                $requestData['role'] = 'SCHOOLADMIN';
                $recepient = $accountUser;
            }  else if ($create === 'facilitator') {
                $requestData['salary'] = $accountData['salary'];
                $requestData['salaryPeriod'] = $accountData['salaryPeriod'];
                $requestData['currency'] = $accountData['currency'];
                $recepient = $mainAccount;
            }
            if ($accountData['files']) {
                $requestData['files'] = [];
                foreach ($accountData['files'] as $file) {                    
                    $requestData['files'][] =  getFileDetails($file);
                }
            }
            (new RequestService())->schoolRelatedRequest($recepient,$mainCreator,
                $requestData,$create,$create === 'admin' ? $create : 'school facilitation');
        }
        
        
        $mainCreator->point->increment('value',2);
        
        return $create === 'admin' ? null : $mainAccount;
    }

    private function parentLearnerAttach($parent,$learner,$role){
         
        $parentRole = 'GUARDIAN';
        if ($role && ($role === 'FATHER' || $role === 'MOTHER')) {
            $parentRole = Str::upper($role);
        }

        $parent->wards()->attach($learner->id,[
            'role'=> $parentRole
        ]);
    }

    public function createMainAccount(array $data)
    {
        $account = null;
        
        Debugbar::info($data);
        if ($data['create'] === 'learner' ||  $data['create'] === 'parent' || $data['create'] === 'facilitator') {
            if ($data['name'] ==='' && is_null($data['name'])) {
                $data['name'] =  $data['user']->name;
            }

            $create = $data['create'];
            if (!is_null($data['user']->$create)) {
                throw new AccountException("You cannot have more than one {$data['create']} account.");
            }

            if ($data['create'] === 'parent' || $data['create'] === 'facilitator') {        
                if (!$data['user']->dob || now()->diffInYears($data['user']->dob) < 18 || 
                    $data['user']->dob->year === $data['user']->created_at->year) {
                    throw new AccountException("Please you must be 18 and above to create this account. Update your use dob.");
                }
            }
            
            $account = $data['user']->$create()->create([
                'name' => $data['name']
            ]);
        } else if ($data['create'] === 'professional') {
            $account = $data['user']->professionals()->create([
                'name' => $data['name'],
                'description' => $data['description'],
                'role' => $data['role'],
                'other_name' => $data['otherName'],
            ]);
        } else if ($data['create'] === 'school') {
            $account = $data['user']->schools()->create([
                'company_name' => $data['name'],
                'role' => $data['role'],
                'class_structure' => $data['classStructure'],
                'types' => $data['types'],
                'about' => $data['about'],
            ]);
        }  

        return $account;
    }

    public function createMainUser($input)
    {
        $user = User::create($input);

        if (is_null($user)) {
            throw new AccountException("User was not created or found.");
        }
        
        return $user;
    }

    public function createUserAccount
    (
        $userDataOne,
        $accountDataOne,
        $create,
        $creator = null,
        $creatorId = null,
        $parentRole = null,
        $userDataTwo = null,
        $accountDataTwo = null
    )
    {
        $mainCreator = getYourEduModel($creator,$creatorId);
        if (is_null($mainCreator)) {
            throw new AccountNotFoundException("{$creator} with id {$creatorId} not found");
        }

        $userOne = $this->createMainUser($userDataOne);
        $accountOne = $this->createAccountWithCreator($mainCreator,$creator,$create,
            $userOne,$accountDataOne);
        
        $userTwo = null;
        $accountTwo = null;
        if (!is_null($userDataTwo)) {
            $userTwo = $this->createMainUser($userDataTwo);
            $accountTwo = $this->createAccountWithCreator($mainCreator,$creator,'parent',
                $userTwo,$accountDataTwo);
            $this->parentLearnerAttach($accountTwo,$accountOne,$parentRole);
        }
        
        if ($creator === 'parent' && $create === 'learner') {
            $this->parentLearnerAttach($mainCreator,$accountOne,$parentRole);
        }

        return [
            'userOne' => $userOne,
            'accountOne' => $accountOne,
            'userTwo' => $userTwo,
            'accountTwo' => $accountTwo,
        ];
    }

}