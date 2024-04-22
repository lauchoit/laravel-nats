<p align="center"><a href="https://www.youtube.com/@lauchoit" target="_blank"><img src="./images/logo.jpg" width="200" alt="LauchoIT Logo"></a></p>

# Laravel NATS Integration

## Description
The laravel-nats package serves as an essential tool for integrating the power of NATS into your Laravel applications, enabling effective real-time communication through event publishing and listening.

With laravel-nats, your Laravel application becomes an agile and powerful platform capable of handling massive amounts of requests without sacrificing speed or efficiency. NATS' robustness combines with Laravel's flexibility, offering a development ecosystem that enhances the scalability and performance of your applications.

This package not only simplifies integration with NATS but also provides an abstraction layer that facilitates event publishing and subscription, allowing you to focus on your application's business logic without worrying about implementation details.

Some key features of laravel-nats include:

- **Real-Time Communication:** NATS provides ultra-fast and efficient communication, allowing your applications to respond in real-time to events and updates.
- **Scalability**: Thanks to NATS' lightweight and distributed architecture, your application can handle a large number of concurrent requests without sacrificing performance.
- **Ease of Use**: With clear and concise syntax, laravel-nats simplifies the process of sending and receiving events, allowing you to focus on your application's logic.
- **Adaptability**: This package seamlessly integrates with Laravel's native features, allowing you to leverage the full potential of the framework while incorporating NATS' power into your applications.
In summary, laravel-nats not only simplifies the integration of NATS into your Laravel applications but also opens up a world of possibilities in terms of real-time communication and efficient request handling. If you're looking to take your application to the next level in terms of performance and scalability, laravel-nats is the tool you need.

## Requirements
- PHP 8.0 or higher.
- Laravel 10.0 or higher.
- An operational and accessible NATS instance.

## Installation
To install the package, use Composer:
```bash
composer require lauchoit/laravel-nats
```
## Publish the configuration file
```bash
php artisan vendor:publish --provider="Lauchoit\\LaravelNats\\NatsServiceProvider"
```
- Once the system is published, a file named `nats.php` will be created within the `routes` folder, where the job queues to be listened to will be declared. 
- Additionally, a configuration file named `nats.php` will be placed inside the `config` folder, where the configuration to access the NATS server will have to be set.
By default, it will be `localhost` and `4222` port. Inside this archive you can modify the configuration to access the NATS server.

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

## Developer
When the `php artisan nats:subscriber` command is executed, the system takes a snapshot and runs it. 
To reflect the changes, you need to shut it down and re-execute the command. 
To avoid this, it is recommended to install the nodemon package with `sudo npm install -g nodemon` 
and then execute: `nodemon --watch app --watch config --watch routes -e php --exec php artisan nats:subscriber`.
instead of just using `php artisan nats:subscriber`,
This will listen for changes in the `app`, `config`, and `routes` folders.

### using with docker (or sail)
- If you are using docker, you can install nodemon in the container :

    Putting the following command `RUN npm install -g nodemon` before the line that exposes port EXPOSE XXXX.

    After the system is up, you can enter it and execute the previously described
    command `nodemon --watch app --watch config --watch routes -e php --exec php artisan nats:subscriber`.

### Up by supervisor
You can also use the supervisor from docker (or native) to keep the system listener running, by placing in the supervisor:
    
```yaml
    [program:nats_subscriber]
    command=/usr/bin/php -d variables_order=EGPCS /var/www/html/artisan nats:subscriber
    directory=/var/www/html
    autostart=true
    autorestart=true
    stderr_logfile=/var/www/html/storage/logs/nats_subscriber.err.log
    stdout_logfile=/var/www/html/storage/logs/nats_subscriber.out.log
    
```
- or your location of system files.


## License
This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
```
