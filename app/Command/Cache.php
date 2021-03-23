<?php


namespace App\Command;

use App\Helper\Commandable;

class Cache implements Commandable
{
    private string $command;
    private array $args;
    private const DIR = BASEDIR . "/resources/View/cache";

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
        system('rm -rf -- ' . escapeshellarg(self::DIR), $situation);
        mkdir(self::DIR);
        chmod(self::DIR, 0777);
        echo "Cache Cleared Successfully" . PHP_EOL;
    }
}