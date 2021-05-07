<?php

namespace Tests\Unit\Client;

use App\Client;
use App\TestCase;
use Exception;

class UserDataTest extends TestCase 
{
    /**
     * @test
     */
    public function unauthorizedClient()
    {
        /** Client $client */
        $client = $this->app->container->get(Client::class);

        $this->expectException(Exception::class);

        $client->getUser('another');
    }

    /**
     * @test
     */
    public function successResponse()
    {
        /** Client $client */
        $client = $this->app->container->get(Client::class);
        $client->auth('test', '12345');

        $response = $client->getUser('ivanov');

        $this->assertTrue($response->status == Client::RESPONSE_STATUS_OK);
    }

    /**
     * @test
     */
    public function failedResponse()
    {
        /** Client $client */
        $client = $this->app->container->get(Client::class);
        $client->auth('test', '12345');

        $response = $client->getUser('another');

        $this->assertFalse($response->status == Client::RESPONSE_STATUS_OK);
    }
}