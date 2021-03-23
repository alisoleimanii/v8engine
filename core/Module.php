<?php


namespace Core;

use Illuminate\Database\Capsule\Manager;

/**
 * Class Module
 * @package Core
 * Modules Core
 */
final class Module
{
    private static array $modules = [];

    public static function getModules()
    {
        return array_merge(self::$modules, config("modules"));
    }

    private function loadModule($module)
    {
        require_once self::getModuleDir($module) . "/" . $module . ".php";
    }

    public static function getModuleDir($module)
    {
        return MODULES_DIR . '/' . $module;
    }

    private function loadRouter($module)
    {
        $routerPath = self::getModuleDir($module) . "/router.php";
        if (file_exists($routerPath))
            require_once $routerPath;
    }

    private function startModule($module)
    {
        return new $module();
    }

    private function loadModules()
    {
        foreach (self::getModules() as $moduleDir => $moduleClass) {
            $this->loadModule($moduleDir);
            $this->loadRouter($moduleDir);
        }
    }

    private function startModules()
    {
        foreach (self::getModules() as $moduleDir => $moduleClass) {
            $module = $this->startModule($moduleClass);
            if ($this->isFirstTimeRun($moduleDir))
                if (method_exists($module, "onActivate"))
                    $module->onActivate();
        }
    }

    private function isFirstTimeRun($moduleDir)
    {
        $moduleConfig = $this->getModuleConfig($moduleDir);
        if (isset($moduleConfig->firstTime) and $moduleConfig->firstTime) {
            $moduleConfig->firstTime = false;
            $this->setModuleConfig($moduleDir, json_encode($moduleConfig));
            return true;
        }
        if (isset($moduleConfig->mainTable) and !Manager::schema()->hasTable($moduleConfig->mainTable) and env("DEBUG", false)) {
            return true;
        }
        return false;
    }

    private function setModuleConfig($module, $config)
    {
        file_put_contents(self::getModuleDir($module) . "/config.json", $config);
    }

    private function getModuleConfig($module)
    {
        return json_decode(file_get_contents(self::getModuleDir($module) . "/config.json"));
    }

    private function initialize()
    {
        $this->loadModules();
        $this->startModules();
    }

    public static function run()
    {
        (new self)->initialize();
    }


}