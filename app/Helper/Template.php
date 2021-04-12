<?php


namespace App\Helper;


use App\Interfaces\Templatable;

class Template implements Templatable
{
    public function __construct()
    {
    }

    public function blank($content = "", $params = [])
    {
        return view("template.__blank", ["content" => $content, "template" => static::class, "params" => $params]);
    }

    public function header($params = [])
    {
        return "";
    }

    public function footer($params = [])
    {
        return "";
    }

    public static function getTemplateTitle()
    {
        return "default";
    }
}