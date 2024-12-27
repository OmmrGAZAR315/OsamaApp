<?php

namespace App\Http\Controllers\API;

use App\Models\Code;
use App\Models\User;
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
    public function updateProfile(UpdateUserProfileRequest $request)
    {
        $user = User::withCount('consultants')->find($request->id);
        if (!$user) {
            return $this->sendError('User is not found', []);
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
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

    public function storeCode(Request $request)
    {
        $validator = validator::make($request->all(), [
            'code' => 'required',
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        if (isset($request->user_id)) {
            UserConsultant::create([
                'user_id' => $request->user_id,
                'transaction_id' => 0,
            ]);
        }

        Code::create([
            'code' => $request->code,
        ]);
        $ticketsCounter = UserConsultant::where('user_id', $request->user_id)->count();

        return $this->sendResponse($ticketsCounter, 'Code stored successfully');
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
