<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\UserConsultant;

class ConsultantController extends BaseController
{
    public function store(Request $request)
    {
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
           'consultant' =>  $consultant,
           'consultants_count' => $consultants_count
        ], 'consultant added successfully');
    }

    public function delete(Request $request)
    {
        $user = auth('sanctum')->user();
        $consultant = UserConsultant::where('user_id', $user->id)->first();

        if(!$consultant) {
            return $this->sendError('no consultant found with this transaction id');
        }

        $consultant->delete();
        $consultants_count = UserConsultant::where('user_id', $user->id)->get()->count();
        return $this->sendResponse(['consultants_count' => $consultants_count], 'consultant deleted successfully');
    }
}
