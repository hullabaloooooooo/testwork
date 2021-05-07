<?php

namespace Tests\Unit\Client;

use App\Client;
use App\TestCase;

class AuthTest extends TestCase 
{
    /**
     * @test
     */
    public function failedAuthorization()
    {
        /** Client $client */
        $client = $this->app->container->get(Client::class);

        $this->assertFalse($client->auth('login', 'pass'));
    }

    /**
     * @test
     */
    public function successAuthorization()
    {
        /** Client $client */
        $client = $this->app->container->get(Client::class);

        $this->assertTrue($client->auth('test', '12345'));
    }
}