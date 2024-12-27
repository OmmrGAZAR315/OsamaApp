<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\UserConsultant;
use Illuminate\Support\Facades\Validator;

class ConsultantController extends BaseController
{
    public function store(Request $request)
    {
        $validator = validator::make($request->all(), [
            'user_id' => 'required',
            'isTicket' => 'required',
        ]);

        if ($validator->fails())
            return $this->sendError('Validation Error.', $validator->errors(), 422);

        if (!$request->get('isTicket')) {
            $subscription = User::where('user_id')->update(['subscription' => 1]);
            return $this->sendResponse([$subscription], 'Subscribed successfully');
        }

        $consultant = UserConsultant::updateOrCreate(
            [
                'transaction_id' => $request->transaction_id
            ],
            [
                'user_id' => $request->user_id,
                'transaction_id' => $request->transaction_id
            ]
        );
        $consultants_count = UserConsultant::where('user_id', $request->user_id)->get()->count();
        return $this->sendResponse([
            'consultant' => $consultant,
            'consultants_count' => $consultants_count
        ], 'consultant added successfully');
    }

    public function delete(Request $request)
    {
        $user = auth('sanctum')->user();
        $consultant = UserConsultant::where('user_id', $user->id)->first();

        if (!$consultant) {
            return $this->sendError('no consultant found with this transaction id');
        }

        $consultant->delete();
        $consultants_count = UserConsultant::where('user_id', $user->id)->get()->count();
        return $this->sendResponse(['consultants_count' => $consultants_count], 'consultant deleted successfully');
    }
}
