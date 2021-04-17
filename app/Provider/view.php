<?php

use Core\View;
use App\Helper\View\{Content, Footer, Notice, Script, Style};

// define const
define("RENDER_DEFAULT", "default");
define("RENDER_BREAK", "break");


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