<?php
/**
 * @copyright Aliakbar Soleimani 2020
 */

namespace Core;

use App\Exception\V8Exception;
use App\Interfaces\Bootable;
use Carbon\Carbon;
use Illuminate\{
    Http\Request,
    Routing\Router,
    Routing\UrlGenerator,
    Support\Traits\Macroable,
    Validation\Factory as Validator
};
use Exception;
use Throwable;


/**
 * Class App
 * @package Core
 * Project Base Core
 */
final class App
{
    use Macroable;

    /**
     * @var App $instance Main Application Instance
     */
    private static self $instance;
    public Validator $validator;
    public UrlGenerator $url;

    private function __construct()
    {
    }

    public static function setConnection($connection)
    {
        container("db", $connection);
    }

    public static function instance()
    {
        return !isset(self::$instance) ? self::$instance = new self() : self::$instance;
    }

    public static function url($uri = null, $extra = [])
    {
        $app = self::instance();
        if (!isset($app->url))
            $app->url = new UrlGenerator(self::router()->getRoutes(), self::request());
        return $app->url->to($uri, $extra, self::urlProtocol());
    }

    public static function urlProtocol()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    }

    public static function setMode($debug = null)
    {
        $debug = is_null($debug) ? env("DEBUG", false) : $debug;
        if ($debug) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            error_reporting(0);
        }
    }

    public static function setTimezone()
    {
        define("TIMEZONE", env("TIMEZONE", "Asia/Tehran"));
    }

    public static function router(): ?Router
    {
        return app('router');
    }

    /**
     * @return Request
     */
    public static function request()
    {
        return app('request');
    }

    public static function validator()
    {
        return self::instance()->validator;
    }

    public static function getLocale()
    {
        return app("locale");
    }

    public static function setLocale($locale)
    {
        container('locale', $locale);
    }

    /**
     * Boot Application
     * @param string $bootable Application Boot Type
     * @param mixed|null $args
     * @throws Throwable
     */
    public static function boot(string $bootable, $args = null)
    {
        //Check Application Base Directory
        defined("BASEDIR") or new Exception('BASEDIR not Defined');


        //Create Bootable
        $boot = new $bootable($args);

        //Check Bootable
        throw_if(!$boot instanceof Bootable, new V8Exception("bootable.invalid", "{$bootable} Must be Instance of " . Bootable::class));

        // Set App Timezone
        date_default_timezone_set("Asia/Tehran");


        // Run Application Provider
        ServiceProviderBootstrap::run($boot::services());

        // Boot Application
        $boot->boot($args);
    }
}
