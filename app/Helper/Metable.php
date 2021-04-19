<?php


namespace App\Helper;


use App\Model\Meta;
use Core\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Module\SocialMarket\Model\Product;

/**
 * Trait Metable
 * @package App\Helper
 * @property Collection metas
 */
trait Metable
{
    public static Collection $metaFields;
    public static array $metaRules = [];
    public Collection $metaMap;

    protected static function boot()
    {
        self::morphMap();
        parent::boot();
    }

    public function metas()
    {
        return $this->morphMany(Meta::class, "metaable");
    }

    public static function morphMap()
    {
        Relation::morphMap([
            self::class => static::class
        ]);
    }

    public function getMeta($key)
    {
        return $this->metas()->where(Meta::KEY, $key)->first();
    }

    //1618750377
    //1618750226

    public function setMeta($key, $value, $update = true)
    {
        $meta = $this->getMeta($key);
        if ($meta and $update) {
            $meta->update([Meta::VALUE => $value]);
            return $meta;
        }
        return $this->metas()->create([Meta::KEY => $key, Meta::VALUE => $value]);
    }

    public function metaMap($reload = false)
    {
        if (!isset($this->metaMap) and !$reload)
            $this->metaMap = $this->metas->keyBy(Meta::KEY);
        return $this->metaMap;
    }

    public static function addMetaField(MetaField $field, $closure = null)
    {
        if ($field->can()) {
            if (!isset(self::$metaFields)) {
                self::$metaFields = new Collection();
            }
            $field->model = static::class;
            $field->setController($closure ? $closure : function (Model $user, Request $request, $meta, MetaField $metaField) {
                $user->setMeta($metaField->key, $meta);
            });
            self::$metaFields->add($field);
            self::$creatable[] = $field->key;
            self::$updatable[] = $field->key;
            self::$metaRules[$field->key] = $field->rules;
        }
    }

    public static function renderMetaFields($update = false, $model = null, $config = false)
    {
        if (!isset(self::$metaFields)) {
            return "";
        }
        $fields = "";
        foreach (self::$metaFields as $field) {
            $fields .= $field->render($update, $model, $config);
        }
        return $fields;
    }

    public static function getRequiredFields()
    {
        if (!isset(self::$metaFields))
            self::$metaFields = new Collection();
        return self::$metaFields->filter(function (MetaField $field) {
            return in_array("required", $field->rules);
        })->pluck("key");
    }

    public static function getFileFields()
    {
        if (!isset(self::$metaFields))
            self::$metaFields = new Collection();
        return self::$metaFields->filter(function (MetaField $field) {
            return $field->type == "file";
        })->pluck("key");
    }

    public static function getOptionalFields()
    {
        if (!isset(self::$metaFields))
            self::$metaFields = new Collection();
        return self::$metaFields->filter(function (MetaField $field) {
            return !in_array("required", $field->rules);
        })->pluck("key");
    }

    public static function renderSubmitterArgs($argument, $firstComa = true)
    {

        $method = "get" . ucfirst($argument) . "Fields";
        $fields = self::$method();
        $html = "";
        $first = true;
        foreach ($fields as $field) {
            if (!($first and !$firstComa))
                $html .= ",";
            $html .= "'#" . $field . "'";
        }
        return $html;
    }

    public static function handleMeta(self $model, Request $request)
    {
        if (!isset(self::$metaFields))
            self::$metaFields = new Collection();
        self::$metaFields->map(function (MetaField $field) use ($model, $request) {
            $field->callController($model, $request, $field->type != "file" ? $request->input($field->key) : $request->file($field->key));
        });
        Event::listen($model::class . ".updated", $model);
    }

    public static function renderSubmitter($fields, $url, $method, $filesFields, $optional, $additional = [], $autoResponse = true, $loading = true, $after = "")
    {
        $metaRequiredFields = self::getRequiredFields();
        foreach ($metaRequiredFields as $field) {
            $fields[] = "#" . $field;
        }
        foreach ($fields as $key => $field) {
            $fields[$key] = "'" . $field . "'";
        }
        $metaOptionalFields = self::getOptionalFields();
        foreach ($metaOptionalFields as $field) {
            $optional[] = "#" . $field;
        }
        foreach ($optional as $key => $field) {
            $optional[$key] = "'" . $field . "'";
        }
        $keys = [];
        $values = [];
        foreach ($additional as $field) {
            $keys[] = "'" . $field["key"] . "'";
            $values[] = $field["value"];
        }
        if (!is_array($filesFields) and trim($filesFields) == "")
            $filesFields = [];
        $files = [];
        $filesFields = array_merge($filesFields, self::getFileFields()->toArray());
        foreach ($filesFields as $key => $field) {
            $files[$key] = "'#" . $field . "'";
        }
        $fields = implode(",", $fields);
        $optional = implode(",", $optional);
        $keys = implode(",", $keys);
        $values = implode(",", $values);
        $files = implode(",", $files);
        return view("assets.meta.submitter", compact("fields", 'optional', 'keys', "values", "after", "url", "method", "autoResponse", "loading", "files"));
    }

}
