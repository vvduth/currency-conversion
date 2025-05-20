<?php

namespace Tests\Unit;

use App\Services\CurrentcyConverterService;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_format_result_returns_formatted_currency(): void
    {
        $number = 123456.789;
        $expectedOutput = '¥123,457';
        $service = new CurrentcyConverterService();

        $result = $service->formatResult($number, 'JPY');
        $this->assertEquals($expectedOutput, $result);

    }

     public function test_convert_returns_formatted_result()
    {
        // Create a mock instance of the service
        $mock = $this->getMockBuilder(CurrentcyConverterService::class)
                     ->onlyMethods(['getRates', 'formatResult'])
                     ->getMock();

        $mock->expects($this->once())
             ->method('getRates')
             ->with('USD', 'EUR')
             ->willReturn(['quote' => 0.85]);

        $mock->expects($this->once())
             ->method('formatResult')
             ->with(85.0, 'EUR')
             ->willReturn('€85.00');

        $result = $mock->convert('USD', 'EUR', 100);

        $this->assertEquals('€85.00', $result);
    }

    public function test_convert_throws_exception_when_rates_unavailable()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Currency conversion failed due to unavailable rates.');

        $mock = $this->getMockBuilder(CurrentcyConverterService::class)
                     ->onlyMethods(['getRates'])
                     ->getMock();

        $mock->expects($this->once())
             ->method('getRates')
             ->with('USD', 'EUR')
             ->willReturn(null);

        $mock->convert('USD', 'EUR', 100);
    }
}
