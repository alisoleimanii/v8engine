<?php

use App\Helper\Event;
use Core\App;
use Core\View;
use App\Helper\View\{Content, Footer, Notice, Script, Style};
use Illuminate\Support\Facades\Blade;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Response;

View::setProps([
    "title" => env("TITLE", "V8"),
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
    Event::listen('shutdown', app());
    /**
     * @var $notices Notice
     */
    $notices = prop('notices');
    if (App::request()->ajax() and $notices != @$_SESSION['notices'])
        $_SESSION['notices'] = $notices;
    else
        $_SESSION['notices'] = [];
    
});