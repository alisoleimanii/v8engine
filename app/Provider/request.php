<?php
/**
 * @copyright Aliakbar Soleimani 2020
 * Request Router Provider
 */

use Core\App;
use Illuminate\{Container\Container, Events\Dispatcher, Http\Request, Routing\Router};

// Create a service container
$container = new Container;

// Create a request from server variables, and bind it to the container; optional
$request = Request::capture();
$container->instance(Request::class, $request);

// Using Illuminate/Events/Dispatcher here (not required); any implementation of
// Illuminate/Contracts/Event/Dispatcher is acceptable
$events = new Dispatcher($container);

// Create the router instance
$router = new Router($events, $container);

//Load Engine Routes
require_once __DIR__ . "/../../router.php";

// Load the Base routes
if (file_exists(BASEDIR . "/router.php"))
    require_once BASEDIR . '/router.php';


// Create App Instance
$app = App::instance();

// Set Router & Request in App Instance
container("router", $router);
container("request", $request);