<?php

use App\Command\Born;
use App\Command\Cache;
use App\Command\MakeController;
use App\Command\MakeMiddleware;
use App\Command\MakeMigration;
use App\Command\MakeModel;
use App\Command\MakeModule;
use App\Command\Migrate;
use App\Command\Route;

return [

    /*
     * Create New Module
     */
    "make:module" => MakeModule::class,

    /*
     * Start PHP Development Web Server
     */
    "born" => Born::class,

    /*
     * Clear Blade Views Cache
     */
    "cache:clear" => Cache::class,

    /*
     * Migrate Main Tables
     */
    "migrate" => Migrate::class,

    /*
     * Get Route List
     */
    "route:list" => Route::class,

    /*
     * Make Controller For Modules
     */
    "make:controller" => MakeController::class,

    /*
     * Make Model For Modules
     */
    "make:model" => MakeModel::class,

    /*
     * Make Migration For Modules
     */
    "make:migration" => MakeMigration::class,

    /*
     * Make Middleware For Modules
     */
    "make:middleware" => MakeMiddleware::class
];