<?php


namespace App\Helper\View\Filed;


use App\Helper\View\Field;

class Label extends Field
{
    public static string $view = "assets.field.label";

    const RTL = "rtl", LTR = "ltr";

    /**
     * @var string $text
     */
    public string $text;

    /**
     * @var ?Field $input
     */
    public ?Field $input;

    /**
     * @var string $direction
     */
    public string $direction = self::RTL;

    public function __construct(string $text, ?Field $input, array $attributes = [])
    {
        parent::__construct($attributes, false);
        $this->text = $text;
        !$input ?: $this->input = $input->setFormGroup(false);
    }

    public function getInput()
    {
        return isset($this->input) ? $this->input : '';
    }

    public function render(): string
    {
        return view(static::$view, ["field" => $this]);
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }
}