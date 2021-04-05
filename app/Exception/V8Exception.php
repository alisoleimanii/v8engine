<?php


namespace App\Exception;

use Throwable;
use Exception;

class V8Exception extends Exception implements Throwable
{
    private static array $exception = [];

    public function __construct($name, $message = "", $code = 0, Throwable $previous = null)
    {
        if (@self::$exception[$name])
            return call_user_func(self::$exception[$name], $this);
        return parent::__construct($message, $code, $previous);
    }

    public static function handle($exception, $callback)
    {
        self::$exception[$exception] = $callback;
    }
}