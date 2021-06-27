<?php


namespace App\Helper\View;


use App\Helper\View\Filed\Label;
use Illuminate\Support\Traits\Macroable;

abstract class Field
{
    use Macroable;

    /**
     * @var array Html Attributes
     * @example ["rows" => 4]
     */
    public array $attributes = [];

    /**
     * @var ?string Default Value
     */
    public ?string $value;

    /**
     * @var string Grid Column
     * @example col-md-12 (bootstrap)
     */
    public string $grid = 'col-md-12';

    /**
     * @var Label $label Render Input Label?
     */
    public ?Label $label;

    /**
     * Field Title
     * @var string $title
     */
    public string $title;

    /**
     * @var string $type Field Type (text,file,number,...)
     */
    public string $type = 'text';

    /**
     * @var bool $formGroup
     */
    public bool $formGroup = true;

    /**
     * Field constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [], $label = null)
    {
        $this->setAttributes($attributes);
        $this->setLabel($label);
    }

    /**
     * @param Label|null $label
     * @return $this
     */
    public function setLabel($label = null)
    {
        $this->label = $label !== false ? $label : null;
        return $this;
    }

    /**
     * @return bool|string
     */
    public function getLabel()
    {
        return $this->label ?? new Label($this->getTitle(), null, ['for' => $this->getId()]);
    }

    /**
     * Render Field
     * @return string
     */
    abstract public function render(): string;

    /**
     * Set HTML Attributes
     * @param array $attributes
     * @return Field
     */
    public function setAttributes(array $attributes = [])
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    /**
     * Set HTML Attribute
     * @param string $name
     * @param string|null $value
     * @return mixed
     */
    public function attribute(string $name, string $value = null)
    {
        return $value ? $this->attributes[$name] = $value : @$this->attributes[$name];
    }

    /**
     * @param $except
     * @return array
     */
    public function getAttributes(...$except)
    {
        $attrs = [];
        array_walk($this->attributes, function ($value, $key) use (&$attrs, $except) {
            in_array($key, $except) ?: $attrs[$key] = $value;
        });
        return $attrs;
    }

    /**
     * Set ID
     * @param string $id
     * @return Field
     */
    public function setId(string $id)
    {
        $this->attribute('id', $id);
        return $this;
    }

    /**
     * Get ID Attribute
     * @return mixed|string
     */
    public function getId()
    {
        return $this->attribute('id');
    }


    /**
     * @return bool
     */
    public function isFormGroup(): bool
    {
        return $this->formGroup;
    }

    /**
     * @param bool $formGroup
     */
    public function setFormGroup(bool $formGroup)
    {
        $this->formGroup = $formGroup;
        return $this;
    }


    /**
     * Set Css Classes
     * @param $classes string
     * @return Field
     */
    public function setClasses(string $classes)
    {
        $this->attribute('class', $classes);
        return $this;
    }

    /**
     * Set Default Value
     * @param $value string
     * @return Field
     */
    public function setValue(?string $value)
    {
        $this->value = $value;
        return $this;

    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value ?? "";
    }

    /**
     * Render HTML Attributes
     * @param $except array Ignored Attributes To Render
     */
    public function renderAttributes(...$except)
    {
        array_walk($this->attributes, function ($value, $key) use ($except, &$output) {
            $output .= !in_array($key, $except) ? $key . '="' . $value . '" ' : "";
        });
        return $output;
    }

    /**
     * @return string
     */
    public function getGrid(): string
    {
        return $this->grid;
    }

    /**
     * @param string $grid
     * @return Field
     */
    public function setGrid(string $grid)
    {
        $this->grid = $grid;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?? "";
    }

    /**
     * @param string $title
     * @return Field
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }


    /**
     * Render Field
     * @return string
     */
    public function __toString()
    {
        $this->attribute('name') ?? $this->attribute('name', $this->getId());
        return $this->render();
    }
}