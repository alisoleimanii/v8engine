<?php


namespace App\Helper\View\Filed;


use App\Helper\View\Field;

class File extends Field
{
    public static string $view = 'assets.field.file';

    public string $type = 'file';

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->setClasses("form-control file-input");
    }

    public function render(): string
    {
        return view(static::$view, ["field" => $this]);
    }

}