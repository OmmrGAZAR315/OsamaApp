<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Code;
use App\Models\UserConsultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CodeController extends BaseController
{
    public function store(Request $request)
    {
        $validator = validator::make($request->all(), [
            'user_id' => 'nullable',
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $ticketsCounter = 0;
        if (isset($request->user_id)) {
            UserConsultant::create([
                'user_id' => $request->user_id,
                'transaction_id' => 0,
            ]);
            $ticketsCounter = UserConsultant::where('user_id', $request->user_id)->count();
        }

        Code::create([
            'code' => $request->code,
        ]);

        return $this->sendResponse($ticketsCounter, 'Code stored successfully', 201);
    }

}
