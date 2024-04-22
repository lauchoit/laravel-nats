<p align="center"><a href="https://www.youtube.com/@lauchoit" target="_blank"><img src="./images/logo.jpg" width="200" alt="LauchoIT Logo"></a></p>

# Laravel NATS Integration / Integración de Laravel con NATS

## Description
This package, `lauchoit/laravel-nats`, facilitates integration with NATS, enabling effective real-time communication in Laravel applications through both publishing and listening to events.

## Requirements
- PHP 8.0 or higher / PHP 8.0 o superior.
- Laravel 10.0 or higher / Laravel 10.0 o superior.
- An operational and accessible NATS instance / Una instancia de NATS ejecutándose y accesible.

## Installation
To install the package, use Composer / Para instalar el paquete, utiliza Composer:
```bash
composer require lauchoit/laravel-nats
```
## Publish the configuration file
```bash
php artisan vendor:publish --provider="Lauchoit\\LaravelNats\\NatsServiceProvider"
```
- Once the system is published, a file named `nats.php` will be created within the `routes` folder, where the job queues to be listened to will be declared. 
- Additionally, a configuration file named `nats.php` will be placed inside the `config` folder, where the configuration to access the NATS server will have to be set.
By default, it will be localhost. Inside this archive you can modify the configuration to access the NATS server.

## Startup
To initiate the event listening startup, you need to run the command 
```bash 
php artisan nats:subscriber
```
This command will start the process of listening to events defined within the file `routes/nats.php`.

## Examples
### Listening to a job queue
  - Inside the file routes/nats.php, add the following:
```php
<?php

namespace Lauchoit\LaravelNats\Console\Commands;

use Lauchoit\LaravelNats\Services\Nats;

// Example: Nats::subscribe('login', [AuthController::class, 'login']),
return [ 
    Nats::subscribe('login', [AuthController::class, 'login']),
];

```
- The `subscribe` method receives two parameters: the name of the job queue and Controller and method as an array to execute.

### Publishing a job queue
- To publish any event, you have to use the publish method:
```php
<?php

namespace App\Http\Controllers;

use Lauchoit\LaravelNats\Services\Nats;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        Nats::publish('login', $credentials);
    }
}
```
- The `publish` method receives two parameters: the name of the job queue and the payload to be sent.
