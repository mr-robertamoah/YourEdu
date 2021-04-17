<?php

namespace App\Services;

use App\Contracts\DashboardItemContract;
use App\DTOs\ActivityTrackDTO;
use App\DTOs\AdmissionDTO;
use App\DTOs\CommissionDTO;
use App\DTOs\DiscountDTO;
use App\DTOs\FeeDTO;
use App\DTOs\MessageDTO;
use App\DTOs\PaymentDTO;
use App\DTOs\RequestDTO;
use App\DTOs\RequestSearchDTO;
use App\DTOs\ResponseDTO;
use App\DTOs\SalaryDTO;
use App\Events\DeleteRequestMessage;
use App\Events\NewRequestMessage;
use App\Events\NewSchoolable;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\RequestException;
use App\Http\Resources\AdminResource;
use App\Http\Resources\OwnedProfileResource;
use App\Http\Resources\UserAccountResource;
use App\Notifications\AccountRequestNotification;
use App\Notifications\AccountResponseNotification;
use App\Notifications\AccountsResponseNotification;
use App\Notifications\AdminResponseNotification;
use App\Notifications\FacilitatorResponseNotification;
use App\Notifications\RequestMessageNotification;
use App\Notifications\SchoolResponseNotification;
use App\Traits\ServiceTrait;
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
    use ServiceTrait;
    
    const VALIDACCOUNTREQUESTACTION = [
        'collaboration', 'admission', 'administration', 'learning', 'facilitation'
    ];

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
            $this->throwRequestException(
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
        $request = $this->getModel('request',$requestId);

        if ($mine !== 'admin') {
            if ($request->requestto->user_id !== $id && $request->requestto->owner_id !== $id) {
                $this->throwRequestException(
                    'the request you are trying to respond to was not addressed to you.'
                );
            }
        } else {
            if ($request->requestto_id !== $id) {
                $this->throwRequestException(
                    'the request you are trying to respond to was not addressed to you.'
                );
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

    public function createAccountResponse(ResponseDTO $responseDTO)
    {
        $request = $this->getRequest($responseDTO);
        
        $this->checkResponder($request);

        $this->validateResponderUsingUserId($request, $responseDTO);

        $request = $this->setResponse($request, $responseDTO);

        $requestDTO = $this->getRequestDTO($request);

        $requestDTO = $requestDTO->withResponseDTO($responseDTO);

        $requestDTO = $requestDTO->withRequest($request);

        $this->completeRequestProcess($requestDTO);

        $this->sendResponse($requestDTO);

        // $this->broadcastResponse($requestDTO);
    }

    private function broadcastResponse(RequestDTO $requestDTO)
    {
        $events = $this->getResponseEvents($requestDTO);

        foreach ($events as $event) {
            broadcast($event)->toOthers();
        }
    }

    private function getResponseEvents(RequestDTO $requestDTO)
    {
        if ($requestDTO->action === 'learning') {
            return;
        }
        
    }

    private function completeRequestProcess($requestDTO)
    {
        if ($requestDTO->state === 'DECLINED') {
            return;
        }

        $this->completeLearningRequest($requestDTO);

        $this->completeFacilitationRequest($requestDTO);

        $this->completeCollaborationRequest($requestDTO);

        $this->completeAdmissionRequest($requestDTO);
        
        $this->completeAdministrationRequest($requestDTO);

        $this->completeRequestSalaries($requestDTO->request);

        $this->completeRequestFees($requestDTO->request);

        $this->completeRequestDiscounts($requestDTO->request);

        $this->completeRequestCommissions($requestDTO->request);
    }

    private function completeLearningRequest(RequestDTO $requestDTO)
    {
        if ($requestDTO->action !== 'learning') {
            return;
        }

        $account = $this->getMyAccountFromRequestUsingUserId(
            $requestDTO->request, $requestDTO->responseDTO->userId
        );

        if ($account->accountType === 'learner') {
            $account = $this->getOtherAccountFromRequestUsingUserId(
                $requestDTO->request, $requestDTO->responseDTO->userId
            );
        }

        $requestDTO->request->requestable->learners()->attach($account);

        $requestDTO->request->requestable->save();
    }

    private function completeFacilitationRequest(RequestDTO $requestDTO)
    {
        if ($requestDTO->action !== 'facilitation') {
            return;
        }

        //get facilitator
        //add facilitator to requestable
        //if requestable owner is school, create employment
    }

    private function completeCollaborationRequest(RequestDTO $requestDTO)
    {
        if ($requestDTO->action !== 'collaboration') {
            return;
        }

        //get my account
        //if am owner of collaboration then i will add other account
        //else my account will be added
        
    }

    private function completeAdmissionRequest(RequestDTO $requestDTO)
    {
        if ($requestDTO->action !== 'admission') {
            return;
        }
        
        //get learner
        //add learner to school
        //attach learner to grade if theres one
    }

    private function completeAdministrationRequest(RequestDTO $requestDTO)
    {
        if ($requestDTO->action !== 'administration') {
            return;
        }
        
        //get user
        //create admin using admin data
        //add admin to school
        //create employment
    }

    private function completeRequestSalaries($request)
    {
        if (is_null($request->requestable)) {
            return;
        }
        
        foreach ($request->salaries as $salary) {
            $salaryDTO = SalaryDTO::createFromData()
                ->withsalary($salary)
                ->withOwnedby(
                    $this->getPaymentBeneficiaryFromRequest($request)
                )
                ->withDashboardItem($request->requestable);

            SalaryService::associateSalaryToOwnedbyAndItem($salaryDTO);
        }
    }

    private function completeRequestFees($request)
    {
        if (is_null($request->requestable)) {
            return;
        }

        if (class_basename_lower($request->requestable) !== 'class') {
            return;
        }

        foreach ($request->fees as $fee) {
            $feeDTO = FeeDTO::createFromData()
                ->withFee($fee)
                ->withClass($request->requestable);

            (new FeeService)->attachClassToFee($feeDTO);
        }
    }

    private function completeRequestCommissions($request)
    {
        if (is_null($request->requestable)) {
            return;
        }

        foreach ($request->commissions as $commission) {
            $commissionDTO = CommissionDTO::createFromData()
                ->withCommission($commission)
                ->withOwnedby(
                    $this->getPaymentBeneficiaryFromRequest($request)
                )
                ->withDashboardItem($request->requestable);

            (new CommissionService)->associateCommissionToOwnedbyAndItem($commissionDTO);
        }
    }

    private function completeRequestDiscounts($request)
    {
        if (is_null($request->requestable)) {
            return;
        }

        foreach ($request->discounts as $discount) {
            $discountDTO = DiscountDTO::createFromData()
                ->withDiscountable($request->requestable)
                ->withOwnedby(
                    $this->getPaymentBeneficiaryFromRequest($request)
                )
                ->withDiscount($discount);

            (new DiscountService)->associateDiscountToOwnedbyAndItem($discountDTO);
        }
    }

    private function sendResponse($requestDTO)
    {
        $request = $requestDTO->request;
        $requestDTO->message = $this->getResponseNotificationMessageBasedOnAction($request, $requestDTO);

        Notification::send(
            User::whereIn('id', $request->requestfrom->getAuthorizedIds())->get(),
            new AccountResponseNotification(
                requestDTO: $requestDTO->cleanUp()->withRequest($request)
            )
        );
    }

    private function getResponseNotificationMessageBasedOnAction($request, $requestDTO)
    {
        if ($requestDTO->action === 'learning') {
            return $this->getResponseNotificationLearningActionMessage(
                $request, $requestDTO
            );
        }

        if ($requestDTO->action === 'facilitation') {
            return $this->getResponseNotificationFacilitationActionMessage(
                $request, $requestDTO
            );
        }

        if ($requestDTO->action === 'collaboration') {
            return $this->getResponseNotificationCollaborationActionMessage(
                $request, $requestDTO
            );
        }

        if ($requestDTO->action === 'admission') {
            return $this->getResponseNotificationAdmissionActionMessage(
                $request, $requestDTO
            );
        }

        return $this->getResponseNotificationAdministrationActionMessage(
            $request, $requestDTO
        );
    }

    private function getResponseNotificationLearningActionMessage
    (
        $request,
        $requestDTO
    )
    {
        $item = class_basename_lower($request->requestable);

        if ($request->requestto->accountType === "learner") {
            return "has {$requestDTO->responseDTO->response} to access the $item with name: {$request->requestable->name}";
        }

        return "has {$requestDTO->responseDTO->response} granting you access to the $item with name: {$request->requestable->name}";
    }

    private function getResponseNotificationFacilitationActionMessage
    (
        $request,
        $requestDTO
    )
    {
        $item = class_basename_lower($request->requestable);

        if ($request->requestto->is($request->requestable->ownedby)) {
            return "has {$requestDTO->responseDTO->response} your request to facilitate the $item with name: {$request->requestable->name}";
        }

        return "has {$requestDTO->responseDTO->response} your request to have a look at and facilitate your $item with name: {$request->requestable->name}";
    }

    private function getResponseNotificationCollaborationActionMessage
    (
        $request,
        $requestDTO
    )
    {
        if (! $request->requestto->is($request->requestable->addedby)) {
            return "has {$requestDTO->responseDTO->response} your request to have him/her join your collaboration with name: {$request->requestable->name}";
        }

        return "has {$requestDTO->responseDTO->response} your request to join the collaboration with name: {$request->requestable->name}";
    }

    private function getResponseNotificationAdmissionActionMessage
    (
        $request,
        $requestDTO
    )
    {
        if ($request->requestto->accountType === "learner") {
            return "has {$requestDTO->responseDTO->response} your request to have him/her look at and become a learner of your school with name: {$request->requestfrom->profile->name}";
        }

        return "has {$requestDTO->responseDTO->response} your request to become a learner of school with the name: {$request->requestto->profile->name}";
    }

    private function getResponseNotificationAdministrationActionMessage
    (
        $request,
        $requestDTO
    )
    {
        if ($request->requestto->accountType !== "school") {
            return "has {} your request to have him/her become an administrator for your school with name: {$request->requestfrom->profile->name}";
        }

        return "has {} your request to become an administrator for the school with name: {$request->requestto->profile->name}";
    }

    private function getRequestDTO($request)
    {
        return unserialize($request->data);
    }

    private function setResponse($request, $responseDTO)
    {
        if (! in_array($responseDTO->response, ['accepted', 'declined'])) {
            $this->throwRequestException(
                message: "a valid response was not provided",
                data: compact($request, $responseDTO)
            );
        }

        $request->state = strtoupper($responseDTO->response);
        $request->save();

        return $request;
    }

    private function checkResponder($request)
    {
        if (is_not_null($request->requestto)) {
            return;
        }

        $this->throwRequestException(
            message: "sorry you cannot respond, because this request doesn't have a responder",
            data: $request
        );
    }

    private function validateResponderUsingUserId($request, $dto)
    {
        if ($request->requestto->isAuthorizedUserById($dto->userId)) {
            return;
        }

        $this->throwRequestException(
            message: "you are not authorized to respond to a request for {$request->requestto->accountType} account with id {$request->requestto->id}",
            data: compact($request, $dto)
        );
    }

    private function getRequest($dto)
    {
        return $this->getModel('request', $dto->requestId);
    }

    public function createAccountRequests(RequestDTO $requestDTO)
    {
        $requestDTO = $this->getRequester($requestDTO);

        $this->validateRequesterUser($requestDTO);
        
        $requestDTO = $this->getSender($requestDTO);

        $requests = $this->createRequestsBasedOnAction($requestDTO);
        
        $requests = $this->addPaymentsToRequests($requests, $requestDTO);
        
        $requests = $this->addDiscountToRequests($requests, $requestDTO);

        $requests = $this->addFilesToRequests($requests, $requestDTO);

        $requestDTO->method = __METHOD__;
        $this->trackSchoolAdmin($requestDTO);

        $this->sendRequests($requests, $requestDTO);
        
        $requests = $this->addDataToRequests($requests, $requestDTO);
    }

    private function addDataToRequests($requests, $requestDTO)
    {
        foreach ($requests as $request) {
            $request->update([
                'data' => serialize($requestDTO->cleanUp())
            ]);
        }

        return $requests;
    }

    private function validateRequesterUser(RequestDTO $requestDTO)
    {
        if (in_array($requestDTO->userId, $requestDTO->requester->getAuthorizedIds())) {
            return;
        }

        $this->throwRequestException(
            message: "you are not authorized to send the request using this {$requestDTO->requester->accountType} account",
            data: $requestDTO
        );
    }

    private function trackSchoolAdmin
    (
        $requestDTO,
    )
    {
        if ($requestDTO->sender->accountType !== 'school') {
            return;
        }

        if (! $requestDTO->adminId) {
            return;
        }

        $admin = $this->getModel('admin',$requestDTO->adminId);
        
        (new ActivityTrackService)->trackActivity(
            ActivityTrackDTO::createFromData(
                activityfor: $requestDTO->sender,
                performedby: $admin,
                action: $requestDTO->method
            )
        );
    }

    private function sendRequests($requests, $requestDTO)
    {
        if (!count($requests)) {
            return;
        }

        foreach ($requests as $request) {
            $users = User::whereIn('id', $request->requestto->getAuthorizedIds())->get();
            
            if (!count($users)) {
                continue;
            }

            $requestDTO->message = $this->getRequestNotificationMessageBasedOnAction($request, $requestDTO);

            Notification::send(
                $users,
                new AccountRequestNotification(
                    requestDTO: $requestDTO->cleanUp()->withRequest($request)
                )
            );
        }
    }

    private function getRequestNotificationMessageBasedOnAction
    (
        $request,
        $requestDTO
    ) : string
    {
        if ($requestDTO->action === 'learning') {
            return $this->getRequestNotificationLearningActionMessage(
                $request, $requestDTO
            );
        }

        if ($requestDTO->action === 'facilitation') {
            return $this->getRequestNotificationFacilitationActionMessage(
                $request, $requestDTO
            );
        }

        if ($requestDTO->action === 'collaboration') {
            return $this->getRequestNotificationCollaborationActionMessage(
                $request, $requestDTO
            );
        }

        if ($requestDTO->action === 'admission') {
            return $this->getRequestNotificationAdmissionActionMessage(
                $request, $requestDTO
            );
        }

        return $this->getRequestNotificationAdministrationActionMessage(
            $request, $requestDTO
        );
    }

    private function getRequestNotificationLearningActionMessage
    (
        $request,
        $requestDTO
    )
    {
        $item = class_basename_lower($request->requestable);

        if ($requestDTO->sender->accountType === "learner") {
            return "wants to have access to your $item with name: {$request->requestable->name}";
        }

        return "wants you to have a look at and access the $item with name: {$request->requestable->name}";
    }

    private function getRequestNotificationFacilitationActionMessage
    (
        $request,
        $requestDTO
    )
    {
        $item = class_basename_lower($request->requestable);

        if ($requestDTO->sender->is($request->requestable->ownedby)) {
            return "wants to facilitate the $item with name: {$request->requestable->name}";
        }

        return "wants you to have a look at and facilitate your $item with name: {$request->requestable->name}";
    }

    private function getRequestNotificationCollaborationActionMessage
    (
        $request,
        $requestDTO
    )
    {
        if (! $requestDTO->sender->is($request->requestable->addedby)) {
            return "wants to join your collaboration with name: {$request->requestable->name}";
        }

        return "wants you to join the collaboration with name: {$request->requestable->name}";
    }

    private function getRequestNotificationAdmissionActionMessage
    (
        $request,
        $requestDTO
    )
    {
        if ($requestDTO->sender->accountType === "learner") {
            return "wants to become a learner of your school with name: {$request->requestto->profile->name}";
        }

        return "wants you to have a look at and become a learner of school with the name: {$requestDTO->sender->profile->name}";
    }

    private function getRequestNotificationAdministrationActionMessage
    (
        $request,
        $requestDTO
    )
    {
        if ($requestDTO->sender->accountType !== "school") {
            return "wants to become an administrator for your school with name: {$request->requestto->profile->name}";
        }

        return "wants you to become an administrator for the school with name: {$requestDTO->sender->profile->name}";
    }

    private function addPaymentsToRequests
    (
        $requests,
        RequestDTO $requestDTO
    )
    {
        if (is_null($requestDTO->paymentDTO)) {
            return $requests;
        }

        if ($requestDTO->paymentDTO->type === 'salary') {
            return $this->setMultipleSalaryPayment($requests, $requestDTO);
        }

        if ($requestDTO->paymentDTO->type === 'commission') {
            return $this->setMultipleCommissionPayment($requests, $requestDTO);
        }

        if ($requestDTO->paymentDTO->type === 'fee') {
            return $this->setMultipleFeePayment($requests, $requestDTO);
        }

        foreach ($requests as $request) {
            $requestDTO->paymentDTO = PaymentService::setMultiplePaymentOnItemBasedOnPaymentType(
                $requestDTO->paymentDTO
                    ->withAddedby($requestDTO->sender)
                    ->withDashboardItem($request)
            );

            $request->refresh();
        }

        return $requests;
    }

    private function setMultipleSalaryPayment($requests, $requestDTO)
    {
        $requestDTO->paymentDTO = PaymentService::setMultiplePaymentOnItemBasedOnPaymentType(
            $requestDTO->paymentDTO->dontCheckDasboardItem()
                ->withAddedby($requestDTO->sender)
        );

        for ($i = 0; $i < count($requests); $i++) {
            if (is_null($requests[$i]->requestable)) {
                continue;
            }

            foreach ($requestDTO->paymentDTO->payments as $salary) {
                $requests[$i]->salaries()->attach($salary);
                $requests[$i]->save();
            }

            $requests[$i] = $requests[$i]->refresh();
        }

        return $requests;
    }
    
    private function setMultipleCommissionPayment($requests, $requestDTO)
    {
        $requestDTO->paymentDTO = PaymentService::setMultiplePaymentOnItemBasedOnPaymentType(
            $requestDTO->paymentDTO->dontCheckDasboardItem()
                ->withAddedby($requestDTO->sender)
        );

        for ($i = 0; $i < count($requests); $i++) {
            if (is_null($requests[$i]->requestable)) {
                continue;
            }

            foreach ($requestDTO->paymentDTO->payments as $salary) {
                $requests[$i]->commissions()->attach($salary);
                $requests[$i]->save();
            }

            $requests[$i] = $requests[$i]->refresh();
        }

        return $requests;
    }
    
    private function setMultipleFeePayment($requests, $requestDTO)
    {
        $requestDTO->paymentDTO = PaymentService::setMultiplePaymentOnItemBasedOnPaymentType(
            $requestDTO->paymentDTO->dontCheckDasboardItem()
                ->withAddedby($requestDTO->sender)
        );

        foreach ($requestDTO->paymentDTO->payments as $fee) {
            $requests = $this->attachItemToRequestablesOfRequests($requests, $fee);
        }

        return $requests;
    }

    private function addDiscountToRequests
    (
        $requests,
        RequestDTO $requestDTO
    )
    {
        if (is_null($requestDTO->discountDTO)) {
            return $requests;
        }

        if (!count($requests)) {
            return $requests;
        }

        if (!count($requestDTO->items)) {
            return $requests;
        }

        $discount = (new DiscountService)->createDiscount(
            $requestDTO->discountDTO->withAddedby($requestDTO->sender)
                ->setToNotRequiringDiscountable()
        );
        
        foreach ($requests as $request) {
            $request->discounts()->attach($discount);
            $request->save();
            $request->refresh();
        }

        return $requests;
    }

    private function getPaymentBeneficiaryFromRequest
    (
        Request $request,
    )
    {
        if (is_null($request->requestable)) {
            return null;
        }

        if ($request->requestto->accountType === 'learner') {
            return $request->requestto;
        }

        if ($request->requestto->accountType === 'user') {
            return $request->requestto;
        }

        if (! $request->requestto->is($request->requestable->ownedby)) {
            return $request->requestto;
        }

        return $request->requestfrom;
    }

    private function addFilesToRequests
    (
        array $requests, 
        RequestDTO $requestDTO
    )
    {
        $createdFiles = [];
        
        foreach ($requestDTO->files as $file) {
            $createFiled[] = FileService::createFile(
                $requestDTO->sender,
                $file
            );
        }

        foreach ($requests as $request) {
            
            foreach ($createdFiles as $file) {
                $request = FileService::attachFileToItem($createFiled, $request);
            }
        }

        return $requests;
    }

    private function createRequestsBasedOnAction(RequestDTO $requestDTO)
    {
        $this->checkRequestTypeValidity($requestDTO);

        if ($requestDTO->action === 'learning') {
            return $this->makeLearningRelatedRequests($requestDTO);
        }

        if ($requestDTO->action === 'facilitation') {
            return $this->makeFacilitationRelatedRequests($requestDTO);
        }

        if ($requestDTO->action === 'collaboration') {
            return $this->makeCollaborationRelatedRequests($requestDTO);
        }

        if ($requestDTO->action === 'admission') {
            return $this->makeAdmissionRelatedRequests($requestDTO);
        }

        return $this->makeAdministrationRelatedRequests($requestDTO);
    }

    private function makeLearningRelatedRequests(RequestDTO $requestDTO)
    {
        $this->checkRequestItems($requestDTO);

        if ($requestDTO->sender->accountType === 'learner') {
            return $this->makeRequestsBasedOnItemsOnly($requestDTO);
        }

        $this->checkRequestReceivers($requestDTO);

        return $this->makeRequestsBasedOnItemsAndSendees($requestDTO);
    }

    private function makeFacilitationRelatedRequests(RequestDTO $requestDTO)
    {
        $this->checkRequestItems($requestDTO);

        if ($requestDTO->sender->accountType === 'facilitator') {
            return $this->makeRequestsBasedOnItemsOnly($requestDTO);
        }

        $this->checkRequestReceivers($requestDTO);

        return $this->makeRequestsBasedOnItemsAndSendees($requestDTO);
    }

    private function makeCollaborationRelatedRequests(RequestDTO $requestDTO)
    {
        $this->checkRequestItems($requestDTO);

        $this->checkRequestReceivers($requestDTO);

        return $this->makeRequestsBasedOnItemsAndSendees($requestDTO);
    }

    private function checkReceiversAccountType($requestDTO)
    {
        $mappedReceiversAccounts = array_map(
            fn ($receiver) => $receiver->account,
            $requestDTO->receivers
        );

        if ($requestDTO->action === 'admission') {
            $this->validateAdmissionReceiversAccount($requestDTO, $mappedReceiversAccounts);
        }
    }

    private function validateAdmissionReceiversAccount($requestDTO, $mappedAccounts)
    {
        $validReceiversAccount = 'school';
        if ($requestDTO->sender->accountType === 'school') {
            $validReceiversAccount = 'learner';
        }

        $hasInvalidAccount = false;
        foreach ($mappedAccounts as $account) {
            if ($account !== $validReceiversAccount) {
                $hasInvalidAccount = true;
            }
        }

        if (! $hasInvalidAccount) {
            return;
        }

        $this->throwRequestException(
            message: "you are trying to send an administration request to an account which is not a {$validReceiversAccount}",
            data: $requestDTO
        );
    }

    private function makeAdmissionRelatedRequests(RequestDTO $requestDTO)
    {
        $this->checkRequestReceivers($requestDTO);

        $this->checkReceiversAccountType($requestDTO);

        $requests = $this->makeRequestBasedOnSendeesOnly($requestDTO);

        $admission = (new AdmissionService)->createAdmission(
            $requestDTO->admissionDTO->withAddedby(
                $requestDTO->sender
            )
        );

        $requests = $this->attachItemToRequestsRequestable($requests, $admission);

        $requests = $this->addAssessmentsToRequestablesOfRequests($requests, $requestDTO);

        return $requests;
    }

    private function addAssessmentsToRequestablesOfRequests($requests, $requestDTO)
    {
        if (! count($requestDTO->items)) {
            return $requests;
        }

        foreach ($requestDTO->items as $itemDTO) {
            $item = $this->getModel($itemDTO->item, $itemDTO->itemId);

            $requests = $this->attachItemToRequestablesOfRequests($requests, $item);
        }

        return $requests;
    }

    private function attachItemToRequestablesOfRequests
    (
        $requests,
        $item
    )
    {
        foreach ($requests as $request) {
            $requestable = $request->requestables()->create();
            $requestable->requestable()->associate($item);
            $requestable->save();

            $request->refresh();
        }

        return $requests;
    }

    private function attachItemToRequestsRequestable
    (
        $requests,
        $item
    )
    {
        foreach ($requests as $request) {
            $request->requestable()->associate($item);
            $request->save();
        }

        return $requests;
    }

    private function makeAdministrationRelatedRequests($requestDTO)
    {
        $this->checkRequestReceivers($requestDTO);

        return $this->makeRequestBasedOnSendeesOnly($requestDTO);
    }

    private function makeRequestsBasedOnItemsOnly($requestDTO)
    {
        $requests = [];

        foreach ($requestDTO->items as $itemDTO) {
            
            $item = $this->getModel($itemDTO->item, $itemDTO->itemId);

            $requests[] = $this->makeRequest(
                $requestDTO->sender,
                $item->ownedby,
                $item
            );
        }

        return $requests;
    }

    private function makeRequestBasedOnSendeesOnly($requestDTO)
    {
        $requestDTO = $this->getSendees($requestDTO);

        $requests = [];

        foreach ($requestDTO->sendees as $sendee) {

            $requests[] = $this->makeRequest(
                $requestDTO->sender,
                $sendee,
            );
        }

        return $requests;
    }

    private function makeRequestsBasedOnItemsAndSendees($requestDTO)
    {
        $requestDTO = $this->getSendees($requestDTO);
        
        $requests = [];

        foreach ($requestDTO->items as $itemDTO) {
            
            $item = $this->getModel($itemDTO->item, $itemDTO->itemId);

            $this->checkUserAccessibility($item, $requestDTO);

            foreach ($requestDTO->sendees as $sendee) {
                
                $requests[] = $this->makeRequest(
                    $requestDTO->sender,
                    $sendee,
                    $item
                );
            }
        }

        return $requests;
    }

    private function checkUserAccessibility($item, $requestDTO)
    {
        $checkableActions = ['collaboration'];

        if (! in_array($requestDTO->action, $checkableActions)) {
            return;
        }

        if (in_array($requestDTO->userId, $item->ownedby->getAuthorizedIds())) {
            return;
        }

        $itemType = class_basename_lower($item);
        $this->throwRequestException(
            message: "you are not authorized to use this {$itemType} with id {$item->id}",
            data: $requestDTO
        );
    }

    public function makeRequest
    (
        $sender, $sendee, $requestable = null
    ): Request
    {
        $request = $sender->requestsSent()->create([
            'state' => 'PENDING',
        ]);

        $this->checkRequest($request);

        $request->requestto()->associate($sendee);
        $request->requestable()->associate($requestable);
        $request->save();

        return $request;
    }

    private function checkRequest($request)
    {
        if (is_not_null($request)) {
            return;
        }

        $this->throwRequestException(
            message: 'failed to crete and send request',
        );
    }

    private function checkRequestItems(RequestDTO $requestDTO)
    {
        if (count($requestDTO->items)) {
            return;
        }

        $this->throwRequestException(
            message: "this type of request requires that you add some items such as courses, classes, etc.",
            data: $requestDTO
        );
    }

    private function checkRequestReceivers(RequestDTO $requestDTO)
    {
        if (count($requestDTO->receivers)) {
            return;
        }

        $this->throwRequestException(
            message: "this type of request requires that you add accounts to which the request will be sent.",
            data: $requestDTO
        );
    }

    private function checkRequestTypeValidity(RequestDTO $requestDTO)
    {
        if (in_array($requestDTO->action, self::VALIDACCOUNTREQUESTACTION)) {
            return;
        }

        $this->throwRequestException(
            message: "this request does not have the valid action",
            data: $requestDTO
        );
    }

    private function getSendees(RequestDTO $requestDTO)
    {
        foreach ($requestDTO->receivers as $receiver) {
            $requestDTO = $requestDTO->withSendee(
                $this->getModel($receiver->account, $receiver->accountId)
            );
        }

        return $requestDTO;
    }

    private function getSender(RequestDTO $requestDTO)
    {
        if (is_not_null($requestDTO->sender)) {
            return $requestDTO;
        }

        $sender = $requestDTO->requester;

        if ($sender->accountType === 'parent') {
            $this->checkWardData($requestDTO);

            $sender = $this->getModel('learner', $requestDTO->wardId);
        }

        return $requestDTO->withSender(
            $sender
        );
    }

    private function checkWardData($requestDTO)
    {
        if (is_not_null($requestDTO->wardId)) {
            return;
        }

        $this->throwRequestException(
            message: "there ward id for this parent was not specified",
            data: $requestDTO
        );
    }

    private function getRequester(RequestDTO $requestDTO)
    {
        if (is_not_null($requestDTO->requester)) {
            return $requestDTO;
        }

        return $requestDTO->withRequester(
            $this->getModel($requestDTO->account, $requestDTO->accountId)
        );
    }

    private function getMyAccountFromRequestUsingUserId($request, $userId)
    {
        if (in_array($userId, $request->requestfrom->getAuthorizedIds())) {
            return $request->requestfrom;
        }

        return $request->requestto;
    }

    private function getOtherAccountFromRequestUsingUserId($request, $userId)
    {
        if (in_array($userId, $request->requestfrom->getAuthorizedIds())) {
            return $request->requestto;
        }

        return $request->requestfrom;
    }

    public function sendMessage(MessageDTO $messageDTO)
    {
        $request = $this->getModel($messageDTO->item, $messageDTO->itemId);

        $messageDTO = $messageDTO->withFromable(
            $this->getMyAccountFromRequestUsingUserId($request, $messageDTO->fromUserId)
        );

        $messageDTO = $messageDTO->withToable(
            $this->getOtherAccountFromRequestUsingUserId($request, $messageDTO->fromUserId)
        );

        $message = (new MessageService())->createMessage($messageDTO);

        $messageDTO = $messageDTO->withMessage($message);

        $messageDTO->method = 'created';
        $this->broadcastMessage($messageDTO);
        
        $this->sendMessageNotification($messageDTO);
        
        return $message;
    }

    public function deleteMessage(MessageDTO $messageDTO)
    {
        $message = $this->getModel('message', $messageDTO->messageId);

        $messageDTO = $messageDTO->withMessage($message);

        $messageDTO = $messageDTO->withMessageable($message->messageable);
        
        (new MessageService)->deleteMessage($messageDTO);

        $messageDTO->method = 'deleted';
        $this->broadcastMessage($messageDTO);
    }

    private function broadcastMessage(MessageDTO $messageDTO)
    {
        $event = $this->getMessageEvent($messageDTO);
            
        broadcast($event)->toOthers();
    }

    private function getMessageEvent(MessageDTO $messageDTO)
    {
        if ($messageDTO->method === 'created') {
            return new NewRequestMessage($messageDTO);
        }

        return new DeleteRequestMessage($messageDTO);
    }

    private function sendMessageNotification($messageDTO)
    {
        Notification::send(
            User::whereIn('id',$messageDTO->toable->getAuthorizedIds())->get(),
            new RequestMessageNotification(
                $this->getRequestNotificationMessage($messageDTO)
            )
        );
    }

    private function getRequestNotificationMessage($messageDTO)
    {
        if ($messageDTO->toable->accountType === 'school') {
            return "please check requests from your Nav. a request has a new message";
        }

        return "please check requests in school's dashboard. a request has a new message";
    }

    public function searchAccounts
    (
        RequestSearchDTO $requestSearchDTO
    )
    {
        
        $query = Profile::query();
        
        if ($requestSearchDTO->type === 'user') {
            return $this->getUsersFromProfileQuery($query, $requestSearchDTO);
        }

        $single = substr($requestSearchDTO->type, 0, -1);
        $this->checkAccountValidity($single, $requestSearchDTO);

        $query->search(
            "%{$requestSearchDTO->search}%",
            "App\\YourEdu\\".capitalize($single),
            '',
            '',
            [],
            $requestSearchDTO->userId,
            $requestSearchDTO->others,
        );

        return $query->get();
    }

    private function checkAccountValidity($type, $requestSearchDTO)
    {
        if (in_array($type, Learner::VALIDACCOUNTTYPE)) {
            return;
        }

        $this->throwRequestException(
            message: "{$requestSearchDTO->type} is not a valid account type",
            data: $requestSearchDTO
        );
    }

    private function getUsersFromProfileQuery($query, $requestSearchDTO)
    {
        return $query->whereHas('user', function($query) use ($requestSearchDTO){
            $query
            ->where('first_name','like',$requestSearchDTO->search)
            ->orWhere('last_name','like',$requestSearchDTO->search)
            ->orWhere('other_names','like',$requestSearchDTO->search);
        })->get()->pluck('user')->unique();
    }

    public function getMessages($requestId)
    {
        $request = $this->getModel('request',$requestId);

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
        $mainAccount = $this->getModel($account,$accountId);

        $requests = new Collection();
        $requests = $requests->merge($mainAccount->requestsSent()
            ->where('requestable_type',null)->get());
        $requests = $requests->merge($mainAccount->requestsReceived()
            ->where('requestable_type',null)->get());

        return $requests->sortByDesc('updated_at');
    }

    public function declineRequest($requestId,$id)
    {
        $mainRequest = $this->getModel('request', $requestId);

        $requestTo = $this->getModel(class_basename_lower($mainRequest->requestto_type),
            $mainRequest->requestto_id);

        if ($requestTo->user_id !== $id || 
            $requestTo->owner_id !== $id) {
            $this->throwRequestException(
                'unsuccessful. request is to no account'
            );
        }
    
        $mainRequest->update([
            'state' => 'DECLINED'
        ]);
    }

    public function searchItems(RequestSearchDTO $requestSearchDTO)
    {
        $query = $this->getQueryFromDTO($requestSearchDTO);

        if ($requestSearchDTO->others && $requestSearchDTO->userId) {
            $query->whereNotOwnedbyBasedOnUserId($requestSearchDTO->userId);
        }

        if ($requestSearchDTO->type === 'courses') {
            $query->whereStandAlone();
        }

        $query->searchItems($requestSearchDTO->search);

        return $query->paginate(10);
    }

    private function getQueryFromDTO(RequestSearchDTO $requestSearchDTO)
    {
        $this->validateType($requestSearchDTO);

        $file = "App\\YourEdu\\";

        if ($requestSearchDTO->type === 'classes') {
            $file .= 'ClassModel';
        }
        
        if ($requestSearchDTO->type !== 'classes') {
            $file .= capitalize(substr($requestSearchDTO->type, 0, -1));
        }

        return $file::query();
    }

    private function throwRequestException($message, $data = null)
    {
        throw new RequestException(
            message: $message,
            data: $data
        );
    }

    private function validateType(RequestSearchDTO $requestSearchDTO)
    {
        if (is_null($requestSearchDTO->type) || !strlen($requestSearchDTO->type)) {
            $this->throwRequestException(
                message: "the field 'type' is required.",
                data: $requestSearchDTO
            );
        }

        if (!in_array(
            $requestSearchDTO->type, 
            [...DashboardItemContract::VALIDITEMTYPEPLURAL, 'assessments']
        )) {
            $this->throwRequestException(
                message: "the type provide is not valid.",
                data: $requestSearchDTO
            );
        }
    }
}
