<?php


namespace App\Helper\View\Filed;


use App\Helper\View\Field;

class TextArea extends Field
{
    public static string $view = "assets.field.textarea";

    public function __construct()
    {
        $this->attribute('rows', 4);
    }

    public function render(): string
    {
        return view(static::$view, ["field" => $this]);
    }
}