<?php


namespace App\Helper\View\Filed;


class BooleanSelect extends Select
{
    public function __construct()
    {
        return parent::__construct([
            new Option(__("base.active"), 1),
            new Option(__("base.di-active"), 0),
        ]);
    }

}