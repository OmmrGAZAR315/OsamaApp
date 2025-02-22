<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return JsonResponse
     */
    public function sendResponse($result, $message, $status = 200): JsonResponse
    {
        $response = [
            'message' => $message,
            'success' => true,
            'data' => $result,
        ];

        return response()->json($response, $status);
    }

    /**
     * return error response.
     *
     * @return JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
