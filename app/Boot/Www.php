<?php


namespace App\Boot;


use App\Exception\V8Exception;
use App\Helper\Event;
use App\Interfaces\Bootable;
use Core\App;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Www implements Bootable
{

    public function boot($args = null)
    {
        $this->invoke(App::request(), App::router());
    }

    public static function services()
    {
    }

    /**
     * Invoke Http Request
     * @param Request $request
     * @param Router $router
     * @throws V8Exception
     */
    private function invoke(Request $request, Router $router)
    {
        $router->getRoutes()->refreshNameLookups();
        try {
            $response = Route::dispatch($request);
            Event::listen('dispatch', $response);
        } catch (NotFoundHttpException $exception) {
            throw new V8Exception("route.invalid", "Route Not Found", 404);
        }
        $response->send();
    }
}