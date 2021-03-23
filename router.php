<?php

use Core\App;
use App\Http\Controller\AssetController;

$router = App::router();

$router->get("module/{module}/{asset}", [AssetController::class, "module"])->where("asset", "[a-zA-Z0-9_./-{()}-]+");
