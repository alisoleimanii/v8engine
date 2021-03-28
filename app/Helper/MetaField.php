<?php


namespace App\Helper;


use App\Model\Config;
use Core\Model;
use Illuminate\Http\Request;
use Closure;

class MetaField
{
    public string $key;
    public string $name;
    public string $type = "text";
    public array $rules = [];
    public object $options;
    public array $css;
    public bool $config = false;
    public $model;
    public Closure $getValue;
    private $controller;
    public $permission;
    public ?User $user;
    private static array $inputAble = ["text", "date", "datetime-local", "color", "number"];
    /**
     * @var void
     */
    public $column = "col-md-12";
    public $note;

    private function __construct()
    {
    }

    public static function make($key, $name, $rules = [], $type = "text", $css = [], $options = [])
    {
        $field = new self();
        $field->key = $key;
        $field->name = $name;
        $field->type = $type;
        $field->rules = $rules;
        $field->options = (object)$options;
        $field->css = $css;
        return $field;
    }

    public function setConfig()
    {
        $this->config = true;
        return $this;
    }

    public function setColumn($cssClass)
    {
        $this->column = $cssClass;
        return $this;
    }

    public function setController($closure)
    {
        $this->controller = $closure;
    }

    public function callController($model, Request $request, $value)
    {
        if (!$this->can())
            return false;
        if ($this->config)
            $model = Config::get($this->key, true);
        return call_user_func($this->controller, $model, $request, $value, $this);
    }

    public function getCssClasses()
    {
        return count($this->css) > 0 ? implode(" ", $this->css) : "form-control";
    }

    public function getValue()
    {
        if (isset($this->getValue) and $this->getValue)
            return call_user_func($this->getValue, $this->model, $this);
        if ($this->config)
            return @Config::get($this->key)->value;
        $meta = $this->model->getMeta($this->key);
        return @$meta->value;
    }

    public function setPermission($scope, $user = null)
    {
        $this->user = $user ?? app("user");
        $this->permission = $scope;
        return $this;
    }

    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    public function can()
    {
        if (!isset($this->permission) or $this->permission == "") {
            return true;
        }
        $this->user = $this->user ?? app("user");
        if (!$this->user)
            return false;

        if ($this->permission instanceof Model)
            return $this->user->access($this->permission);
        return $this->user->can($this->permission);
    }

    public function render($update = false, $model = null, $config = false)
    {
        $this->model = $model;
        $this->config = $config;
        $view = '';
        if (in_array($this->type, self::$inputAble))
            $view = "assets.meta.input";
        elseif ($this->type == "select")
            $view = "assets.meta.select";
        elseif ($this->type == "textarea")
            $view = "assets.meta.textarea";
        elseif ($this->type == "file")
            $view = "assets.meta.file";
        return view($view, [
            "field" => $this,
            "update" => $update,
            "model" => $model
        ]);
    }
}