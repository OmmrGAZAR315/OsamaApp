<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserConsultant;
use BoogieFromZk\AgoraToken\RtcTokenBuilder2;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\RtcTokenRequest;
use App\Models\Meeting;

class UserController extends BaseController
{
    public function update(UpdateUserProfileRequest $request)
    {
        $user = User::withCount('consultants')->find($request->id);
        if (!$user) {
            return $this->sendError('User is not found');
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        if ($request->hasFile('photo'))
            $user->profile_pic_path = $request->file('photo')->store('profile_pics', 'public');

        $user->save();

        return $this->sendResponse($user, 'User profile updated successfully');

    }

    public function delete(Request $request, $transaction_id)
    {
        $consultant = UserConsultant::where('transaction_id', $transaction_id)->first();
        if (!$consultant) {
            return $this->sendError('no consultant found with this transaction id');
        }

        $consultant->delete();
        return $this->sendResponse($consultant, 'consultant deleted successfully');
    }

    public function store(Request $request)
    {
        $validator = validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'name' => 'required',
            'role' => 'required:integer',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($validator->fails())
            return $this->sendError('Validation Error.', $validator->errors(), 422);

        if ($request->user()->is_admin == 0)
            return $this->sendError('You are not authorized to add a user', [], 401);

        if (User::where('email', $request->email)->exists()) {
            return $this->sendError('User already exists', [], 422);
        }

        User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'name' => $request->name,
            'is_admin' => $request->role,
            'profile_pic_path' => $request->file('photo')->store('profile_pics', 'public')
        ]);
        return $this->sendResponse([], 'User register successfully.');
    }

    public function myProfile(Request $request)
    {
        $user = User::withCount('consultants')->find($request->user()->id);
        if (!$user)
            return $this->sendError('User is not found');
        if ($user->profile_pic_path)
            $user->profile_pic_path = asset('storage/' . $user->profile_pic_path);
        return $this->sendResponse($user, 'User profile');
    }

    public function generateRtcToken(RtcTokenRequest $request)
    {
        $appID = $request->get('app_id');
        $appCertificate = $request->get('app_certificate');
        $channelName = $request->get('channel_name');
        $expiresInSeconds = $request->get('token_expire_in_seconds') ?? 60;
        $uid = (string)Str::uuid();
        $role = RtcTokenBuilder2::ROLE_PUBLISHER;

        $token = RtcTokenBuilder2::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $expiresInSeconds);

        if ($token) {

            $meeting = Meeting::find($request->meeting_id);
            if ($meeting) {
                $meeting->rtc_token = $token;
                $meeting->save();
            }
            return $this->sendResponse($token, 'Rtc Token Generated');
        }

        return $this->sendError('We can not generate a token', []);
    }
}
