<?php

namespace App\Http\Controllers\API\Payment;

use App\Http\Controllers\API\BaseController;
use App\Models\Meeting;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymobController extends BaseController
{
    /**
     * Get list of available schedules
     */
    public function callback(Request $request)
    {
        return $this->sendResponse($request->all(), 'Paymob Callback Response');
    }

    public function handleTransaction(Request $request)
    {
        $payment_id = $request->get('id'); // should evaluate to APPROVED
        $txn_response_code = $request->get('txn_response_code'); // should evaluate to APPROVED
        $data_message = $request->get('data.message'); // should evaluate to Approved
        $meeting = Meeting::find($request->get('meeting_id'));
        if(!$meeting){
            return $this->sendError('Meeting is not found', []);
        }

        try {

            if($txn_response_code == 'APPROVED' && $data_message == 'Approved'){
                // store transaction 
                $transaction = new Transaction();
                $transaction->user_id = $meeting->user_id;
                $transaction->meeting_id = $meeting->id;
                $transaction->payment_id = $payment_id;
                $transaction->amount = round($request->get('amount_cents') / 100 , 2);
                $transaction->response = $request->all();
                $transaction->save(); 

                // update meeting status 
                $meeting->meeting_status = 'confirmed';
                $meeting->save();

                $result = [
                    'meeting' => $meeting, 
                    'transaction' => [
                        'payment_id' => $transaction->payment_id,
                    ]
                ];
                return $this->sendResponse($result, 'Transaction Stored Successfully');

            }
            return $this->sendError('There is an issue with your payment' , []);
        } catch (\Throwable $th) {
           return $this->sendError($th->getMessage() , [] , 422);
        }
       
    }
}
