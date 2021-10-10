<?php

use Core\App;
use Dotenv\Dotenv;

session_start();
Dotenv::createImmutable(BASEDIR)->load();

/*
 * Set Application Mode (Develop | Production)
 */
App::setMode();

/*
 * Set Default Timezone
 */
App::setTimezone();
