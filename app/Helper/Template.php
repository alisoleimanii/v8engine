<?php


namespace App\Helper;


use App\Helper\View\Script;
use App\Helper\View\Style;
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

    protected function assets()
    {
        $i = 0;
        collect($this->styles)->each(function ($src, $name) use (&$i) {
            Style::enqueue($name, $src, static::getTemplateTitle(), null, null, $i, $name);
            $i++;
        });
        $i = 0;
        collect($this->scripts)->each(function ($src, $name) use (&$i) {
            Script::enqueue($name, $src, static::getTemplateTitle(), null, null, $i,$name);
            $i++;

        });
    }
}