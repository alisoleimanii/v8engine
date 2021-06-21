<?php

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Queue\Capsule\Manager as Queue;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Facades\Log;
use App\Helper\Logger;

// init logger
Log::swap((new Logger("logger"))->initialize(BASEDIR . "/" . env("LOG_PATH", "storage/logs")));

////init queue
//$container = Container::getInstance();
//
//(new EventServiceProvider($container))->register();
//
//$container->instance(Dispatcher::class, new Dispatcher($container));
//
//$container->bind('redis', function () use ($container) {
//    return new RedisManager($container, 'predis', [
//        'default' => [
//            'host' => '127.0.0.1',
//            'password' => null,
//            'port' => 6379,
//            'database' => 0,
//        ],
//    ]);
//});
//
////$container->bind('exception.handler', ExceptionHandler::class);
//$queue = new Queue($container);
//
//
