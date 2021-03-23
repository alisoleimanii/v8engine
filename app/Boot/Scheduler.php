<?php


namespace App\Boot;


use App\Helper\Bootable;
use Core\Scheduler as Cronjob;


class Scheduler implements Bootable
{

    public function boot()
    {
        Cronjob::handle();
    }

    public static function services()
    {
        // TODO: Implement services() method.
    }
}