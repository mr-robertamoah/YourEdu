<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\DashboardException;
use App\Notifications\BanNotification;
use App\User;
use App\YourEdu\Admin;
use \Debugbar;
use Illuminate\Support\Facades\Notification;

class DashboardService
{
    public function banningAccount($action,$account,$accountId,$adminId,$authId,
        $banId = null,$state = null,$type = null)
    {
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }
        $admin = getAccountObject('admin',$adminId);
        if (is_null($admin)) {
            throw new AccountNotFoundException("administrator not found with id {$adminId}");
        }

        if ($action === 'ban') {
            $ban = (new BanService())->ban($admin,$authId,$mainAccount,null,$state,$type);
        } else if ($action === 'unban') {
            $ban = (new BanService())->unban($admin,$authId,$banId);
        }

        //notify user
        $user = ($account === 'user' ? $mainAccount : $account === 'school') ? 
            User::whereeIn('id',getAdminIds($mainAccount))->get() : $mainAccount->user;

        Notification::sendNow($user,new BanNotification($ban));

        return [
            'ban' => $ban,
            'account' => $mainAccount,
        ];
    }

    public function getUsersOrAdmins($account,$accountId,$userId,$type)
    {
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        if ($mainAccount->user_id !== $userId) {
            throw new DashboardException("you do not own {$account} account with id {$accountId}");
        }

        if ($type === 'admins') {
            return Admin::where('role','SUPERVISORADMIN')->paginate(10);
        } else if ($type === 'users') {
            return User::whereDoesntHave('admins',function($query) use ($userId){
                $query->whereIn('role',['SUPERADMIN','SUPERVISORADMIN']);
            })->paginate(10);
        }     
    }

    public function getAccountDetails(string $account,$accountId,$id,$owner)
    {
        $mainAccount = getAccountObject($account,$accountId);
        if (is_null($mainAccount)) {
            throw new AccountNotFoundException("{$account} not found with id {$accountId}");
        }

        if ($owner) {            
            $this->verifyAccess($mainAccount,$id);
        }

        return $mainAccount;
    }

    private function verifyAccess($account,$id)
    {
        if ($account->user_id) {
            if ($account->user_id != $id) {
                throw new DashboardException("you are not the owner of this account");
            }
        } else {
            if ($account->owner_id != $id && 
                $account->admins()->where('user_id',$id)->count() > 1) {
                    throw new DashboardException("you are not the owner or admin of this account");
            }
        }
    }

    public function getSectionItemData($item,$itemId)
    {
        $mainItem = getAccountObject($item,$itemId);
        if (is_null($mainItem)) {
            throw new AccountNotFoundException("{$item} not found with id {$itemId}");
        }

        return $mainItem;
    }
}