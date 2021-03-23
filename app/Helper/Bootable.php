<?php


namespace App\Helper;


interface Bootable
{
    public function boot($args = null);

    /**
     * List Of Provider
     * null for Default Provider
     * @return null|array
     */
    public static function services();
}