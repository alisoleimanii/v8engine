<?php


namespace App\Helper;

use Illuminate\Database\Capsule\Manager;
use stdClass;
use Core\Module as ModuleManager;
use Exception;

class Module
{
    public string $module;
    public string $mainClass;
    public stdClass $config;
    public string $path;
    public object $instance;
    public bool $isActivated = false;
    public bool $isUpdated = false;
    public bool $isLoaded = false;

    public function __construct($module, $mainClass)
    {
        $this->module = $module;
        $this->path = ModuleManager::getModuleDir($module);
        $this->config = $this->getConfig();
        $this->mainClass = $mainClass;
    }

    public function load()
    {
        $this->loadMainClass();
        $this->loadRouter();
        $this->isLoaded = true;
    }

    private function loadRouter()
    {
        $routerPath = $this->path . "/router.php";
        if (file_exists($routerPath))
            require_once $routerPath;
    }

    private function loadMainClass()
    {
        require_once $this->path . "/" . $this->module . ".php";
    }

    public function init()
    {
        if ($this->isActivated())
            $this->activate();

        if ($this->isUpdated())
            $this->update();

        $this->start();
    }

    public function activate()
    {
        if (method_exists($this->mainClass, "onActivate")) {
            $this->mainClass::onActivate();
        }
        $this->isActivated = true;
    }

    public function update()
    {
        if (method_exists($this->mainClass, "onUpdate")) {
            $this->mainClass::onUpdate();
        }
        $this->setConfig();

        $this->isUpdated = true;
    }

    private function start()
    {
        $this->instance = new $this->mainClass();
    }

    public function version()
    {
        return $this->config->version;
    }

    private function getCache()
    {
        return @json_decode(file_get_contents(ModuleManager::getCacheDir() . "/" . $this->module . ".json")) ?: $this->setCache();
    }

    public function setCache()
    {
        try {
            $file = fopen(ModuleManager::getCacheDir() . "/" . $this->module . ".json", "w");
            fwrite($file, json_encode($this->config));
        } catch (Exception $exception) {

        } finally {
            !isset($file) or fclose($file);
        }
        return $this->config;
    }

    public function isUpdated()
    {
        return version_compare(@$this->getCache()->version, $this->config->version, "<");
    }

    private function setConfig($config = null, $cache = true)
    {
        file_put_contents($this->path . "/config.json", $config ?? $this->config);
        !$cache or $this->setCache();
    }

    private function isActivated()
    {
        if (isset($this->config->firstTime) and $this->config->firstTime) {
            $this->config->firstTime = false;
            $this->setConfig();
            return true;
        }
        if (isset($this->config->mainTable) and !empty($this->config->mainTable) and !Manager::schema()->hasTable($this->config->mainTable) and env("DEBUG", false)) {
            return true;
        }
        return false;
    }

    public function config()
    {
        return isset($this->config) ? $this->config : $this->config = $this->getConfig();
    }

    private function getConfig()
    {
        return json_decode(file_get_contents($this->path . "/config.json"));
    }
}