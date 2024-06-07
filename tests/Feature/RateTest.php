<?php

namespace Tests\Feature;

use Tests\TestCase;

class RateTest extends TestCase
{
    public function test_the_exchange_rate_returns_number(): void
    {
        $response = $this->get('/api/rate');
        $response->assertStatus(200);
        $this->assertIsFloat($response->getOriginalContent());
    }

    public function test_the_exchange_rate_without_proper_third_party_api_auth_key_returns_bad_request(): void
    {
        config(['app.exchangeServiceApiKey' => 'NOT_REAL_API_KEY']);
        $response = $this->get('/api/rate');
        $response->assertStatus(400);
    }
}
