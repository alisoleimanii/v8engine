<?php
/**
 * @var \Illuminate\Routing\Router $router
 */
use App\Http\Controller\AssetController;

$router->get("assets/{asset}",[AssetController::class,"asset"])->where("asset", "[a-zA-Z0-9_./-{()}-]+");
$router->get("module/{module}/{asset}", [AssetController::class, "module"])->where("asset", "[a-zA-Z0-9_./-{()}-]+");
