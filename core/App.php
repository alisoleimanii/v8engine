<?php
/**
 * @copyright Aliakbar Soleimani 2020
 */

namespace Core;


use Carbon\Carbon;
use Illuminate\{Database\Capsule\Manager,
    Http\Request,
    Routing\Router,
    Routing\UrlGenerator,
    Support\Traits\Macroable,
    Validation\Factory as Validator
};
use Predis\Client;

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
    public Router       $router;
    public Request      $request;
    public Validator    $validator;
    public string       $locale;
    public ?Manager     $connection;
    public UrlGenerator $url;
    public Client $redis;

    private function __construct()
    {
    }

    public static function setConnection($connection)
    {
       Container::add("db",$connection);
    }

    public static function instance()
    {
        return !isset(self::$instance) ? self::$instance = new self() : self::$instance;
    }

    public static function url($uri = null, $extra = [])
    {
        $app = self::instance();
        if (!isset($app->url))
            $app->url = new UrlGenerator($app->router->getRoutes(), $app->request);
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

    public static function router()
    {
        return self::instance()->router;
    }

    public static function request()
    {
        return self::instance()->request;
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
        Container::add('locale',$locale);
        Carbon::setLocale($locale);
    }

}
