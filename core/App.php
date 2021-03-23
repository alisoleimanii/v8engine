<?php
/**
 * @copyright Aliakbar Soleimani 2020
 * #TODO Bootable Interface
 */

namespace Core;


use Carbon\Carbon;

use Illuminate\{
    Http\Request,
    Routing\Redirector,
    Routing\Router,
    Routing\UrlGenerator,
    Support\Traits\Macroable,
    Validation\Factory as Validator
};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
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
    public UrlGenerator $url;

    /**
     * Application Boot Types
     * @var array|string[]
     */
    private static array $bootTypes = ["www", "scheduler", "console"];

    private function __construct()
    {
    }

    public static function setConnection($connection)
    {
        Container::add("db", $connection);
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
        Container::add('locale', $locale);
        Carbon::setLocale($locale);
    }

    /**
     * Boot Application
     * @param string $type Apllication BootTypes
     */
    public static function boot(string $type)
    {
        //#Todo Check Paths

        //Check Apllication Base Directory
        defined("BASEDIR") or new Exception('BASEDIR not Defined');

        // Check Type
        in_array($type, self::$bootTypes) or (new Exception("Invalid Boot Type"));

        // Set App Timezone
        date_default_timezone_set("Asia/Tehran");

        // Run Application Providers
        ServiceProviderBootstrap::run();

        App::instance()->{$type}();
    }

    /**
     * Execute Http Request
     */
    private function www()
    {
         $this->invoke(App::request(), App::router());
    }

    /**
     * Excecute Console Command
     */
    private function console()
    {
         Kernel::make($argv[1], array_slice($argv, 2));
    }


    /**
     * Execute Cronjobs
     */
    private function scheduler()
    {
        Scheduler::handle();
    }

    /**
     * Invoke Http Request
     * @param Request $request
     * @param Router $router
     */
    private function invoke(Request $request, Router $router)
    {
        $app = self::instance();
        $router->getRoutes()->refreshNameLookups();
        $app->url = new UrlGenerator($router->getRoutes(), $request);
        new Redirector(
            $app->url
        );
        try {
            $response = $router->dispatch($request);
            $response->send();
        } catch (NotFoundHttpException $exception) {
            http_response_code(404);
            echo 404;
        }

    }
}
