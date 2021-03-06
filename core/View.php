<?php


namespace Core;


use App\Helper\Renderable;
use eftec\bladeone\BladeOne;
use Exception;

/**
 * Class View
 * @package Core
 */
class View
{
    /**
     * @var View
     */
    private static self $instance;
    public BladeOne $blade;
    private array $paths = [];

    private function __construct()
    {
        $this->blade = new BladeOne($this->getViewPaths(),
            self::compilePath()
            , $this->compileMode());
        $this->directives();
    }

    public static function resourcePath()
    {
        return BASEDIR . "/" . env("RESOURCE_PATH", "resources");
    }

    public static function baseViewsPath()
    {
        return self::resourcePath() . "/View";
    }

    public static function compilePath()
    {
        return self::baseViewsPath() . "/cache";
    }

    private function compileMode()
    {
        return env("DEBUG", false) ? BladeOne::MODE_DEBUG : BladeOne::MODE_AUTO;
    }

    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone()
    {
    }

    public static function getProp($prop, $default = null)
    {
        return @container($prop) ?? $default;
    }

    public static function setProp($prop, $value, $nullSafe = true)
    {
        if ($nullSafe and is_null($value)) {
            if (container($prop))
                return false;
        }
        container($prop, $value);
        return true;
    }

    public static function setProps(array $props)
    {
        Container::set($props);
    }

    private function getModulesViewPaths()
    {
        $paths = [];
        foreach (Module::getModules() as $module => $moduleClass) {
            $paths[] = Module::getModuleDir($module) . "/View";
        }
        return $paths;
    }

    public function addPath($path)
    {
        $this->blade->setPath($this->paths = [$path, ...$this->paths], self::compilePath());
    }

    private function getViewPaths()
    {
        return $this->paths = array_reverse(
            array_merge($this->getModulesViewPaths(), [self::baseViewsPath()])
        );
    }

    public function make($view = null, $data = [])
    {
        /*
         * Compile View
         */
        try {
            return $this->blade->run($view, $data);
        } catch (Exception $e) {
            return $e;
        }
    }

    private function directives()
    {
        $url = App::router() ? url() : "/";
        $this->blade->directiveRT("assets", function () use ($url) {
            echo $url . "/assets";
        });
        $this->blade->directiveRT("url", function () use ($url) {
            echo $url;
        });
        $this->blade->directiveRT('prop', function ($prop, $default = null) {
            echo @self::getProp($prop, $default);
        });
        $this->blade->directiveRT("render", function ($prop, ...$params) {
            $prop = @self::getProp($prop);
            if ($prop instanceof Renderable) {
                echo render($prop, $params);
            }
        });
    }
}