<?php

use Core\{App, Translation};


$translator = Translation::instance();

/*
 * Register locales
 */
$locales = config("locales");
foreach ($locales as $locale) {
    $translator->registerTranslator($locale);
}

/*
 * Set first locale to default app locale
 */
App::setLocale(env("LOCALE", $locales[0]));
