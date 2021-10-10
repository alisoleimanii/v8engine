<?php

namespace App\Helper;

trait HasCustomRelations
{

    protected static array $customRelations = [];


    public static function addCustomRelation($relation, $callable)
    {
        static::$customRelations[$relation] = $callable;
    }

    public function __call($method, $arguments)
    {
        if ($relation = @self::$customRelations[$method]) {
            if (!$this->relationLoaded($method))
                $this->setRelation($method, $relation);
            return \Closure::fromCallable($relation)->call($this, ...$arguments);
        }
        return parent::__call($method, $arguments);
    }


}