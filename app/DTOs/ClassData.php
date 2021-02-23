<?php 

namespace App\DTOs;

use App\Contracts\ItemDataContract;
use Illuminate\Http\Request;

class ClassData implements ItemDataContract
{
    public string | null $name;
    public bool | null $facilitate;
    public array | null $items;
    public array | null $removedItems;
    public string | null $classId;
    public string | null $gradeId;
    public string | null $removedGradeId;
    public int | null $maxLearners;
    public array | null $academicYears;
    public array | null $removedAcademicYears;
    public string | null $structure;
    public object | null $discussionData;
    public array | null $discussionFiles;
    public string | null $action;
    public string | null $owner;
    public string | null $ownerId;
    public string | null $adminId;
    public string | null $account;
    public string | null $accountId;
    public string | null $description;
    public string | null $type;
    public string | null $state;
    public array | null $paymentData;
    public array | null $removedPaymentData;
    public int | null $userId;


    public static function createFromRequest(Request $request)
    {
        $self = new static();

        $self->classId = $request->classId;
        $self->structure = $request->structure;
        $self->gradeId = $request->gradeId;
        $self->removedGradeId = $request->removedGradeId;
        $self->removedAcademicYears = is_null($request->removedAcademicYears) ? [] : 
            json_decode($request->removedAcademicYears);
        $self->academicYears = is_null($request->academicYears) ? [] : 
            json_decode($request->academicYears);
        $self->maxLearners = json_decode($request->maxLearners);
        $self->adminId = $request->adminId;
        $self->action = $request->action;
        $self->name = $request->name;
        $self->owner = $request->owner;
        $self->ownerId = $request->ownerId;
        $self->account = $request->account;
        $self->accountId = $request->accountId;
        $self->userId = (int) $request->user()->id;
        $self->description = $request->description;
        $self->state = $request->state;
        $self->type = $request->type;
        $self->facilitate = json_decode($request->facilitate);
        $self->items = is_null($request->items) ? [] : 
            json_decode($request->items);
        $self->removedItems = is_null($request->removedItems) ? [] : 
            json_decode($request->removedItems);
        $self->removedPaymentData = is_null($request->removedPaymentData) ? [] : 
            json_decode($request->removedPaymentData);
        $self->paymentData = is_null($request->paymentData) ? [] : 
            json_decode($request->paymentData);
        $self->discussionData = json_decode($request->discussionData);
        $self->discussionFiles = !$request->hasFile('discussionFile') ? [] : 
            $request->file('discussionFile');
        
        return $self;
    }
}