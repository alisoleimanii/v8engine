<?php


namespace App\Helper\View\Filed;


class BooleanSelect extends Select
{
    public function __construct()
    {
        return parent::__construct([
            new Option(lang("base.active"), 1),
            new Option(lang("base.di-active"), 0),
        ]);
    }

}