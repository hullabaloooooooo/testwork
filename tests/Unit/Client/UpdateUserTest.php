<?php

namespace Tests\Unit\Client;

use Exception;
use App\Client;
use App\DTO\PermissionsDTO;
use App\DTO\UpdateUserDTO;
use App\TestCase;

class UpdateUserTest extends TestCase 
{
    /**
     * @test
     */
    public function unauthorizedClient()
    {
        /** Client $client */
        $client = $this->app->container->get(Client::class);

        $this->expectException(Exception::class);

        $client->updateUser(1, new UpdateUserDTO());
    }

    /**
     * @test
     */
    public function userNotFound()
    {
        /** Client $client */
        $client = $this->app->container->get(Client::class);
        $client->auth('test', '12345');

        $response = $client->updateUser(2, new UpdateUserDTO());

        $this->assertEquals('Not found', $response->status);
    }

    /**
     * @test
     */
    public function userSuccessUpdated()
    {
        /** Client $client */
        $client = $this->app->container->get(Client::class);
        $client->auth('test', '12345');

        $permissions = new PermissionsDTO();
        $permissions->add(1, 'comment');

        $data = new UpdateUserDTO();
        $data->active = '1';
        $data->blocked = true;
        $data->name = 'Petr Petrovich';
        $data->permissions = $permissions;

        $response = $client->updateUser(1, $data);

        $this->assertEquals('OK', $response->status);
    }
}