<?php

namespace Core;

use App\Command\{Born, MakeController, MakeMiddleware, MakeMigration, MakeModel, MakeModule, Cache, Migrate, Route};
use Exception;

final class Kernel
{
    private static array $commands = [];
    public string $command;

    public function __construct($command)
    {
        $this->command = $command;
        self::$commands = array_merge(self::$commands, config("commands", true));
    }

    private function __clone()
    {
    }

    /**
     * Add Command To Kernel
     * @param $command
     * @param $class
     * @throws Exception
     */
    public static function add($command, $class)
    {
        self::$commands[$command] = $class;
    }

    public static function make($command, $arguments)
    {
        $handler = (new self($command))->getCommandHandler();
        throw_if(is_null($handler), new Exception("Command Not Found"));
        echo (new $handler($command, ...$arguments))->run();
    }


    public function getCommandHandler($command = null)
    {
        return @self::$commands[$command ?? $this->command];
    }
}