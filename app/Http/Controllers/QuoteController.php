<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuoteController extends BaseController
{
    public function index(Request $request)
    {
        if ($request->has('search')) {
            $quotes = Quote::where('quote', 'like', '%' . $request->search . '%')->get();
            return $this->sendResponse($quotes, 'Quotes retrieved successfully');
        }
        if ($request->has('from') && $request->has('to')) {
            $quotes = Quote::whereBetween('created_at', [$request->from, $request->to])->get();
            return $this->sendResponse($quotes, 'Quotes retrieved successfully');
        }
        $quotes = Quote::all();
        return $this->sendResponse($quotes, 'Quotes retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required',
            'image' => 'nullable',
        ]);
        if ($validator->fails())
            return $this->sendError('Validation Error.', $validator->errors(), 422);

        if (Quote::where('quote', $request->text)->exists())
            return $this->sendError('Quote already exists', [], 422);

        $quote = Quote::create([
            'quote' => $request->text,
            'image' => $request->image,
        ]);
        return $this->sendResponse($quote, 'Qoute stored successfully', 201);
    }
}
