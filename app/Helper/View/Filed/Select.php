<?php


namespace App\Helper\View\Filed;

use App\Helper\View\Field;
use Illuminate\Support\Collection;
use Closure;

class Select extends Field
{
    public $options;
    public static string $view = "assets.field.select";

    /**
     * Select constructor.
     * @param Option[]|callable $options
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    public function render($model, $field, $update = false): string
    {
        $this->options = is_callable($this->options) ? Closure::fromCallable($this->options)->call($model, $field, $update) : $this->options;
        $select = $this;
        return view(static::$view, compact("model", "field", "update", "select"));
    }
}