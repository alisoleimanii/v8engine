<?php


namespace App\Helper;


use Core\App;
use Illuminate\Support\Collection;
use Module\JWT\JWT;

abstract class Renderable extends Collection
{
    abstract public function render($object): ?string;

    abstract public function prioritySort(): self;

    abstract public function can($object): bool;

    public function checkRoute($object)
    {
        return is_null(@$object['routes']) or in_array(App::router()->getCurrentRoute()->getName(), $object["routes"]);
    }

    public function user()
    {
        return JWT::getUser();
    }

    public function renderPrefix()
    {
        return "";
    }

    public function renderAppend()
    {
        return "";
    }
}