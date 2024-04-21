<?php

namespace Lauchoit\LaravelNats\Services;

class Nats
{
    public static function subscribe(String $queue, mixed $ControllerMethod): array
    {
        return [$queue, $ControllerMethod];
    }
}
