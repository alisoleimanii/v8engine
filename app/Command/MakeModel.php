<?php

namespace App\Command;

use App\Helper\Command;
use Core\Module;
use Illuminate\Support\Str;

class MakeModel extends Command
{
    private $module, $name, $extraArgs;
    private int $argsCount;
    private array $options = [
        "c" => "controller",
        "m" => "migration"
    ];

    public function __construct($command, ...$args)
    {
        $this->name = @$args[0];
        $this->module = @$args[1];
        $this->argsCount = count($args);
        $this->extraArgs = strpos(@$args[2], "-") > -1 ? $args[2] : "";
        $this->dir = Module::getModuleDir($this->module) . "/Database";
    }

    public function run()
    {
        if ($this->argsCount <= 1) {
            $error_hint = "Your command is wrong" . PHP_EOL;
            $error_hint .= "try again with this command" . PHP_EOL;
            $error_hint .= "php v8 make:model {ModelName} {ModuleName}";
            return $error_hint;
        }
        if (!is_dir($this->dir)) {
            echo "The {$this->module} module not exist" . PHP_EOL;
            return "Module Path : " . $this->dir . PHP_EOL;
        }
        if (file_exists($this->dir . "/{$this->name}.php")) {
            return "Model file already exist";
        }
        $this->putFile("{$this->name}.php",
            $this->stub("module/model", ["\%name", "%name"], ["\\" . $this->module, $this->name]));
        $this->runExtraCommand();
        return "Model Created Successfully";
    }

    private function runExtraCommand()
    {
        collect(str_split($this->extraArgs))->map(function ($option) {
            if ($option != "-")
                echo $this->{$this->options[$option]}() . PHP_EOL;
        });
    }

    private function controller()
    {
        return (new MakeController("make:controller", $this->name . "Controller", $this->module))->run();
    }

    private function migration()
    {
        return (new MakeMigration("make:migration", Str::pluralStudly(lcfirst($this->name)), $this->module))->run();
    }
}
