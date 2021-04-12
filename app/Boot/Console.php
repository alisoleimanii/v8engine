<?php


namespace App\Boot;


use App\Interfaces\Bootable;
use Core\Kernel;

class Console implements Bootable
{
    public function boot($args = null)
    {
        Kernel::make($args[1], array_slice($args, 2));
    }

    public static function services()
    {
    }
}