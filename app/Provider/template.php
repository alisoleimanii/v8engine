<?php

use App\Helper\Template;

$templates = collect(array_merge(config("templates"), [Template::class]));

$templates->each(function ($template) {
    /**
     * @var $template Template
     */
    template($template::getTemplateTitle(), new $template());
});