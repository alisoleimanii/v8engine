<?php


namespace App\Helper;


trait Singleton
{
    private static self $instance;

    private function __construct()
    {
        parent::__construct();
    }

    private function clone()
    {
    }

    public static function instance()
    {
        if (!isset(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

}