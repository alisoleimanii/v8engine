<?php

use App\Helper\MetaField;
use App\Model\Config;
use Illuminate\Http\Request;

menu("config", "تنظیمات", "dashboard/config", '', "admin.configs", "icon-settings", 9);

Config::addMetaField(
    MetaField::make("logo", "لینک لوگو", ["required"])->setConfig()->setColumn("col-md-6"),
    function (Config $config, Request $request, $value) {
        $config->update([Config::VALUE => $value]);
    });

Config::addMetaField(
    MetaField::make("icon", "لینک ایکون", ["required"])->setConfig()->setColumn("col-md-6"),
    function (Config $config, Request $request, $value) {
        $config->update([Config::VALUE => $value]);
    });
