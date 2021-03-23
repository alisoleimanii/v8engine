<?php

use Core\App;
use Dotenv\Dotenv;

Dotenv::createImmutable(BASEDIR)->load();

/*
 * Set Application Mode (Develop | Production)
 */
App::setMode();

/*
 * Set Default Timezone
 */
App::setTimezone();
