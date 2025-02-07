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
        $query = Quote::query();
        if ($request->has('search'))
            $query->where('quote', 'like', '%' . $request->search . '%');

        if ($request->has('from') && $request->has('to'))
            $query->whereBetween('created_at', [$request->from, $request->to]);

        $quotes = $query->get();

        $quotes->each(function ($quote) {
            $quote->file_url = $quote->file_path ? asset('storage/' . $quote->file_path) : null;
        });

        return $this->sendResponse($quotes, 'Quotes retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required',
            'file' => 'nullable',
        ]);
        if ($validator->fails())
            return $this->sendError('Validation Error.', $validator->errors(), 422);

        if (Quote::where('quote', $request->text)->exists())
            return $this->sendError('Quote already exists', [], 422);
        $quote = new Quote();

        $quote->quote = $request->text;
        if ($request->hasFile('file'))
            $quote->file_path = $request->file('file')->store('quotes', 'public');

        $quote->save();
        return $this->sendResponse($quote, 'Qoute stored successfully', 201);
    }
}
