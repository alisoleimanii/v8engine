<?php


namespace Core;

use Predis\Client;

class Cache
{
    public Client $redis;
    private static self $instance;

    private function __construct()
    {
        $this->redis = app('redis');
    }

    public static function instance()
    {
        if (!isset(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    public static function isEnable()
    {
        return env("REDIS", false);
    }

    public function set($key, $value)
    {
        $this->redis->set($key, $value);
        return $this;
    }

    public function get($key, $default = null, $setCache = true)
    {
        $cache = $this->redis->get($key);
        if (!$cache and $default != null and $setCache == true) {
            $this->set($key, $default());
            $cache = $default;
        }
        return $cache;
    }

    public function destroy(...$keys)
    {
        $this->redis->del(...$keys);
    }
}