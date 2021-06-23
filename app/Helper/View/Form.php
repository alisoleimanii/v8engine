<?php


namespace App\Helper\View;


class Form extends Field
{
    const POST = 'POST', GET = 'GET';

    public static string $view = "assets.field.form";

    /**
     * @var Field[] $fields
     */
    public array $fields = [];

    /**
     * @var mixed $ajax Handle Form With ajax
     */
    public $ajax = true;

    /**
     * Form constructor.
     * @param string $id
     * @param string $action
     * @param string $method
     * @param bool $ajax
     * @param array $attributes
     */
    public function __construct(string $id, string $action, string $method = self::POST, $ajax = true, array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setAttributes(['action' => $action, 'method' => $method, 'id' => $id]);
        $this->ajax = $ajax;
    }

    /**
     * @param Field[] $fields
     * @return $this
     */
    public function make(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param string[] ...$except field ids
     */
    public function renderFields(...$except)
    {
        array_walk($this->fields, function (Field $field) use ($except, &$output) {
            in_array($field->getId(), $except) ?: $output .= $field;
        });
        return $output;
    }

    /**
     * @return Ajax
     */
    public function ajax()
    {
        return $this->ajax ?? new Ajax($this->getId());
    }

    public function render(): string
    {
        return view(self::$view, ["form" => $this]);
    }
}