<?php


namespace App\Helper\View;

class Menu
{
    private static array $menu = [];


    public static function add($slug, $title, $url, $parent = "", $permission = null, $icon = null, $priority = 0)
    {
        self::$menu[] = ["name" => $title, "slug" => $slug, "url" => $url, "parent" => $parent, "icon" => $icon, "permission" => $permission, "priority" => $priority];
    }

    public static function getMenu()
    {
        return self::$menu;
    }
}