<?php


namespace App\Helper\View;

use App\Helper\Renderable;
use Closure;

class Style extends Renderable
{
    public static Closure $render;
    public static string $template;

    public static function enqueue($slug, $src, $template, $routes = null, $permission = null, $priority = 0,$id = null)
    {
        return prop("styles")->add(["slug" => $slug, "template" => $template, "src" => $src,"id" => $id, "permission" => $permission, "priority" => $priority, "routes" => $routes]);
    }

    public function render($object, ...$params): ?string
    {
        if (isset(self::$render)) {
            $content = self::$render->call($this, $object, $params[0]);
        }
        if (!isset($content) or $content == RENDER_DEFAULT)
        {
            $content = "<link href='{$object['src']}' rel='stylesheet' " .($object['id'] ? "id='{$object['id']}'" : ""). ">";
        }
        return $content;
    }

    public function prioritySort(...$params): Renderable
    {
        return $this->where('template', $params[0])->sortBy("priority");
    }

    public function can($object): bool
    {
        return true;
        return $this->user()->can($object["permission"]);
    }
}