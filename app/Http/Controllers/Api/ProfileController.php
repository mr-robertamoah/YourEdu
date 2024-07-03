<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AudioResource;
use App\Http\Resources\EmailResource;
use App\Http\Resources\ImageResource;
use App\Http\Resources\PhoneNumberResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\SocialMediaResource;
use App\Http\Resources\UserAccountResource;
use App\Http\Resources\VideoResource;
use App\Services\ProfileService;
use App\YourEdu\Email;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use App\YourEdu\PhoneNumber;
use App\YourEdu\Profile;
use App\YourEdu\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function profileMediaChange(Request $request, $media,$mediaId)
    {
        $mainMedia = null;
        $account = getYourEduModel($request->account, $request->accountId);

        if (!$account) {
            return response()->json([
                'message' => 'unsuccessful, your account does not exist.'
            ], 422);
        }
        
        if ($account->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'unsuccessful, you do not own this account.'
            ], 422);
        }

        if ($media === 'images') {
            $mainMedia = $account->profile->images()->where('id',$mediaId)->first();
        } else if ($media === 'videos') {
            $mainMedia = $account->profile->videos()->where('id',$mediaId)->first();
        } else if ($media === 'audios') {
            $mainMedia = $account->profile->audios()->where('id',$mediaId)->first();
        }

        if ($mainMedia) {
            $state = $mainMedia->pivot->state;

            if ($state === 'PUBLIC') {
                $mainMedia->pivot->state = 'PRIVATE';
            } else if ($state === 'PRIVATE') {
                $mainMedia->pivot->state = 'PUBLIC';
            }
            $mainMedia->save();

            if ($media === 'images') {
                return response()->json([
                    'message' => 'successful',
                    'media' => new ImageResource($mainMedia)
                ]);
            } else if ($media === 'videos') {
                return response()->json([
                    'message' => 'successful',
                    'media' => new VideoResource($mainMedia)
                ]);
            } else if ($media === 'audios') {
                return response()->json([
                    'message' => 'successful',
                    'media' => new AudioResource($mainMedia)
                ]);
            }
        } else {
            return response()->json([
                'message' => 'unsuccessful, media not found'
            ], 422);
        }
    }

    public function profileUploadFile(Request $request, $profile)
    {
        $mainProfile = Profile::find($profile);
        $account = getYourEduModel($request->account, $request->accountId);

        if (!$account) {
            return response()->json([
                'message' => 'unsuccessful, your account does not exist.'
            ], 422);
        }

        if ($account->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'unsuccessful, you do not own this account.'
            ], 422);
        }
        $fileDetails = [];
        try {
            if ($mainProfile) {
                if ($mainProfile->user_id === auth()->id()) {
                    if ($request->hasFile('file')) {
                            
                        $inputShow = 'PUBLIC';
                        if ($request->has('show') && $request->show === ''
                            && $request->show === null) {
                            $inputShow = 'PRIVATE';
                        }

                        $fileDetails = getFileDetails($request->file('file'));
                        if (Str::contains($fileDetails['mime'], 'image')) {
                            $image = $account->addedImages()->create($fileDetails);
                            $image->ownedby()->associate($account);
                            $image->save();
        
                            $mainProfile->images()->attach($image->id,[
                                'state' => $inputShow
                            ]);
        
                            return response()->json([
                                'message' => 'successful',
                                'image' => new ImageResource($image),
                            ]);
                        } else if (Str::contains($fileDetails['mime'], 'video')) {
                            $video = $account->addedVideos()->create($fileDetails);
                            $video->ownedby()->associate($account);
                            $video->save();

                            $mainProfile->videos()->attach($video->id,[
                                'state' => $inputShow
                            ]);
        
                            return response()->json([
                                'message' => 'successful',
                                'video' => new VideoResource($video),
                            ]);
                        } else if (Str::contains($fileDetails['mime'], 'video')) {
                            $audio = $account->addedAudios()->create($fileDetails);
                            $audio->ownedby()->associate($account);
                            $audio->save();

                            $mainProfile->audios()->attach($audio->id,[
                                'state' => $inputShow
                            ]);
        
                            return response()->json([
                                'message' => 'successful',
                                'audio' => new AudioResource($audio),
                            ]);
                        }
    
                    } else {
                        return response()->json([
                            'message' => 'unsuccessful, the file does not exist.'
                        ], 422);
                    }
                } else {
                    return response()->json([
                        'message' => 'unsuccessful, you do not own this profile.'
                    ], 422);
                }
            } else {
                return response()->json([
                    'message' => 'unsuccessful, the profile does not exist.'
                ], 422);
            }
        } catch (\Throwable $th) {
            if (Storage::exists(public_path($fileDetails['path']))) {
                Storage::delete(public_path($fileDetails['path']));
            }
            throw $th;
        }
    }

    public function profilePicUpdate(Request $request, $profile)
    {
        $mainProfile = Profile::find($profile);
        $account = getYourEduModel($request->account, $request->accountId);

        if (!$account) {
            return response()->json([
                'message' => 'unsuccessful, your account does not exist.'
            ], 422);
        }

        if ($account->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'unsuccessful, you do not own this account.'
            ], 422);
        }
        $fileDetails = [];
        try {
            if ($mainProfile) {
                if ($mainProfile->user_id === auth()->id()) {
                    if ($request->hasFile('file')) {
                        $fileDetails = getFileDetails($request->file('file'));
                        
                        Image::load(public_path("assets/{$fileDetails['path']}"))
                            ->fit(Manipulations::FIT_CONTAIN, 100, 100)->save();
                        $image = $account->addedImages()->create($fileDetails);
                        $image->ownedby()->associate($account);
                        $image->save();
    
                        $mainProfile->images()->attach($image->id,[
                            'state' => 'PUBLIC',
                            'thumbnail' => 1,
                        ]);
    
                        return response()->json([
                            'message' => 'successful',
                            'image' => new ImageResource($image),
                        ]);
    
                    } else {
                        return response()->json([
                            'message' => 'unsuccessful, the file does not exist.'
                        ], 422);
                    }
                } else {
                    return response()->json([
                        'message' => 'unsuccessful, you do not own this profile.'
                    ], 422);
                }
            } else {
                return response()->json([
                    'message' => 'unsuccessful, the profile does not exist.'
                ], 422);
            }
        } catch (\Throwable $th) {
            if (Storage::exists(public_path($fileDetails['path']))) {
                Storage::delete(public_path($fileDetails['path']));
            }
            throw $th;
        }
        
    }

    public function profileAddInfo(Request $request, $profile)
    {
        // return $profile;
        $mainProfile = Profile::find($profile);
        
        if (Str::contains($mainProfile->profileable_type, 'Group')) {
            return response()->json([
                'message' => 'groups cannot add emails or socails or phone numbers'
            ],422);
        }

        $input = [];

        if ($request->show) {
            $input['show'] = true;
        } else {
            $input['show'] = false;
        }

        DB::beginTransaction();
        try {
            if ($request->has('email')) {
                $request->validate([
                    'email' => 'required|email'
                ]);
                $input['email'] = $request->email;
                $email = $mainProfile->profileable->emails()->create($input);
    
                if ($email) {
                    DB::commit();
                    return response()->json([
                        'message' => 'successful',
                        'email' => new EmailResource($email),
                    ]);
                }
            } else if ($request->has('social')) {
                $request->validate([
                    'url' => 'required|url'
                ]);
                $input['url'] = $request->social;
                $social = $mainProfile->socials()->create($input);
    
                if ($social) {
                    DB::commit();
                    return response()->json([
                        'message' => 'successful',
                        'email' => new SocialMediaResource($social),
                    ]);
                }
            } else if ($request->has('phone')) {
                $request->validate([
                    'phone' => 'required|string'
                ]);
                $input['phone_number'] = $request->phone;
                $phone = $mainProfile->profileable->phoneNumbers()->create($input);
    
                if ($phone) {
                    DB::commit();
                    return response()->json([
                        'message' => 'successful',
                        'email' => new PhoneNumberResource($phone),
                    ]);
                }
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        
    }

    public function profileMarkInfo(Request $request)
    {
        $info = null;
        if ($request->item === 'email') {
            $info = Email::find($request->id);
        } else if ($request->item === 'social') {
            $info = SocialMedia::find($request->id);
        } else if ($request->item === 'phone') {
            $info = PhoneNumber::find($request->id);
        }

        if ($info) {
            if ($info->show) {
                $info->show = false;
            } else {
                $info->show = true;
            }
            $info->save();

            return response()->json([
                'message' => "successful"
            ]);
        } else {
            return response()->json([
                'message' => "unsuccessful. {$request->item} was not found."
            ]);
        }
    }

    public function profileDeleteInfo(Request $request)
    {
        $info = null;
        if ($request->item === 'email') {
            $info = Email::find($request->id);
        } else if ($request->item === 'social') {
            $info = SocialMedia::find($request->id);
        } else if ($request->item === 'phone') {
            $info = PhoneNumber::find($request->id);
        }

        if ($info) {
            
            $info->delete();

            return response()->json([
                'message' => "successful"
            ]);
        } else {
            return response()->json([
                'message' => "unsuccessful. {$request->item} was not found."
            ]);
        }
    }

    public function profileUpdate(Request $request, $data)
    {
        $profile = null;
        $input = [];

        $profile = Profile::find($data);

        try {
            if ($profile) {
                if ($profile->user_id !== auth()->id()) {
                    return response()->json([
                        'message' => "unsuccessful. you do not own this profile"
                    ],422);
                }
                $input['about'] = $request->has('about') && !is_null($request->about) ? 
                    $request->about : null;
                $input['name'] = $request->has('name') && !is_null($request->name) ? 
                    $request->name : null;
                $input['interests'] = $request->has('interests') && !is_null($request->interests) ? 
                    $request->interests : null;
                $input['company'] = $request->has('company') && !is_null($request->company) ? 
                    $request->company : null;
                $input['occupation'] = $request->has('occupation') && !is_null($request->occupation) ? 
                    $request->occupation : null;
                $input['address'] = $request->has('address') && !is_null($request->address) ? 
                    $request->address : null;
                $input['location'] = $request->has('location') && !is_null($request->location) ? 
                    $request->location : null;

                $profile->update($input);

                return response()->json([
                    'message' => "successful",
                    'profile' => $profile
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "unsuccessful"
            ],422);
            // throw $th;
        }
    }

    public function profileGet($account, $accountId)
    {
        $mainAccount = null;

        try {
            $mainAccount = getYourEduModel($account, $accountId);
    
            if ($mainAccount && $mainAccount->profile) {
                return response()->json([
                    'status' => true,
                    'account' => $account,
                    'profile' => new ProfileResource(Profile::find(
                        $mainAccount->profile->id
                    )),
                ]);
            }
    
            return response()->json([
                'status' => false,
                'message' => "profile doesn't exist.",
            ], 422);
        } catch (\Throwable $th) {
            // return response()->json([
            //     'status' => false,
            //     'message' => "something unexpected happened",
            // ], 422);
            throw $th;
        }
    }


    public function profilePrivateMediasGet($requestAccount,$requestAccountId,$media)
    {
        $account = getYourEduModel($requestAccount, $requestAccountId);

        if (!$account) {
            return response()->json([
                'message' => 'unsuccessful, your account does not exist.'
            ], 422);
        }

        if ($account->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'unsuccessful, you do not own this account.'
            ], 422);
        }

        if ($media === 'images') {
            return ImageResource::collection($account->profile->images()
                    ->where('state','PRIVATE')
                    ->where('thumbnail',0)->latest()->paginate(10));
        } else if ($media === 'videos') {
            return VideoResource::collection($account->profile->videos()
                    ->where('state','PRIVATE')->latest()->paginate(10));
        } else if ($media === 'audios') {
            return AudioResource::collection($account->profile->audios()
                    ->where('state','PRIVATE')->latest()->paginate(10));
        } else {
            return response()->json([
                'message' => "unsuccessful, this {$media} does not exist."
            ], 422);
        }
        
    }
    
    public function profileMediasGet($requestAccount,$requestAccountId,$media)
    {
        $account = getYourEduModel($requestAccount,$requestAccountId);
        if (!$account) {
            return response()->json([
                'message' => 'unsuccessful, your account does not exist.'
            ], 422);
        }

        if ($media === 'images') {
            return ImageResource::collection($account->profile->images()
                    ->where('state','PUBLIC')
                    ->where('thumbnail',0)->latest()->paginate(10));
        } else if ($media === 'videos') {
            return VideoResource::collection($account->profile->videos()
                    ->where('state','PUBLIC')->latest()->paginate(10));
        } else if ($media === 'audios') {
            return AudioResource::collection($account->profile->audios()
                    ->where('state','PUBLIC')->latest()->paginate(10));
        } else {
            return response()->json([
                'message' => "unsuccessful, this {$media} does not exist."
            ], 422);
        }
        
    }

    public function profileMediaGet($profile,$requestAccount,$requestAccountId,$media)
    {
        $mainProfile = Profile::find($profile);
        $mainMedia = null;

        $account = getYourEduModel($requestAccount,$requestAccountId);

        if (!$account) {
            return response()->json([
                'message' => 'unsuccessful, your account does not exist.'
            ], 422);
        }

        if ($mainProfile) {
            if ($media === 'images') {
                $mainMedia = $account->profile()->images()->where('state','PUBLIC')
                    ->where('thumbnail',0)->latest()->paginate(10);

                return response()->json([
                    'message' => 'successful',
                    'images' => ImageResource::collection($mainMedia),
                ]);                
            } else if ($media === 'videos') {
                $mainMedia = $account->profile()->videos()->where('state','PUBLIC')
                    ->latest()->paginate(10);

                return response()->json([
                    'message' => 'successful',
                    'videos' => VideoResource::collection($mainMedia),
                ]);
            } else if ($media === 'audios') {
                $mainMedia = $account->profile()->audios()->where('state','PUBLIC')
                    ->latest()->paginate(10);

                return response()->json([
                    'message' => 'successful',
                    'audios' => AudioResource::collection($mainMedia),
                ]);
            } else {
                return response()->json([
                    'message' => "unsuccessful, {$media} is invalid"
                ], 422);
            }
        } else {
            return response()->json([
                'message' => 'unsuccessful, profile does not exist'
            ], 422);
        }
    }
}
