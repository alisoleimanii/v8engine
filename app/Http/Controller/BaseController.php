<?php

namespace App\Controller;

use App\Helper\Submitter;
use App\Model\Config;
use Illuminate\Http\Request;

class BaseController
{
    public function view()
    {
        return view("main");
    }

    public function config()
    {
        return view("config");
    }

    public function storeConfig(Request $request)
    {
        validate($request->all(), Config::$metaRules);
        Config::handleMeta(Config::get("configController", 1), $request);
        return Submitter::refresh();
    }
}