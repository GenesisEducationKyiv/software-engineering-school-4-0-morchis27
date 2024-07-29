<?php

namespace Tests\Feature;

use Tests\TestCase;

class RateTest extends TestCase
{
    public function testExchangeRateReturnsNumber(): void
    {
        $response = $this->get('/api/rate');
        $response->assertStatus(200);
        $this->assertIsFloat($response->getOriginalContent());
    }

    public function testExchangeRateWithInvalidApiKeyReturnsBadRequest(): void
    {
        config([
            'currencyRepository.repositories.privat.exchangeServiceApiHost' =>
                config('currencyRepository.repositories.privat.exchangeServiceApiHost') . 'abracadabra',
            'currencyRepository.repositories.apiLayer.exchangeServiceApiKey' => 'abracadabra',
            'currencyRepository.repositories.currencyBeacon.exchangeServiceApiKey' => 'abracadabra'
        ]);
        $response = $this->get('/api/rate');
        $response->assertStatus(400);
    }
}
