<?php

use Core\{Module};

/*
 * Set Modules Directory
 */
define("MODULES_DIR", BASEDIR."/".env('MODULE_PATH','modules'));

Module::run();