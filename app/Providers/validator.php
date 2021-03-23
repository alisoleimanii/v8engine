<?php

use Core\{App, Translation};
use App\Helper\Presence;
use Illuminate\{Container\Container, Validation\Factory};

App::instance()->validator = new Factory(Translation::getTranslator(), new Container);

App::instance()->validator->setPresenceVerifier(new Presence());