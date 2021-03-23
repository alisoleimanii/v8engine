<?php


namespace App\Helper;


interface Commandable
{
    public function __construct($command,...$args);

    public function run();
}