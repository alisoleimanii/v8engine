<?php


namespace App\Command;


use App\Interfaces\Commandable;
use Illuminate\Database\Capsule\Manager;

class Migrate implements Commandable
{

    public function __construct($command, ...$args)
    {
    }

    public function run()
    {
        if (!Manager::schema()->hasTable('config'))
            require BASEDIR . "/app/Database/Migration/config.php";
        if (!Manager::schema()->hasTable('meta'))
            require BASEDIR . "/app/Database/Migration/meta.php";
        echo "Migrate Completed." . PHP_EOL;
    }
}