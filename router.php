<?php
/**
 * @var \Illuminate\Routing\Router $router
 */
use App\Http\Controller\AssetController;

$router->get("module/{module}/{asset}", [AssetController::class, "module"])->where("asset", "[a-zA-Z0-9_./-{()}-]+");
