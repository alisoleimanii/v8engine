<?php


namespace App\Helper;

use Closure;

trait Otp
{

    public function otp($phone, $template, $token, $token2 = "", $token3 = "")
    {
        return container('otp')($phone, $template, $token, $token2, $token3);
    }

    public static function setSmsCallable(Closure $closure)
    {
        container('otp', $closure);
    }
}