<?php

namespace App\Http\Controllers\API;

use App\Models\User;
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
        if(!$user) {
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
        if(!$consultant) {
            return $this->sendError('no consultant found with this transaction id');
        }

        $consultant->delete();
        return $this->sendResponse($consultant, 'consultant deleted successfully');
    }

    public function generateRtcToken(RtcTokenRequest $request)
    {
        $appId = $request->get('app_id');
        $appCertificate = $request->get('app_certificate');
        $channelName = $request->get('channel_name');
        $expiresInSeconds = $request->get('token_expire_in_seconds') ?? 60;
        $uid = (string) Str::uuid();
        $role = RtcTokenBuilder2::ROLE_PUBLISHER;

        $channelName = "7d72365eb983485397e3e3f9d460bdda";
        $uid = 2882341273;
        $uidStr = "2882341273";
        $tokenExpirationInSeconds = 3600;
        $privilegeExpirationInSeconds = 3600;
        $joinChannelPrivilegeExpireInSeconds = 3600;
        $pubAudioPrivilegeExpireInSeconds = 3600;
        $pubVideoPrivilegeExpireInSeconds = 3600;
        $pubDataStreamPrivilegeExpireInSeconds = 3600;

        $token1 = RtcTokenBuilder2::buildTokenWithUid($appId, $appCertificate, $channelName, $uid, RtcTokenBuilder2::ROLE_PUBLISHER, $tokenExpirationInSeconds, $privilegeExpirationInSeconds);

        $token2 = RtcTokenBuilder2::buildTokenWithUserAccount($appId, $appCertificate, $channelName, $uidStr, RtcTokenBuilder2::ROLE_PUBLISHER, $tokenExpirationInSeconds, $privilegeExpirationInSeconds);

        $token3 = RtcTokenBuilder2::buildTokenWithUidAndPrivilege($appId, $appCertificate, $channelName, $uid, $tokenExpirationInSeconds, $joinChannelPrivilegeExpireInSeconds, $pubAudioPrivilegeExpireInSeconds, $pubVideoPrivilegeExpireInSeconds, $pubDataStreamPrivilegeExpireInSeconds);

        $token4 = RtcTokenBuilder2::buildTokenWithUserAccountAndPrivilege($appId, $appCertificate, $channelName, $uidStr, $tokenExpirationInSeconds, $joinChannelPrivilegeExpireInSeconds, $pubAudioPrivilegeExpireInSeconds, $pubVideoPrivilegeExpireInSeconds, $pubDataStreamPrivilegeExpireInSeconds);



        $token = RtcTokenBuilder2::buildTokenWithUid($appId, $appCertificate, $channelName, $uid, $role, $expiresInSeconds);

        if($token) {

            $meeting = Meeting::find($request->meeting_id);
            if($meeting) {
                $meeting->rtc_token = $token;
                $meeting->save();
            }
            return $this->sendResponse(
                [
                    'token' => $token,
                    'other_tokens' => [
                       'buildTokenWithUid' =>  $token1,
                       'buildTokenWithUserAccount' => $token2,
                       'buildTokenWithUidAndPrivilege' => $token3,
                       'buildTokenWithUserAccountAndPrivilege' => $token4
                    ]
                ],
                'Rtc Token Generated'
            );
        }

        return $this->sendError('We can not generate a token', []);
    }
}
