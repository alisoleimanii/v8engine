<?php


namespace App\Helper\View\Filed;

class Option
{
    public string $key;
    public string $value;
    public bool $selected;
    public static string $view = "assets.field.option";

    private $defaultValue = null;
    private bool $update = false;

    public function __construct($title, $value, $selected = false)
    {
        $this->key = $title;
        $this->value = $value;
        $this->selected = $selected;
    }

    public static function make($title, $value, $selected = false): Option
    {
        return new Option ($title, $value, $selected);
    }

    public function render($value = null, $update = false)
    {
        $this->defaultValue = $value;
        $this->update = $update;
        $option = $this;
        return view(static::$view, compact("value", "update", "option"));
    }

    public function isSelected(): string
    {
        if ($this->defaultValue == $this->value and $this->update)
            return "selected";
        if ($this->selected and (!$this->update and !$this->defaultValue))
            return "selected";
        return "";
    }
}