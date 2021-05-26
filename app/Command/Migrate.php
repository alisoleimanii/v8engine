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
            require __DIR__ . "/../Database/Migration/config.php";
        if (!Manager::schema()->hasTable('meta'))
            require __DIR__ . "/../Database/Migration/meta.php";
        echo "Migrate Completed." . PHP_EOL;
    }
}