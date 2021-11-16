<?php

use App\Helper\MetaField;
use App\Model\Config;
use Illuminate\Http\Request;

menu("config", lang('base.settings','Settings'), url("dashboard/config"), '', "admin.configs", "icon-settings", 9);