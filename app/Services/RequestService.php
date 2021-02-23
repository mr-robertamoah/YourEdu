<?php

namespace App\Services;

use App\Events\NewSchoolable;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\RequestException;
use App\Http\Resources\AdminResource;
use App\Http\Resources\OwnedProfileResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\AccountsRequestNotification;
use App\Notifications\AccountsResponseNotification;
use App\Notifications\AdminResponseNotification;
use App\Notifications\FacilitatorResponseNotification;
use App\Notifications\RequestMessageNotification;
use App\Notifications\SchoolResponseNotification;
use App\User;
use App\YourEdu\ClassModel;
use App\YourEdu\Discussion;
use App\YourEdu\Facilitator;
use App\YourEdu\Learner;
use App\YourEdu\Message;
use App\YourEdu\ParentModel;
use App\YourEdu\Professional;
use App\YourEdu\Profile;
use App\YourEdu\Request;
use App\YourEdu\School;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class RequestService
{

    public function createRequest
    (
        $from, $to, $requestable = null, $data = null
    ): Request
    {
        $request = $from->requestsSent()->create([
            'state' => 'PENDING',
            'data' => serialize($data)
        ]);

        if (is_null($request)) {
            throw new RequestException(
                message: 'failed to crete and send request'
            );
        }

        $request->requestto()->associate($to);
        $request->requestable()->associate($requestable);
        $request->save();
        return $request;
    }
    /**
     * sending request between accounts and school
     * 
     * @param $recepient
     * @param $sender
     * @param array $requestDetails
     * 
     * @return Request
     */
    public function schoolRelatedRequest($recepient,$sender,$requestDetails,$type,$what)
    {
        $data = [];
        $data['adminId'] = $requestDetails['adminId'] ?? null;
        $data['attachments'] = $requestDetails['attachments'] ?? null;
        $data['main'] = $requestDetails['main'] ?? null;
        $data['requestType'] = $what;
        if ($type === 'admin' || $type === 'facilitator') {            
            $data['salary']['amount'] = $requestDetails['salary'];
            $data['salary']['currency'] = $requestDetails['currency'];
            $data['salary']['period'] = Str::upper($requestDetails['salaryPeriod']);
            if ($type === 'admin') {
                $data['adminDetails']['name'] = $requestDetails['name'];
                $data['adminDetails']['title'] = $requestDetails['title'];
                $data['adminDetails']['level'] = $requestDetails['level'];
                $data['adminDetails']['description'] = $requestDetails['description'];
                $data['adminDetails']['role'] = $requestDetails['role'];
            }
        }
        
        return $this->createMainDashboardRequest($recepient,$sender,$data,$requestDetails['files']);
    }

    /**
     * Responding to a request sent between accounts and schools
     * 
     * @param string $requestId
     * @param string $action
     * @param string $other
     * @param int $id
     * @param string $mine
     * 
     * @return object
     */

    public function schoolRelatedResponse(string $requestId,string $action,
        string $other,int $id,string $mine)
    {
        $request = getYourEduModel('request',$requestId);
        if (is_null($request)) {
            throw new AccountNotFoundException("request was not found with id {$requestId}");
        }

        if ($mine !== 'admin') {
            if ($request->requestto->user_id !== $id && $request->requestto->owner_id !== $id) {
                throw new RequestException('the request you are trying to respond to was not addressed to you.');
            }
        } else {
            if ($request->requestto_id !== $id) {
                throw new RequestException('the request you are trying to respond to was not addressed to you.');
            }
        }
        $requestData = unserialize($request->data);
        $request->state = Str::upper($action);
        $request->save();

        
        $name = $request->requestto->username ? $request->requestto->username : $request->requestto->name;
        
        if ($other === 'school') {
            $otherUserIds = $request->requestfrom->getAdminIds();
            $message = "{$name} {$action} your request to be {$mine} of your {$other}";
            if ($mine === 'admin') {
                
                $admin = null;
                $requestData['adminDetails']['user_id'] = $id;
                if ($action === 'accepted') {
                    $admin = $request->requestfrom->admins()->create($requestData['adminDetails']);
                    $this->createEmployment($admin,$request->requestfrom,
                        $requestData['salary']);
                }     
                
                Notification::send(User::whereIn('id',$otherUserIds)->get(),
                    new AdminResponseNotification(
                        $message,
                        is_null($admin) ? null : new AdminResource($admin),
                        [
                            'account' => 'school',
                            'accountId' => $request->requestfrom->id,
                        ]
                    ));
            } else if ($mine === 'facilitator') {                
                if ($action === 'accepted') {
                    $this->createEmployment($request->requestto,$request->requestfrom,
                        $requestData['salary']);

                    $this->attachToSchool($request->requestfrom,$request->requestto);
                } 
                
                Notification::send(User::whereIn('id',$otherUserIds)->get(),
                    new FacilitatorResponseNotification(
                        $message,
                        new UserAccountResource($request->requestto),
                        [
                            'account' => 'school',
                            'accountId' => $request->requestfrom->id,
                        ]
                    ));  
            } else {
                if ($action === 'accepted') {
                    $this->attachToSchool($request->requestfrom,$request->requestto);
                }  
            }
        } else if ($other === 'facilitator') {
            if ($mine === 'school') {
                $message = "{$name} {$action} your request to be {$other} in the {$mine}";               
                if ($action === 'accepted') {
                    $this->createEmployment($request->requestfrom,$request->requestto,
                        $requestData['salary']);

                    $this->attachToSchool($request->requestfrom,$request->requestto);
                }  
                $request->requestfrom->user->notfy(
                    new SchoolResponseNotification(
                        $message,
                        new UserAccountResource($request->requestto),
                        [
                            'account' => 'facllitator',
                            'accountId' => $request->requestfrom->id,
                        ]
                    ));  

                //broadcast to school
                broadcast(new NewSchoolable($other,new UserAccountResource($request->requestfrom),$request->requestto_id));
            }
        } else if ($other === 'admin') {
            if ($mine === 'school') {
                $message = "{$name} {$action} your request to be {$other} in the {$mine}";               
                if ($action === 'accepted') {
                    $admin = $request->requestto->admins()->create($requestData['adminDetails']);
                    $this->createEmployment($admin,$request->requestto,
                        $requestData['salary']);
                }  
                $admin->user->notfy(
                    new SchoolResponseNotification(
                        $message,
                        new OwnedProfileResource($request->requestto->profile),
                        [
                            'account' => 'admin',
                            'accountId' => $admin->id,
                        ]
                    ));  

                //broadcast to school
                broadcast(new NewSchoolable($other,new AdminResource($admin),$request->requestto_id));
            }
        }

        return $request->requestfrom;
    }

    public function sendMessage($requestId,$account,$accountId,$userId,$message,$state,$file)
    {
        $messageData = (new MessageService())->createMessage('request',$requestId,$account,
            $accountId,$userId,$message,$state,$file);

        $recepientUserIds = [];
        if ($messageData['belongsTo']->requestto_type === 'App\YourEdu\School' && 
            $account !== 'school') {
            $recepientUserIds = $messageData['belongsTo']->requestto->getAdminIds();
        } else if ($messageData['belongsTo']->requestfrom_type === 'App\YourEdu\School' &&
            $account !== 'school') {
            $recepientUserIds = $messageData['belongsTo']->requestfrom->getAdminIds();
        } else if ($messageData['belongsTo']->requestto_type === 'App\User' && 
            $account === 'school') {
            $recepientUserIds[] = $messageData['belongsTo']->requestto_id;
        } else if ($messageData['belongsTo']->requestfrom_type === 'App\User' &&
            $account === 'school') {
            $recepientUserIds[] = $messageData['belongsTo']->requestfrom_id;
        } else if ($messageData['belongsTo']->requestto_type === 'App\YourEdu\School' && 
            $account === 'school') {
            $recepientUserIds[] = $messageData['belongsTo']->requestfrom->user_id;
        } else if ($messageData['belongsTo']->requestfrom_type === 'App\YourEdu\School' && 
            $account === 'school') {
            $recepientUserIds[] = $messageData['belongsTo']->requestto->user_id;
        }

        if ($account === 'school') {
            $notificationMessage = "please check requests from your Nav. a request has a new message";
        } else {
            $notificationMessage = "please check requests in school's dashboard. a request has a new message";
        }
        
        Notification::send(User::whereIn('id',$recepientUserIds)->get(),
            new RequestMessageNotification($notificationMessage));
        
        return $messageData['message'];
    }

    public function searchAccounts($account,$search,$type = null,$account2 = null)
    {
        $searchText = "%$search%";
        if ($account !== 'class') {            
            $data = Profile::query();
    
            if ($account !== 'user') {
                $profileable = [];
                $account = Str::title($account);
                $profileable[] = "App\\YourEdu\\{$account}";
                if ($account2) {
                    $account = Str::title($account2);
                    $profileable[] = "App\\YourEdu\\{$account}";
                }
                $data = $data
                ->whereHasMorph('profileable',$profileable, function($query) use ($searchText,$type){
                    if ($type) {
                        $query
                        ->where('name','like',$searchText)
                        ->where('role',Str::upper($type));
                    } else {
                        $query->where('name','like',$searchText);
                    }
                })->get();
            } else if ($account === 'user') {
                $data = $data->whereHas('user', function($query) use ($searchText){
                    $query
                    ->where('first_name','like',$searchText)
                    ->orWhere('last_name','like',$searchText)
                    ->orWhere('other_names','like',$searchText);
                })->get()->pluck('user')->unique();
            }
        } else {
            $data = ClassModel::where('name','like',$searchText)
                ->where('ownedby_type','App\YourEdu\Facilitator');
            if ($type) {
                $data->where('type',Str::upper($type));
            }

            $data = $data->get();
        }

        return $data;
    }

    public function sendAccountRequest($from,$fromId,$to,$toId,$item,$itemId,$data,
        $files = null,$adminId = null,$what = null)
    { 
        $requestfrom = getYourEduModel($from,$fromId);
        if (is_null($requestfrom)) {
            throw new AccountNotFoundException("$from not found with id $fromId");
        }

        if ($to === 'user') {
            $requestto = User::where('username',$toId);
        } else {
            $requestto = getYourEduModel($to,$toId);
        }
        if (is_null($requestto)) {
            throw new AccountNotFoundException("$to not found with $toId");
        }

        $requestData = [];
        $requestData['main'] = getYourEduModel($item,$itemId); //ward
        if ($files) {
            $requestData['files'] = [];
            foreach ($files as $file) {                    
                $requestData['files'][] =  getFileDetails($file);
            }
        }

        switch ($what) {
            case 'school facilitation': //2
                
                break;
            
            case 'admin':
                
                break;
            
            case 'school learning':
                
                break;
            
            case 'extracurriculum':
                
                break;
            
            case 'course':
                
                break;
            
            case 'collaboration':
                
                break;
            
            case 'class learning': //3 facilitator parent learner
                
                break;
            
            case 'school learning'://3 school parent learner
                
                break;
            
            case 'nanny':
                
                break;
            
            case 'home facilitation':
                
                break;
                        
            default:
                
                break;
        }

        if ($from === 'school' || $to === 'school') {
            if ($to === 'user' || $from === 'user') {                
                $schoolRequestType = 'admin';
            } else if ($to !== 'user' && $to !== 'school') {
                $schoolRequestType = $to;
            } else if ($from !== 'user' && $from !== 'school') {
                $schoolRequestType = $to;
            }
            $requestData = $data;
            if ($schoolRequestType === 'admin') {
                $requestData['role'] = 'SCHOOLADMIN';
            } else if ($schoolRequestType === 'facilitator') {

            }
            if ($from === 'school') {
                $requestData['adminId'] = $adminId;
            }

            $request = $this->schoolRelatedRequest($requestto,$requestfrom,
                $requestData,$schoolRequestType, $what);
        } else if ($from === 'class' || $to === 'class') {
            if ($to !== 'class') {
                $classRequestType = $to;
            } else if ($from !== 'class') {
                $classRequestType = $to;
            }
            $request = $this->classRelatedRequest($requestto,$requestfrom,
                $requestData,$classRequestType);
        } else {

            $request = $this->accountsRelatedRequest($requestto,$requestfrom,
                $requestData,$what);
        }

        $this->notifyAppropriateUsers($request->requestto,$request->requestfrom,'request');
        return $request;
    }

    public function notifyAppropriateUsers($requestto,$requestfrom,$type)
    {
        $users = [];
        $from = class_basename_lower($requestfrom);
        $to = class_basename_lower($requestto);
        if ($type === 'request') {
            switch ($to) {
                case 'learner':
                    if ($requestto->user->age >= 18) {
                        $users[] = $requestto->user;
                        
                        $message = "";
                    } else {                    
                        foreach ($requestto->user->parents as $parent) {
                            $users[] = $parent->user;
                        }
                    }
                    break;
                case 'class':
                    $users[] = $requestto->ownedby->user;
                    break;
                case 'school':
                    $users = User::whereIn('id',$requestto->getAdminIds());
                    break;
                case 'collaboration':
                    
                    break;
                
                case 'facilitator':
                    $users[] = $requestto->user;
                    break;
                
                case 'professional':
                    $users[] = $requestto->user;
                    break;
                
                case 'user':
                    $users[] = $requestto;
                    break;
                
                default:
                    
                    break;
            }
            Notification::send($users,
                new AccountsRequestNotification());
        } else {
            switch (class_basename_lower($requestfrom)) {
                case 'learner':
                    if ($requestfrom->user->age >= 18) {
                        $users[] = $requestfrom->user;
                        $message = "";
                    } else {                    
                        foreach ($requestfrom->user->parents as $parent) {
                            $users[] = $parent->user;
                        }
                    }
                    break;
                case 'class':
                    $users[] = $requestfrom->ownedby->user;
                    break;
                case 'school':
                    $users = User::whereIn('id',$requestfrom->getAdminIds());
                    break;
                case 'collaboration':
                    
                    break;
                
                case 'facilitator':
                    $users[] = $requestfrom->user;
                    break;
                
                case 'professional':
                    $users[] = $requestfrom->user;
                    break;
                
                case 'user':
                    $users[] = $requestfrom;
                    break;
                
                default:
                    
                    break;
            }
            Notification::send($users,
                new AccountsResponseNotification());
        }
    }

    /**
     * sending request between accounts and class
     * 
     * @param $recepient
     * @param $sender
     * @param array $requestDetails
     * 
     * @return Request
     */
    public function classRelatedRequest($recepient,$sender,$requestDetails,$type)
    {
        $data = [];
        $data['main'] = $requestDetails['main'] ?? null;
        $data['attachments'] = $requestDetails['attachments'] ?? null;
        if ($type === 'facilitator') {     
            $data['commission'] = $requestDetails['commission'] ?? null;
        } else if ($type === 'parent') {     

        } else if ($type === 'learner') {

        }
        
        $request = $this->createMainDashboardRequest($recepient,$sender,$data,$requestDetails['files']);

        return $request;
    }

    public function accountsRelatedRequest($recepient,$sender,$requestDetails,$type)
    {
        $data = [];

        return $this->createMainDashboardRequest($recepient,$sender,$data,$requestDetails['files']);
    }

    private function createMainDashboardRequest($recepient,$sender,$data,$files)
    {
        $request = $sender->requestsSent()->create([
            'state' => 'PENDING'
        ]);
        $request->requestto()->associate($recepient);

        if (count($files)) {
            foreach ($files as $requestFile) {
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

    public function getMessages($requestId)
    {
        $request = getYourEduModel('request',$requestId);
        if (is_null($request)) {
            throw new AccountNotFoundException("request not found with id {$requestId}");
        }

        return $request->messages()->latest()->paginate(5);
    }

    private function attachToSchool($school,$account)
    {             
        $account->schools()->attach($school);
        $account->save();
    }

    private function createEmployment($employee,$employer,array $salary)
    {
        $employment = $employee->employed()->create([
            'start_date' => now()
        ]);
        $employment->employer()->associate($employer);
        $employment->salaries()->create([
            'amount' => $salary['amount'],
            'period' => $salary['period'],
        ]);
    }

    public function getUserRequests($id)
    {
        $request = Request::where('state','PENDING')
            ->where(function($query) use ($id){
                $query
                ->whereHasMorph('requestto',[User::class],function($query3) use ($id){
                    $query3->where('id',$id);
                })
                ->orWhereHasMorph('requestto',
                    [ParentModel::class,Learner::class,Facilitator::class,
                        Professional::class,School::class],
                    function($query2, $type) use ($id){
                    if ($type == 'App\YourEdu\School') {
                        $query2->where('owner_id',$id);
                    } else if ($type != 'App\User') {
                        $query2->where('user_id',$id);
                    }
                });
            }) 
            ->with(['requestfrom.profile'])
            ->with(['requestable'=>function($query){
                $query->morphWith([
                    Message::class =>['images','videos','audios','files',],
                    Discussion::class =>['images','videos','audios','files','likes',
                        'comments','beenSaved','raisedby.profile','attachments',
                        'participants'],
                ]);
            }])->get();

        return $request->sortByDesc('updated_at');
    }
    
    public function getAccountRequests($account,$accountId)
    {
        $mainAccount = getYourEduModel($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        $requests = new Collection();
        $requests = $requests->merge($mainAccount->requestsSent()
            ->where('requestable_type',null)->get());
        $requests = $requests->merge($mainAccount->requestsReceived()
            ->where('requestable_type',null)->get());

        return $requests->sortByDesc('updated_at');
    }

    public function declineRequest($requestId,$id)
    {
        $mainRequest = Request::find($requestId);
        if (is_null($mainRequest)) {
            throw new AccountNotFoundException("request with id {$requestId} not found");
        }

        $requestTo = getYourEduModel(class_basename_lower($mainRequest->requestto_type),
            $mainRequest->requestto_id);

        if (is_null($requestTo) || $requestTo->user_id !== $id || 
            $requestTo->owner_id !== $id) {
            throw new RequestException('unsuccessful. request is to no account');
        }
    
        $mainRequest->update([
            'state' => 'DECLINED'
        ]);
    }
}
