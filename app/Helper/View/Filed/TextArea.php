<?php


namespace App\Helper\View\Filed;


use App\Helper\View\Field;

class TextArea extends Field
{
    public static string $view = "assets.field.textarea";

    public function __construct(array $attributes = [], $label = null)
    {
        $this->setClasses("form-control");
        parent::__construct($attributes, $label = null);
        $this->attribute('rows', 4);
    }

    public function render(): string
    {
        return view(static::$view, ["field" => $this]);
    }
}