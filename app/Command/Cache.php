<?php


namespace App\Command;

use App\Interfaces\Commandable;
use Core\View;

class Cache implements Commandable
{
    private string $command;
    private array $args;

    public function __construct($command, ...$args)
    {
        $this->command = explode(":", $command)[1];
        $this->args = $args;
    }

    public function run()
    {
        return $this->{$this->command}();
    }

    private function clear()
    {
        $dir = View::compilePath();
        system('rm -rf -- ' . escapeshellarg($dir), $situation);
        mkdir($dir);
        chmod($dir, 0777);
        echo "Cache Cleared Successfully" . PHP_EOL;
    }
}