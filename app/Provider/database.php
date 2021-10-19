<?php
/**
 * @copyright Aliakbar Soleimani 2020
 * Database Connection Provider
 */

use Core\App;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Builder;
use Illuminate\Events\Dispatcher;

$container = Container::getInstance();


$connection = new Manager;
$connection->addConnection(config("database"));
$connection->setEventDispatcher(container('dispatcher',new Dispatcher($container)));
$connection->setAsGlobal();
$connection->bootEloquent();
App::setConnection($connection);
if (env("DEBUG", false)) {
    Manager::enableQueryLog();
}
\Illuminate\Support\Facades\DB::swap($connection);
Builder::defaultStringLength(191);