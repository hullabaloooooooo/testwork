<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase as FrameworkTestCase;
use Tests\Unit\Client\GuzzleMock;

class TestCase extends FrameworkTestCase
{
    protected Core $app;

    protected function setUp(): void
    {
        $this->app = new Core();

        $handlerStack = HandlerStack::create(new GuzzleMock());
        $this->app->container->set(Client::class, new Client(['handler' => $handlerStack]));

        $this->app->init();

        parent::setUp();
    }
}
