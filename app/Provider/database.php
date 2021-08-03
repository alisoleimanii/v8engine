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
$connection = new Manager;
$connection->addConnection(config("database"));
$connection->setEventDispatcher(new Dispatcher(new Container));
$connection->setAsGlobal();
$connection->bootEloquent();
App::setConnection($connection);
if (env("DEBUG", false)) {
    Manager::enableQueryLog();
}

Builder::defaultStringLength(191);