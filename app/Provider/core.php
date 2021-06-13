<?php

use Illuminate\Support\Facades\Log;
use App\Helper\Logger;

// init logger
Log::swap((new Logger("logger"))->initialize(BASEDIR . "/" . env("LOG_PATH", "storage/logs")));
