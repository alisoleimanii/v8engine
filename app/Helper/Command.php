<?php


namespace App\Helper;


use App\Interfaces\Commandable;

/**
 * Class Command
 * @package App\Helper
 */
abstract class Command implements Commandable
{
    protected string $dir;
    const ENGINE_DIR = __DIR__ . "/../..";

    abstract public function __construct($command, ...$args);

    abstract public function run();

    protected function stub($stub, $search = "", $replace = "")
    {
        return str_replace($search, $replace, file_get_contents(self::ENGINE_DIR . "/app/Command/stubs/{$stub}.stub"));
    }

    protected function putFile($file, $data)
    {
        return file_put_contents($this->dir . "/" . $file, $data);
    }

    protected function exec($command)
    {
        return shell_exec("cd " . $this->dir . " && {$command}");
    }
}