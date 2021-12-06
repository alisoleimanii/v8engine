<?php

namespace App\Helper;

use Illuminate\Support\Collection;

class Filter
{
    use Singleton;

    private function __construct()
    {
        container('filters', collect());
    }

    public static function add($filter, $var)
    {
        $instance = self::instance();
        return $instance->call($instance->container()->where('filter', $filter)->sortBy('priority'), $var);
    }

    public static function apply($filter, $callback, $priority = 10)
    {
        return self::instance()->container()->add(['filter' => $filter, 'callback' => $callback, 'pririty' => $priority]);
    }

    public function call(Collection $callbacks, $var)
    {
        $var = is_callable($var) ? $var() : $var;
        $callbacks->each(function ($callback) use (&$var) {
            $var = call_user_func($callback['callback'], $var);
        });
        return $var;
    }

    public function container(): Collection
    {
        return app('filters');
    }
}