<?php

namespace Lauchoit\LaravelNats\Services;

use Basis\Nats\Client;
use Basis\Nats\Configuration;

class NatsService
{
    public  Configuration $configuration;
    public Client $client;

    public function __construct()
    {
        $this->start();
    }

    private function start()
    {
        $this->configuration = new Configuration(config('nats'));

        $this->configuration->setDelay(0.01, Configuration::DELAY_EXPONENTIAL);
    }

    public function subscribe($queue, $callback)
    {
        $this->client = new Client($this->configuration);

        $this->client->subscribe($queue, $callback);
    }
}
