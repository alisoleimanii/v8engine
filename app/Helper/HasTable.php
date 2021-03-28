<?php


namespace App\Helper;

use App\Exception\V8Exception;
use App\Helper\View\Footer;
use Illuminate\Support\Collection;

define("COLUMN_PROPERTY", "prop");
define("COLUMN_META", "meta");

/**
 * Trait HasTable
 * @package Module\Table
 * @need Jquery Datatable
 */
trait HasTable
{
    private static Collection $items;

    private static function table()
    {
        return isset(self::$items) ? self::$items : self::initialize();
    }

    private static function initialize()
    {
        return self::$items = collect(self::getDefaultColumns());
    }

    public static function addTableColumn($slug, $title, $data, $permission = null, $priority = 0)
    {
        if (!self::checkColumnData($data))
            throw new V8Exception("Unsupported {$slug} Column Data");

        self::table()->add(compact("slug", "title", "data", "permission", "priority"));
    }

    private static function checkColumnData($data)
    {
        return $data == \COLUMN_PROPERTY or $data == \COLUMN_META or is_callable($data);
    }

    private static function getData($column, $model)
    {
        return $column["data"] == COLUMN_PROPERTY ?
            $model->{$column["slug"]} :
            ($column["data"] == COLUMN_META ?
                @$model->metaMap()->get($column["slug"])->value :
                $column["data"]($model));
    }

    public static function renderTable(Collection $records)
    {
        $id = str_replace("\\", "_", static::class);
        Footer::create("table", "<script>$('#{$id}').DataTable({'pageLength': 25})</script>");
        return view("table", ["records" => $records, "header" => self::renderTableHeader(), "body" => self::renderTableBody($records), "id" => $id]);
    }

    private static function renderTableHeader()
    {
        $header = '';
        foreach (self::table()->sortBy("priority") as $column) {
            if (self::condition($column))
                $header .= "<th id='{$column['slug']}'>{$column['title']}</th>";
        }
        return $header;
    }

    private function renderRow($column, $record)
    {
        if (self::condition($column))
            return "<td>" . self::getData($column, $record) . "</td>";
        return null;
    }

    private static function renderTableBody(Collection $records)
    {
        $body = "";
        foreach ($records as $record) {
            /**
             * @var HasTable $record
             */
            $body .= "<tr class=''>";
            foreach (self::table()->sortBy("priority") as $column) {
                $body .= $record->renderRow($column, $record);
            }
            $body .= "</tr>";
        }
        return $body;
    }

    protected static function getDefaultColumns(): array
    {
        return [];
    }

    public function removeTableColumn()
    {

    }

    private static function condition($column)
    {
        return app("user")->can(@$column["permission"]);
    }
}