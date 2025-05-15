<?php

namespace App\Services;

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
            $response = Http::withHeaders([
                'Authorization' => 'ApiKey ' . $this->apiKey,
            ])->get($url);

            if ($response->successful()) {
                return $response->json();
            } else {
                // Handle error response
                return null;
            }
        } catch (\Throwable $th) {
            // Handle exception
            return null;
        }
    }

    public function convert($base_currency, $quote_currency, $amount) {
        $rates = $this->getRates($base_currency, $quote_currency);

        if ($rates) {
            $rate = $rates['rate'];
            return $amount * $rate;
        } else {
            // Handle error
            return null;
        }
    }
}
