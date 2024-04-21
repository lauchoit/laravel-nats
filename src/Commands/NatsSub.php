<?php

namespace Lauchoit\LaravelNats\Commands;

use Lauchoit\LaravelNats\Services\NatsService;
use Illuminate\Console\Command;

class NatsSub extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nats:subscriber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'active all listeners for nats queue';

    public function handle()
    {
        $routes = include base_path('routes/nats.php');
        $natsServices = [];
        foreach ($routes as $route) {
            $natsService = new NatsService();
            $queue = $route[0];
            if (is_callable($route[1])) {
                $natsService->subscribe($queue, $route[1]);
                $natsServices[] = $natsService;
                $this->info("Subscribed to queue: $queue");
                continue;
            }

            $controller = $route[1][0];
            $method = $route[1][1];
            $natsService->subscribe($queue, function ($payload) use ($controller, $method) {
                (new $controller())->$method($payload);
            });
            $natsServices[] = $natsService;
            $this->info("Subscribed to queue: $queue");
        }
        while (true) {
            foreach ($natsServices as $natsService) {
                $natsService->client->process();
            }
        }
    }
}
