<?php


namespace App\Boot;


use App\Helper\Bootable;
use Core\Kernel;

class Console implements Bootable
{
    public function boot()
    {
        Kernel::make($argv[1], array_slice($argv, 2));
    }

    public static function services()
    {
    }
}