<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\BanException;
use App\YourEdu\Admin;
use App\YourEdu\Ban;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BanService
{
    public function ban(Admin $admin,$authId,$bannable,$issuedfor,$state,$type,$dueDate) : Ban
    {
        $this->checkAdminAuthorization($admin,$authId);

        $ban = $admin->bans()->create([
            'state' => $state ? Str::upper($state) : 'PENDING',
            'type' => Str::upper($type),
            'due_date' => $dueDate ? Carbon::parse($dueDate)->toDateTime() : 
                Carbon::now()->addMonths(3),
        ]);

        $ban->bannable()->associate($bannable);
        $ban->issuedfor()->associate($issuedfor);
        $ban->save();

        return $ban;
    }
    
    public function unban(Admin $admin,$authId,$banId) : Ban
    {
        $this->checkAdminAuthorization($admin,$authId);

        $ban = getYourEduModel('ban',$banId);
        if (is_null($ban)) {
            throw new AccountNotFoundException("ban with id {$banId} not found.");
        }

        $ban->state = 'UNSERVED';
        $ban->save();

        return $ban;
    }

    private function checkAdminAuthorization(Admin $admin,$authId)
    {
        if ($admin->user_id !== (int) $authId) {
            throw new BanException("you are not authorized to perform this action");
        }
        if (!in_array($admin->role,['SUPERADMIN','SUPERVISOR'])) {
            throw new BanException("you are not an authorized administrator to perform this action.");
        }
        if ($admin->state === 'SUSPENDED') {
            throw new BanException("your administrator account has been suspended.");
        }
    }
}