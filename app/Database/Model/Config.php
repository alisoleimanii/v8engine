<?php


namespace App\Model;


use App\Helper\Metable;
use Core\Model;

class Config extends Model
{
    use Metable;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    const KEY = "key", VALUE = "value";
    protected $table = "config";
    protected $fillable = [self::KEY, self::VALUE];

    public static function set($key, $value = null)
    {
        return self::create([self::KEY => $key, self::VALUE => $value]);
    }

    public static function get($key, $create = false)
    {
        $config = self::where(self::KEY, $key)->first();
        if ($create and !$config)
            $config = self::set($key, null);
        return $config;
    }
}