<?php


namespace App\Helper\View\Filed;


class BooleanSelect extends Select
{
    public function __construct($attributes = [], $active = 1)
    {
        return parent::__construct(function () use ($active) {
            if ($active)
                return [
                    new Option(lang("base.active"), 1),
                    new Option(lang("base.di-active"), 0),
                ];
            return [
                new Option(lang("base.di-active"), 0),
                new Option(lang("base.active"), 1),
            ];

        }
            , $attributes);
    }

}