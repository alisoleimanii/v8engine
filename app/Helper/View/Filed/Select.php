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
    public function __construct($options, $attributes = [])
    {
        $this->options = $options;
        parent::__construct($attributes);
    }

    public function render(): string
    {
        $this->options = is_callable($this->options) ? Closure::fromCallable($this->options)() : $this->options;
        return view(static::$view, ["field" => $this]);
    }
}