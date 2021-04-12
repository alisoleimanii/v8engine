<?php


namespace App\Boot;


use App\Interfaces\Bootable;
use Core\Scheduler as Cronjob;


class Scheduler implements Bootable
{

    public function boot($args = null)
    {
        Cronjob::handle();
    }

    public static function services()
    {
        // TODO: Implement services() method.
    }
}