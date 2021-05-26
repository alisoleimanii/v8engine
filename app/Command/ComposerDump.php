<?php

namespace App\Command;

use App\Helper\Command;
use Core\Module;

class ComposerDump extends Command
{
    public $module;

    public function __construct($command, ...$args)
    {
        if (!isset($args[0]) and empty($args[0])){
            $error_hint = "Your command is wrong" . PHP_EOL;
            $error_hint .= "try again with this command" . PHP_EOL;
            $error_hint .= "php v8 make:controller {ModuleName}";
            die($error_hint);
        }

        $this->module = $args[0];

        $this->dir = Module::getModuleDir($this->module);
    }

    public function run()
    {
        if (!is_dir($this->dir)) {
            echo "The {$this->module} module not exist" . PHP_EOL;
            return "Module Path : " . $this->dir . PHP_EOL;
        }

        exec("composer dumpauto --working-dir=".$this->dir);
    }
}