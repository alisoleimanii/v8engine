<?php


namespace App\Command;


use App\Helper\Commandable;

class Born implements Commandable
{
    private array $args;

    public function __construct($command, ...$args)
    {
        $this->args = $args;
    }

    private function getPort()
    {
        $index = array_search("--port", $this->args);
        if ($index !== false) {
            $port = @(int)@$this->args[$index + 1];
            if (!is_int($port))
                unset($port);
        }
        return @$port ? $port : env('PORT', "5000");

    }

    public function run()
    {
        shell_exec("php -S 127.0.0.1:{$this->getPort()} -t " . BASEDIR);
    }
}