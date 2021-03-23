<?php


namespace Core;

use App\Helper\Submitter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * @method static static create($array)
 * @method static static first($columns = ["*"])
 * @method static static find($id, $columns = ["*"])
 * @method static Collection get($columns = "")
 * @method static static where(...$condition)
 * @method static static with(...$condition)
 * @method static static orderBy($column, $order)
 * @method static static whereIn(...$condition)
 * @method static whereHas(string $string, $param)
 * @property mixed id
 * @property string $created_at_p persian date
 * @property string $updated_at_p persian date
 * @property string $created_at_diff diff from now
 * @property string $updated_at_diff diff from now
 */
class Model extends BaseModel
{
    const ID = "id";
    public static array $creatable = [];
    public static array $updatable = [];
    public static array $updateRules = [];
    public static array $createRules = [];


    /**
     * @param $id
     * @param string $abortMsg
     * @param string[] $columns
     * @return static
     */
    public static function findOrFail($id, $abortMsg = "", $columns = ["*"])
    {
        $row = static::find($id, $columns);
        if (!$row) {
            echo Submitter::error($abortMsg ?? "Wrong Request");
            die();
        }
        return $row;
    }

    public function diffForHumans(Carbon $date, Carbon $until = null)
    {
        $until = $until ?? Carbon::now();
        return str_replace("از", "", $date->diffForHumans($until));
    }

    public function getCreatedAtPAttribute()
    {
        return verta($this->created_at)->formatDatetime();
    }

    public function getUpdatedAtPAttribute()
    {
        return verta($this->updated_at)->formatDatetime();
    }

    public function getCreatedAtDiffAttribute()
    {
        return $this->diffForHumans($this->created_at);
    }

    public function getUpdatedAtDiffAttribute()
    {
        return $this->diffForHumans($this->updated_at);
    }
}