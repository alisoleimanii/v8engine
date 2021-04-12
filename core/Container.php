<?php


namespace Core;


class Container
{
    private static array $containers = [];

    public static function add($key, $value)
    {
        self::$containers[$key] = $value;
        return $value;
    }

    public static function get($key)
    {
        return @self::$containers[$key];
    }

    public static function set(array $containers)
    {
        self::$containers = array_merge(self::$containers, $containers);
    }
}