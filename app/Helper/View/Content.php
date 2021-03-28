<?php


namespace App\Helper\View;

use App\Helper\Renderable;

class Content extends Renderable
{

    public static function create($slug, $content = "", $routes = null, $permission = null, $priority = 0)
    {
        return prop("content")->add(["slug" => $slug, "content" => $content, "permission" => $permission, "priority" => $priority, "routes" => $routes]);
    }

    public function render($object): ?string
    {
        return is_callable($object["content"]) ? $object["content"]() : $object['content'];
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