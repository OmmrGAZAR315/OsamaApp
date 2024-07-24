<?php

namespace App\Http\Controllers\API\Payment;

use App\Http\Controllers\API\BaseController;
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

    public function handleTransaction(Request $request){
        logger(json_encode($request->all()));
        return $this->sendResponse($request->all(), 'paymob/payment/transaction');
    }
}
