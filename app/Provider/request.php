<?php
/**
 * @copyright Aliakbar Soleimani 2020
 * Request Router Provider
 */

use Core\App;
use Illuminate\{Container\Container, Events\Dispatcher, Http\Request, Routing\Router, Support\Facades\Route,Routing\UrlGenerator,Routing\Redirector};

$container = Container::getInstance();
// Create a request from server variables, and bind it to the container; optional
$request = Request::capture();

$container->instance(Request::class, $request);

// Using Illuminate/Events/Dispatcher here (not required); any implementation of
// Illuminate/Contracts/Event/Dispatcher is acceptable
$events = app('dispatcher');

// Create the router instance
$router = new Router($events, $container);

container("router", $router);
container("request", $request);

App::instance()->url = $url =  new UrlGenerator($router->getRoutes(),$request);
container('redirector',new Redirector($url));
Route::swap(app('router'));

// Load the Base routes
if (file_exists(BASEDIR . "/router.php"))
    require_once BASEDIR . '/router.php';


// Create App Instance
$app = App::instance();
// Set Router & Request in App Instance
