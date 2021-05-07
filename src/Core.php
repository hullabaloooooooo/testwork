<?php

namespace App;

use DI\Container;
use Symfony\Component\Dotenv\Dotenv;

class Core
{
    public Container $container;

    public function __construct() 
    {
        $this->container = new Container();
    }

    public function init(): void
    {
        $this->container->set(Dotenv::class, $this->getDotenv());
        $this->container->set(static::class, $this);
        $this->container->set(Client::class, $this->container->make(Client::class));
    }

    private function getDotenv(): Dotenv
    {
        $env = new Dotenv();
        $env->load(__DIR__ . '/../.env');

        return $env;
    }
}
