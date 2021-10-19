<?php

namespace App\Helper;

use App\Exception\V8Exception;
use Illuminate\Support\Facades\Route;

abstract class Facade
{
    public static function __callStatic(string $name, array $arguments)
    {
        $object = static::getBaseObject();
        if (method_exists($object, $name))
            return $object->{$name}(...$arguments);
        throw new V8Exception('facade.method.not.exists', 'Method Not Exists in' . static::class);
    }

    protected static function getBaseObject()
    {

    }
}