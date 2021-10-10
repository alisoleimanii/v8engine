<?php

use Core\{Module};
use App\Helper\Event;

Module::run();

Event::listen("init",app());