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

        return $this->sendResponse($consultant, 'consultant added successfully');
    }

    public function delete(Request $request, $transaction_id)
    {
        $consultant = UserConsultant::where('transaction_id', $transaction_id)->first();
        if(!$consultant) {
            return $this->sendError('no consultant found with this transaction id');
        }

        $consultant->delete();
        return $this->sendResponse($consultant,'consultant deleted successfully');
    }
}
