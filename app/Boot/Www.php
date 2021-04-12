<?php


namespace App\Boot;


use App\Exception\V8Exception;
use App\Interfaces\Bootable;
use Core\App;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
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
        $app = App::instance();
        $router->getRoutes()->refreshNameLookups();
        $app->url = new UrlGenerator($router->getRoutes(), $request);
        new Redirector($app->url);
        try {
            $response = $router->dispatch($request);
        } catch (NotFoundHttpException $exception) {
            throw new V8Exception("route.invalid", "Route Not Found", 404);
        }
        $response->send();
    }
}