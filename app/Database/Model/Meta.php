<?php


namespace App\Model;


use Core\Model;

class Meta extends Model
{
    const KEY = "key", VALUE = "value";
    protected $table = "meta";
    protected $fillable = ["key", "value"];
    protected $hidden = ["created_at", "updated_at", "metaable_type", "id", "metaable_id"];

    public function metaable()
    {
        return $this->morphTo();
    }

    public function __toString()
    {
        return $this->value;
    }
}