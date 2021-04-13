<?php


namespace App\Helper\View;


use App\Helper\Renderable;
use Closure;

class Style extends Renderable
{
    public static Closure $render;

    public static function enqueue($slug, $src, $template, $routes = null, $permission = null, $priority = 0)
    {
        return prop("styles")->add(["slug" => $slug, "template" => $template, "src" => $src, "permission" => $permission, "priority" => $priority, "routes" => $routes]);
    }

    public function render($object, ...$params): ?string
    {
        return isset(self::$render) ? self::$render->call($this, $object, $params[0]) : "<link href='{$object['src']}' rel='stylesheet'>";
    }

    public function prioritySort(...$params): Renderable
    {
        return $this->where('template', $params[0])->sortBy("priority");
    }

    public function can($object): bool
    {
        return $this->user()->can($object["permission"]);
    }
}