<?php


namespace App\Helper\View\Filed;


use App\Helper\View\Field;

class Input extends Field
{
    public static string $view = "assets.field.input";

    const TYPE = 'type';

    public function __construct(array $attributes = [], $type = 'text')
    {
        $this->setClasses("form-control");
        parent::__construct($attributes);
        $this->attribute(static::TYPE, $type);
    }

    public function render(): string
    {
        return view(self::$view, ["field" => $this]);
    }
}