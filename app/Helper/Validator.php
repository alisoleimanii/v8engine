<?php


namespace App\Helper;


use Core\App;

class Validator
{
    const REQUIRED = "required", ARRAY = "array";

    public static function make($data, $rules, $exit = true, $messages = [], $attributes = [])
    {
        $validator = App::validator()->make($data, $rules, $messages, $attributes);
        if ($validator->fails())
            return $exit ? die((new Submitter(false, __("validation.msg", "Validate Fails")))->setDataAttribute($validator->errors())->send()) : $validator;
        return true;
    }
}