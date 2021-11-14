<?php


namespace App\Boot;


use App\Exception\V8Exception;
use App\Interfaces\Bootable;
use App\Kernel;
use Core\App;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Routing\Router;
use Core\Scheduler;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Www implements Bootable
{

    public function boot($args = null)
    {
        if (@$args['cron'])
            Scheduler::handle($args['cron']);

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
            listen('before.dispatch');

            $response;
            $pipe = new Pipeline(Container::getInstance());
            $res = $pipe->send($request)->through((class_exists(Kernel::class) and property_exists(Kernel::class, 'globals')) ? Kernel::$globals : [])->then(function ($request) use ($router,&$response) {
                return  $response = $router->dispatch($request);
            });
//            $response = Route::dispatch($request);
            listen('dispatch', $response);
        } catch (NotFoundHttpException $exception) {
            throw new V8Exception("route.invalid", "Route Not Found", 404);
        }
        $response->send();
    }
}