<?php


namespace App\Helper\View;


use Illuminate\Support\Collection;

class Menu
{
    private static array $menu = [];


    public static function add($slug, $title, $url, $parent = "", $permission = null, $icon = null, $priority = 0)
    {
        self::$menu[$slug] = ["name" => $title, "url" => $url, "parent" => $parent, "icon" => $icon, "permission" => $permission, "priority" => $priority];
    }

    public static function renderHtml()
    {
        $menu = Collection::make(self::$menu)->sortBy("priority");
        $html = "";
        foreach ($menu as $slug => $menu)
            if ($menu["parent"] == "")
                $html .= self::handle($slug);
        return $html;
    }

    private static function handle($slug)
    {
        $menu = self::$menu[$slug];
        $children = self::children($slug);
        $user = app("user");
        $html = "";
        if ($user->can($menu["permission"]))
            if ($children == []) {
                $html .= self::render($menu) . self::closeTags();
            } else {
                $html .= self::render($menu, true);
                foreach ($children as $slug => $child) {
                    $html .= self::handle($slug);
                }
                $html .= self::closeTags(true);
            }
        return $html;
    }

    private static function children($slug)
    {
        $children = [];
        foreach (self::$menu as $pslug => $menu)
            if ($menu["parent"] == $slug)
                $children[$pslug] = $menu;
        return $children;
    }

    private static function render($menu, $hasChild = false)
    {
        $class = $hasChild ? 'dropdown-toggle' : '';
        $html = ' <li>
                   <a href = "' . url($menu["url"]) . '" class="' . $class . '">
                      <i class="' . $menu["icon"] . '" ></i>
                       <span > ' . $menu["name"] . '</span>
                   </a>';
        return $hasChild ? $html . "<ul>" : $html;
    }

    private static function closeTags($hasChild = false)
    {
        return $hasChild ? " </ul>
                             </li> " : "</li> ";
    }
}