<?php

use Core\App;
use Predis\Client as Redis;

if (env("REDIS", 0)) {
    $redis = new Redis([
        'host' => env("REDIS_HOST", "127.0.0.1"),
        'port' => env("REDIS_PORT", 6379),
    ]);
    if (env("REDIS_AUTH", 0))
        $redis->auth(env("REDIS_PASSWORD"));
    App::instance()->redis = $redis;
    //handle redis prefix
}