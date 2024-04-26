<?php

namespace Lauchoit\LaravelNats\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Lauchoit\LaravelNats\Services\NatsService;
use ReflectionMethod;

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
            $functionName = $route[1][1];
            if (!class_exists($controller)) {
                throw new Exception("The controller '{$controller}' not exists");
            }
            if (!method_exists($controller, $functionName)) {
                throw new Exception("The method '{$functionName}' not exists in controller '{$controller}'");
            }
            $natsService->subscribe($queue, function ($payload) use ($controller, $functionName) {

                $response = null;
                $params = json_decode($payload->headers['params'], true);
                $payload = json_decode($payload->body, true);
                $newPayload = new Request($payload);

                $reflector = new ReflectionMethod($controller, $functionName);
                $paramCount = $reflector->getNumberOfParameters();

                if (count($newPayload->all())) {
                    $response = (new $controller())->$functionName($newPayload);
                }

                if ($paramCount && !count($newPayload->all())) {
                    $response = (new $controller())->$functionName(...$params);
                }
                return $response;
            });
            $natsServices[] = $natsService;
            $this->info("Subscribed to queue: $queue");
        }
        while (true) {
            foreach ($natsServices as $natsService) {
                $natsService->client->process(0.1);
            }
        }
    }
}
