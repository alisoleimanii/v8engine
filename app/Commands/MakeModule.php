<?php


namespace App\Command;


use App\Helper\Command;
use Core\Module;

class MakeModule extends Command
{
    private string $name;
    private string $command;

    public function __construct($command, ...$args)
    {
        $this->command = $command;
        $this->name = ucfirst($args[0]);
    }

    private function dir()
    {
        $this->dir = Module::getModuleDir($this->name);
        throw_if(!mkdir($this->dir, 0775), "Module Directory cant be Created");
    }

    private function router()
    {
        $this->putFile("router.php", $this->stub("module/router", '%name', $this->name));
    }

    private function database()
    {
        mkdir($this->dir . "/Database", 0775);
        mkdir($this->dir . "/Database/Migration", 0775);
    }

    private function http()
    {
        mkdir($this->dir . '/Http');
        mkdir($this->dir . '/Http/Controllers');
        mkdir($this->dir . '/Http/Middlewares');
    }

    private function migration()
    {
        $this->putFile("Database/Migration/" . lcfirst($this->name) . '.php', $this->stub('module/migration', '%name', lcfirst($this->name)));
    }

    private function controller()
    {
        $this->putFile("Http/Controllers/" . $this->name . "Controller" . '.php', $this->stub('module/controller', '%name', $this->name));
    }

    private function middleware()
    {
        $this->putFile("Http/Middlewares/" . $this->name . "Middleware" . '.php', $this->stub('module/middleware', '%name', $this->name));

    }

    private function view()
    {
        mkdir($this->dir . "/View", 0775);
        mkdir($this->dir . "/View/assets", 0775);
    }

    private function config()
    {
        $this->putFile('config.json', $this->stub("module/config", '%name', $this->name));
    }

    private function class()
    {
        $this->putFile("{$this->name}.php", $this->stub("module/class", ['%name', '%table'], [$this->name, lcfirst($this->name)]));
    }

    private function composer()
    {
        $this->putFile("composer.json", $this->stub('module/composer', '%name', $this->name));
        $this->exec('composer install');
    }

    private function model()
    {
        $this->putFile("Database/" . $this->name . ".php", $this->stub("module/model", '%name', $this->name));
    }

    public function run()
    {
        $this->dir();
        $this->config();
        $this->class();
        $this->router();
        $this->database();
        $this->composer();
        $this->model();
        $this->http();
        $this->migration();
        $this->controller();
        $this->middleware();
        $this->view();
    }
}