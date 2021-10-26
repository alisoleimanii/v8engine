<?php


namespace Core;


use App\Helper\Renderable;
use Exception;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Filesystem\Filesystem;
use \Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use \Illuminate\View\FileViewFinder;
use \Illuminate\View\Factory;

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
    public BladeCompiler $blade;
    private array $paths = [];

    private function __construct()
    {
        $filesystem = new Filesystem;
        $viewResolver = new EngineResolver;
        $bladeCompiler = $this->blade = new BladeCompiler($filesystem, View::compilePath());
        $viewResolver->register('blade', function () use ($bladeCompiler) {
            return new CompilerEngine($bladeCompiler);
        });
        $this->viewFinder = new FileViewFinder($filesystem, $this->getViewPaths());
        $this->viewFactory = new Factory($viewResolver, $this->viewFinder, app('dispatcher'));

        Blade::swap($bladeCompiler);
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

//    private function compileMode()
//    {
//        return env("DEBUG", false) ? BladeOne::MODE_DEBUG : BladeOne::MODE_AUTO;
//    }

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
        $this->viewFinder->addLocation($path);
    }

    private function getViewPaths(): array
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
            return $this->viewFactory->make($view, $data);
        } catch (Exception $e) {
            return $e;
        }
    }

    private function directives()
    {
        $url = App::router() ? url() : "/";

        Blade::directive("assets", function () use ($url) {
            return $url . "/assets";
        });

        Blade::directive("url", function () use ($url) {
            return $url;
        });
        Blade::directive('prop', function ($prop, $default = null) {
            return @self::getProp($prop, $default);
        });
        Blade::directive("render", function ($prop) {
            $data = explode(',', $prop);
            $prop = @self::getProp($data[0] ?? $prop);
            if ($prop instanceof Renderable) {
                return render($prop, [@$data[1]]);
            }
            return '';
        });
    }
}