<?php

namespace App\Services;

use App\DTOs\LinkData;
use App\Exceptions\AccountNotFoundException;
use App\YourEdu\Link;

class LinkService
{
    public static function createLink(LinkData $link) : Link | null
    {
        if (is_null($link->addedby)) {
            return null;
        }
        $newLink = $link->addedby->links()->create([
            'name' => $link->name,
            'description' => $link->name,
            'link' => $link->link,
        ]);

        $newLink->linkable()->associate($link->linkable);
        $newLink->save();

        return $newLink;
    }
    
    public static function editLink(LinkData $link) : Link | null
    {
        $newLink = Link::find($link->id);
        if (is_null($newLink)) {
            throw new AccountNotFoundException("link with id {$link->id} not found.");
        }
        
        $newLink->update([
            'name' => $link->name,
            'description' => $link->name,
            'link' => $link->link,
        ]);

        return $newLink;
    }
    
    public static function deleteLink(LinkData $link) : Link | null
    {
        $newLink = Link::find($link->id);
        if (is_null($newLink)) {
            throw new AccountNotFoundException("link with id {$link->id} not found.");
        }
        
        return $newLink->delete();
    }
}