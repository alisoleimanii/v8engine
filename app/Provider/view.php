<?php

use Core\View;
use App\Helper\View\{Content, Footer, Notice, Script, Style};
use Illuminate\Support\Facades\Blade;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Response;

//
////$container = \Illuminate\Container\Container::getInstance();
////\Illuminate\Container\Container::getInstance()->instance(\Illuminate\Contracts\Foundation\Application::class,$container);
//// Dependencies
//
////$viewFactory->setContainer($container);
//
//
//
////\Illuminate\Support\Facades\Facade::setFacadeApplication($container);
////$container->instance(\Illuminate\Contracts\View\Factory::class, $viewFactory);
////$container->alias(
////    \Illuminate\Contracts\View\Factory::class,
////    (new class extends \Illuminate\Support\Facades\View {
////        public static function getFacadeAccessor() { return parent::getFacadeAccessor(); }
////    })::getFacadeAccessor()
////);
////$container->instance(\Illuminate\View\Compilers\BladeCompiler::class, $bladeCompiler);
////$container->alias(
////    \Illuminate\View\Compilers\BladeCompiler::class,
////    (new class extends \Illuminate\Support\Facades\Blade {
////        public static function getFacadeAccessor() { return parent::getFacadeAccessor(); }
////    })::getFacadeAccessor()
////);
//
//// Render template with page.blade.php
//echo $viewFactory->make('abort', [
//    'title' => 'Title',
//    'text' => 'This is my text!',
//])->render();
//
//        die();
View::setProps([
    "title" => env("TITLE", "V8"),
    "theme-color" => "theme-red",
    "logo" => url("assets/images/logo.png"),
    "content" => new Content,
    "footer" => new Footer,
    "notices" => new Notice,
    "styles" => new Style,
    "scripts" => new Script
]);

Response::swap(container('response', new ResponseFactory(View::instance()->viewFactory, app('redirector'))));
Blade::swap(View::instance()->blade);

register_shutdown_function(function () {

    \App\Helper\Event::listen('shutdown', app());
    /**
     * @var $notices Notice
     */
    $notices = prop('notices');


    if (\Core\App::request()->ajax() and $notices != @$_SESSION['notices'])
        $_SESSION['notices'] = $notices;
    else
        $_SESSION['notices'] = [];
});