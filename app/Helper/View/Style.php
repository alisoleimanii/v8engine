<?php


namespace App\Helper\View;


use App\Helper\Renderable;
use Closure;

class Style extends Renderable
{
    public static Closure $render;

    public static function enqueue($slug, $src, $routes = null, $permission = null, $priority = 0)
    {
        return prop("styles")->add(["slug" => $slug, "src" => $src, "permission" => $permission, "priority" => $priority, "routes" => $routes]);
    }

    public function render($object): ?string
    {
        return isset(self::$render) ? self::$render->call($this, $object) : "<link href='{$object['src']}' rel='stylesheet'>";
    }

    public function prioritySort(): Renderable
    {
        return $this->sortBy("priority");
    }

    public function can($object): bool
    {
        return $this->user()->can($object["permission"]);
    }
}