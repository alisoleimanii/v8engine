<?php


namespace App\Interfaces;


interface Commandable
{
    public function __construct($command,...$args);

    public function run();
}