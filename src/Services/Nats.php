<?php

namespace Lauchoit\LaravelNats\Services;

use Basis\Nats\Message\Payload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Nats
{
    public static function subscribe(string $queue, mixed $ControllerMethod): array
    {
        return [$queue, $ControllerMethod];
    }

    public static function post(string $queue, Request $request)
    {
        $method = 'POST';
        return self::getDataSenderAndMethod($request, $queue, $method);
    }

    public static function get(string $queue, string $request): void
    {
        $method = 'GET';
        self::getDataSenderAndMethod($request, $queue, $method);
    }

    public static function send(string $queue, Request $request, bool $async = false)
    {
        return self::getDataSender($request, $queue, $async);
    }

    /**
     * @param mixed $request
     * @param String $queue
     * @return void
     */
    public static function getDataSenderAndMethod(mixed $request, string $queue, string $method)
    {
        $params = Route::current()->parameters();
        $payload = gettype($request) === 'string' ? [] : [...$request->all()];
        $natsService = new NatsService();
        $newPayload = new Payload(json_encode($payload), ['method' => $method, 'params' => json_encode($params)]);
        return $natsService->dispatch($queue, $newPayload);
    }

    public static function getDataSender(mixed $request, string $queue, $async)
    {
        $params = Route::current()->parameters();
        $payload = gettype($request) === 'string' ? [] : [...$request->all()];
        $natsService = new NatsService();
        $newPayload = new Payload(json_encode($payload), ['params' => json_encode($params)]);
        if (!$async) {
            return $natsService->dispatch($queue, $newPayload);
        } else {
            $natsService->publish($queue, $newPayload);
            return null;
        }
    }
}
