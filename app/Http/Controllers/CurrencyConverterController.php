<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use App\Services\CurrentcyConverterService;
use Illuminate\Http\Request;

class CurrencyConverterController extends Controller
{
    //
    public function convert(Request $request, CurrentcyConverterService $converterService) {

        Log::info("request data", ['request' => $request->all()]);

        $request->validate([
            'base_currency' => 'required|string|size:3',
            'quote_currency' => 'required|string|size:3',
            'amount' => 'required|numeric|min:0',
        ]);

        try {

            Log::info(("enter try block"));
            $base_currency = $request->input('base_currency');
            $quote_currency = $request->input('quote_currency');
            $amount = $request->input('amount');

            $converted_amount = $converterService->convert($base_currency, $quote_currency, $amount);

            if ($converted_amount !== null) {
                return response()->json([
                    'success' => true,
                    'converted_amount' => $converted_amount,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Conversion failed.',
                ], 500);
            }

        }
        catch (\Exception $e) {
            Log::error('Error occurred:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false,
                    'message' => 'Something wrong happended.',], 500);
        }
    }

    public function getAvailableCurrencies(CurrentcyConverterService $converterService) {
        try {
            $currencies = $converterService->getAvailableCurrencies();
            return response()->json([
                'success' => true,
                'currencies' => $currencies,
            ]);
        } catch (\Exception $e) {
            Log::error('Error occurred:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false,
                    'message' => 'Something wrong happended.',], 500);
        }
    }
}
