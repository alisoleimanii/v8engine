<?php

use Core\View;
use App\Helper\View\{Content, Footer, Notice, Script, Style};


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

register_shutdown_function(function () {

    /**
     * @var $notices Notice
     */
    $notices = prop('notices')->filter(function ($notice) {
        return is_array($notice);
    });

    if ($notices != @$_SESSION['notices'])
        $_SESSION['notices'] = $notices;
    else
        $_SESSION['notices'] = [];
});