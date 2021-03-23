<?php


namespace Core;


use App\Helper\Renderable;
use eftec\bladeone\BladeOne;
use Exception;
use Module\JWT\JWT;

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

    const RESOURCE_PATH = BASEDIR . "/resources";
    const BASE_VIEW_PATH = self::RESOURCE_PATH . "/View";
    const COMPILE_PATH = self::BASE_VIEW_PATH . "/cache";

    private function __construct()
    {
        $this->blade = new BladeOne($this->getViewPaths(), self::COMPILE_PATH, $this->compileMode());
        $this->directives();
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
        return @Container::get($prop) ?? $default;
    }

    public static function setProp($prop, $value, $nullSafe = true)
    {
        if ($nullSafe and is_null($value)) {
            if (Container::get($prop))
                return false;
        }
        Container::add($prop, $value);
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

    private function getViewPaths()
    {
        $paths = $this->getModulesViewPaths();
        $paths[] = self::BASE_VIEW_PATH;
        return array_reverse($paths);
    }

    public function make($view = null, $data = [], $user = true)
    {

        /*
         * Bind User To All Views
         */
        !$user ?: $data[JWT::USER] = JWT::getUser();

        /*
         * Compile View
         */
        try {
            return $this->blade->run($view, $data);
        } catch (Exception $e) {
            return $e;
        }
    }

    public static function abort($code, $msg)
    {
        return false;
    }

    private function directives()
    {
        $url = url();
        $this->blade->directiveRT("assets", function () use ($url) {
            echo $url . "/assets";
        });
        $this->blade->directiveRT("url", function () use ($url) {
            echo $url;
        });
        $this->blade->directiveRT('prop', function ($prop, $default = null) {
            echo @self::getProp($prop, $default);
        });
        $this->blade->directiveRT("render", function ($prop) {
            $content = "";
            $prop = @self::getProp($prop);
            if ($prop instanceof Renderable) {
                $content .= $prop->renderPrefix();
                foreach ($prop->prioritySort() as $item) {
                    if ($prop->checkRoute($item) and $prop->can($item))
                        $content .= $prop->render($item);
                }
                $content .= $prop->renderAppend();
            }
            echo $content;
        });
    }
}