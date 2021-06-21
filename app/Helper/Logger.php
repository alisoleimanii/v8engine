<?php


namespace App\Helper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as BaseLogger;

class Logger extends BaseLogger
{
    public function initialize($logDir)
    {
        $this->pushHandler(new StreamHandler($logDir . "/" . date("Y-m-d") . ".log", Logger::DEBUG, false))->logger();
//        $this->pushHandler(new FirePHPHandler());
        return $this;

    }

    /**
     * Log All Errors When Debug = 0
     */
    public function logger()
    {
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error["type"] == E_ERROR) {
                if (env("DEBUG") == 0) {
                    $request = container("request");
                    $code = rand(1000000, 9999999);
                    Log::emergency("Error Code: {$code}");
                    Log::error($error['message'], ["url" => $request->fullUrl(), "file" => $error['file'], "line" => $error['line'], "ip" => $request->ip()]);
                    ob_get_clean();
                    abort("خطای داخلی - لطفا با پشتبانی تماس بگیرید", "کد خطا {$code}");
                }
            }
        });
    }
}