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
        logger(json_encode($request->all()));
    }

}
