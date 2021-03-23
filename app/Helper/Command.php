<?php


namespace App\Helper;


abstract class Command implements Commandable
{
    protected string $dir;

    abstract public function __construct($command,...$args);

    abstract public function run();

    protected function stub($stub, $search = "", $replace = "")
    {
        return str_replace($search, $replace, file_get_contents(BASEDIR . "/app/Commands/stubs/{$stub}.stub"));
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