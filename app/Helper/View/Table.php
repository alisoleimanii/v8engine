<?php


namespace App\Helper\View;


use App\Helper\HasTable;
use Illuminate\Support\Collection;

class Table
{
    public Collection $columns;
    public Collection $rows;

    /**
     * Table constructor.
     * @param $columns
     * @param $rows
     */
    public function __construct($columns, $rows)
    {
        $this->columns = collect($columns);
        $this->rows = collect($rows);
    }

    public function render($id = null)
    {
        $id = $id ? $id : str_replace("\\", "_", static::class);
//        Footer::create("table", "<script>var {$id} = $('#{$id}').DataTable({'pageLength': 25})</script>");
        return view("table", ["header" => $this->header(), "body" => $this->body(), "id" => $id]);
    }

    private function header()
    {
        $this->columns->sortBy('priority')->each(function ($column) use (&$content) {
            $content .= "<th id='{$column['slug']}'>{$column['title']}</th>";
        });
        return $content;
    }

    private function row($column, $row)
    {
        #todo  Permissions
        return "<td class='{$column['slug']}'>" . $this->content($column, $row) . "</td>";
    }

    private function content($column, $row)
    {
        return $column["data"] == COLUMN_PROPERTY ? $row->{$column["slug"]} : $column["data"]($row);
    }

    private function body()
    {
        $this->rows->each(function ($row) use (&$content) {
            $content .= "<tr class='t-row' data-row='$row->id'>";
            $this->columns->sortBy('priority')->each(function ($column) use (&$content, $row) {
                $content .= $this->row($column, $row);
            });
            $content .= "</tr>";
        });
        return $content;
    }
}