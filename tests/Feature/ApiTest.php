<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\CurrentcyConverterService;
use Mockery;

class ApiTest extends TestCase
{
    public function test_returns_converted_amount_for_valid_request()
    {
        // Mock the service
        $mock = Mockery::mock(CurrentcyConverterService::class);
        $mock->shouldReceive('convert')
             ->once()
             ->with('USD', 'EUR', 100)
             ->andReturn('92.50 EUR');

        $this->app->instance(CurrentcyConverterService::class, $mock);

        $response = $this->postJson('/api/convert', [
            'base_currency' => 'USD',
            'quote_currency' => 'EUR',
            'amount' => 100,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'converted_amount' => '92.50 EUR',
                 ]);
    }

    public function test_returns_validation_error_for_invalid_input()
    {
        $response = $this->postJson('/api/convert', [
            'base_currency' => 'US',
            'quote_currency' => '',
            'amount' => -5,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['base_currency', 'quote_currency', 'amount']);
    }

     public function test_returns_available_currencies()
    {
        $mock = Mockery::mock(CurrentcyConverterService::class);
        $mock->shouldReceive('getAvailableCurrencies')
             ->once()
             ->andReturn(['USD', 'EUR', 'GBP']);

        $this->app->instance(CurrentcyConverterService::class, $mock);

        $response = $this->getJson('/api/currencies');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'currencies' => ['USD', 'EUR', 'GBP'],
                 ]);
    }

    public function test_handles_service_exception_on_convert()
    {
        $mock = Mockery::mock(CurrentcyConverterService::class);
        $mock->shouldReceive('convert')
             ->andThrow(new \Exception('Service error'));

        $this->app->instance(CurrentcyConverterService::class, $mock);

        $response = $this->postJson('/api/convert', [
            'base_currency' => 'USD',
            'quote_currency' => 'EUR',
            'amount' => 100,
        ]);

        $response->assertStatus(500)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Something wrong happended.',
                 ]);
    }
}
