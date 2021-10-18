<?php

namespace App\Command;

use App\Interfaces\Commandable;
use App\Helper\ConsoleTable;
use Core\App;

class Route implements Commandable
{

    private string $name;

    public function __construct($command, ...$args)
    {
        array_map(function ($index) {
            if (strpos($index, "--name=") > -1) {
                $this->name = explode("=", $index)[1];
            }
        }, $args);
//        $this->name=$args;
    }

    private function table()
    {
        return (new ConsoleTable())
            ->addHeader("Domain")
            ->addHeader("Method")
            ->addHeader("Uri")
            ->addHeader("Name")
            ->addHeader("Action");
    }

    public function run()
    {
        $table = $this->table();
        foreach (App::router()->getRoutes()->getRoutes() as $route) {
            if (isset($this->name)) {
                if (strpos(@$route->action["as"], $this->name) > -1) {
                    $table->addRow([@$route->action["domain"], $this->getMethods($route->methods), @$route->uri, @$route->action["as"], @$route->action["controller"],]);

                }
            } else
                $table->addRow([@$route->action["domain"], $this->getMethods($route->methods), @$route->uri, @$route->action["as"], @$route->action["controller"],]);

        }
        $table->display();
    }

    private function getMethods(array $methods)
    {
        return implode("|", $methods);
    }
}