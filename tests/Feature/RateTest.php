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
        config(['app.exchangeServiceApiKey' => 'NOT_REAL_API_KEY']);
        $response = $this->get('/api/rate');
        $response->assertStatus(400);
    }
}
