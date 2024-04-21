<?php

namespace Lauchoit\LaravelNats\Services;

class Nats
{
    public static function subscribe(String $queue, array $ControllerMethod): array
    {
        return [$queue, $ControllerMethod];
    }
}
