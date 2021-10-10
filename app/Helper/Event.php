<?php


namespace App\Helper;

use Closure;
use Core\Container;
use Exception;
use Illuminate\Support\Collection;
use Opis\Closure\ReflectionClosure;

class Event
{
    private static self $instance;

    private function __construct()
    {
        Container::add("events", new Collection);
    }

    private static function instance()
    {
        return !isset(self::$instance) ? self::$instance = new self() : self::$instance;
    }

    public static function listen($event, $newThis = null, ...$params)
    {
        $instance = static::instance();
        return $instance->call($instance->container()->where("event", $event), $newThis, $params);
    }

    public static function bind($event, Closure $closure)
    {
        return static::instance()->container()->add(["event" => $event, "closure" => $closure]);
    }

    private function container(): Collection
    {
        return Container::get("events");
    }

    private function call(Collection $callbacks, $newThis, $params)
    {
        $callbacks->each(function ($callback) use ($newThis, $params) {
            $callback = $callback["closure"];
            try {
                /**
                 * @var $callback Closure
                 */
              $callback->call($newThis, ...$params);
            } catch (Exception $exception) {
                //log Exception
            }
        });
        return true;
    }
}