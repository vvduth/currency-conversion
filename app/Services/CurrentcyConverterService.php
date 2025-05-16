<?php
namespace App\Services;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use NumberFormatter;



class CurrentcyConverterService {

    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('SWOP_API_URL');
        $this->apiKey = env('SWOP_API_KEY');
    }
    private function getRates($base_currency, $quote_currency) {
        try {
            $url = rtrim($this->apiUrl, '/') . '/' . $base_currency . '/' . $quote_currency;
            $auth_header = 'ApiKey ' . $this->apiKey;
            Log::info("Auth header", ['auth_header' => $auth_header]);
            Log::info('API URL:', ['url' => $url]);

            $response = Http::withHeaders([
                'Authorization' => $auth_header,
                'Accept' => 'application/json',
            ])->get($url);

            if ($response->successful()) {
                return $response->json();
            } else {
                // Handle error response
                Log::error('Error fetching rates:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }
        } catch (\Throwable $th) {
            // Handle exception
            Log::error('Error with api', [
                'status'=> $th->getMessage(),
                'body'=> $th->getMessage(),
            ]);
            return null;
        }
    }

    public function convert($base_currency, $quote_currency, $amount) {
        $rates = $this->getRates($base_currency, $quote_currency);
        Log::info('Rates:', ['rates' => $rates]);

        if ($rates && isset($rates['quote'])) {
            $rate = $rates['quote'];
            $result = $amount * $rate;
            $formatted_result = $this->formatResult($result, $quote_currency);
            return $formatted_result;
        } else {
            // Handle error
            Log::error('Conversion failed: Unable to retrieve rates.', [
                'base_currency' => $base_currency,
                'quote_currency' => $quote_currency,
                'amount' => $amount,
            ]);
            throw new \Exception('Currency conversion failed due to unavailable rates.');
        }
    }

    public function formatResult($amount, $currency) {
        $locale = app()->getLocale();
        $fmt = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        return $fmt->formatCurrency($amount, $currency);
    }
}
