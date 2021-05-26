<?php


namespace App\Helper\View;


abstract class Field
{
    abstract public function render($model, $field, $update = false): string;
}