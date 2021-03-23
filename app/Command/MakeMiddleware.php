<?php

namespace App\Command;

use App\Helper\Command;
use Core\Module;

class MakeMiddleware extends Command
{
    private $module, $name;
    private int $argsCount;

    public function __construct($command, ...$args)
    {
        $this->name = $args[0] ?? "";
        $this->module = $args[1] ?? "";
        $this->argsCount = count($args);
        $this->dir = Module::getModuleDir($this->module)."/Http/Middlewares";
    }

    public function run()
    {
        if ($this->argsCount <= 1) {
            $error_hint = "Your command is wrong" . PHP_EOL;
            $error_hint .= "try again with this command" . PHP_EOL;
            $error_hint .= "php v8 make:migration {MiddlewareName} {ModuleName}";
            return $error_hint;
        }
        if (!is_dir($this->dir)) {
            echo "The {$this->module} module not exist" . PHP_EOL;
            return "Module Path : " . $this->dir . PHP_EOL;
        }
        if (file_exists($this->dir . "/{$this->name}.php")) {
            return "Middleware file already exist";
        }
        $this->putFile("{$this->name}.php",
            $this->stub("module/middleware", ["%nameMiddleware","%name"], [$this->name,$this->module]));
        return "Middleware Created Successfully";
    }
}