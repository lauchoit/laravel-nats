<?php

namespace Lauchoit\LaravelNats\Services;

use Basis\Nats\Message\Payload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Nats
{
    public static function subscribe(String $queue, mixed $ControllerMethod): array
    {
        return [$queue, $ControllerMethod];
    }

    public static function post(String $queue, Request $request): void
    {
        $method = 'POST';
        self::getDataSender($request, $queue, $method);
    }

    public static function get(String $queue, string $request): void
    {
        $method = 'GET';
        self::getDataSender($request, $queue, $method);
    }

    /**
     * @param mixed $request
     * @param String $queue
     * @return void
     */
    public static function getDataSender(mixed $request, string $queue, string $method): void
    {
        $params = Route::current()->parameters();
        $payload = gettype($request) === 'string' ? [] : [...$request->all()];
        $natsService = new NatsService();
        $newPayload = new Payload(json_encode($payload), ['method' => $method, 'params' => json_encode($params)]);
        $natsService->publish($queue, $newPayload);
    }
}
