<?php

namespace App\DTOs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CollaborationDTO
{
    public string | null $collaborationId;
    public string | null $name;
    public string | null $type;
    public string | null $description;
    public int | null $userId;
    public string | null $account;
    public string | null $accountId;
    public string | null $adminId;
    public Model | null $addedby;
    public array $collaborators;
    public array $removedCollaborators;
    public array $editedCollaborators;

    public static function createFromRequest(Request $request)
    {
        $static = new static();

        $static->collaborationId = $request->collaborationId;
        $static->adminId = $request->adminId;
        $static->name = $request->name;
        $static->type = strtoupper($request->type);
        $static->account = $request->account;
        $static->accountId = $request->accountId;
        $static->userId = (int) $request->user()->id;
        $static->description = $request->description;
        $static->collaborators = !is_null(json_decode($request->collaborators)) ? 
            json_decode($request->collaborators) : [];
        $static->removedCollaborators = !is_null(json_decode($request->removedCollaborators)) ? 
            json_decode($request->removedCollaborators) : [];
        $static->editedCollaborators = !is_null(json_decode($request->editedCollaborators)) ? 
            json_decode($request->editedCollaborators) : [];

        return $static;
    }
}