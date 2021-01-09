<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markNotifications(Request $request)
    {
        try {
            $notifications = [];
            if ($request->others) {
                $notifications = auth()->user()->unreadNotifications()
                    ->where(function($query){
                        $query
                        ->where('type','App\Notifications\DiscussionInvitationNotification')
                        ->orWhere('type','App\Notifications\DiscussionInvitationResponseNotification')
                        ->orWhere('type','App\Notifications\RemoveDiscussionParticipantNotification')
                        ->orWhere('type','App\Notifications\UpdateParticipantStateNotification')
                        ->orWhere('type','App\Notifications\AdminResponseNotification')
                        ->orWhere('type','App\Notifications\FacilitatorResponseNotification')
                        ->orWhere('type','App\Notifications\SchoolResponseNotification')
                        ->orWhere('type','App\Notifications\RequestMessageNotification')
                        ->orWhere('type','App\Notifications\BanNotification');
                    })
                    ->get();
            } else {
                $notifications = auth()->user()->unreadNotifications()
                    ->where('type','App\Notifications\DiscussionRequestNotification')
                    ->orWhere('type','App\Notifications\FollowRequest')
                    ->orWhere('type','App\Notifications\NewDiscussionMessageNotification')
                    ->get();
            }
            foreach ($notifications as $notificaiton) {
                $notificaiton->markAsRead();
            }
            return response()->json([
                'message' => 'successful'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getNotifications()
    {
        try {
            $notifications = auth()->user()->notifications()
            ->where(function($query){
                $query
                ->where('type','App\Notifications\DiscussionInvitationNotification')
                ->orWhere('type','App\Notifications\DiscussionInvitationResponseNotification')
                ->orWhere('type','App\Notifications\RemoveDiscussionParticipantNotification')
                ->orWhere('type','App\Notifications\UpdateParticipantStateNotification')
                ->orWhere('type','App\Notifications\RequestMessageNotification')
                ->orWhere('type','App\Notifications\AdminResponseNotification')
                ->orWhere('type','App\Notifications\FacilitatorResponseNotification')
                ->orWhere('type','App\Notifications\SchoolResponseNotification')
                ->orWhere('type','App\Notifications\BanNotification');
            })
            ->latest()->get();
            return NotificationResource::collection(paginate($notifications,10));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
