<?php


namespace Core;

use Illuminate\{Filesystem\Filesystem, Translation\FileLoader, Translation\Translator};

/**
 * Class Translation
 * @package Core
 * @property Translator[] $translators
 */
class Translation
{

    const LOADER_PATH = BASEDIR . "/lang";
    private static self $instance;
    private FileLoader $loader;
    private array $translators = [];

    private function __construct()
    {
        $this->initializeLoader();
    }

    private function initializeLoader()
    {
        $this->loader = new FileLoader(new Filesystem(), self::LOADER_PATH);
    }

    public static function instance()
    {
        if (!isset(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    public function registerTranslator($locale)
    {
        $this->translators[$locale] = new Translator($this->loader, $locale);
    }

    public function translate($key, $locale = null, $default = null)
    {
        $translate = $this->translators[$locale ? $locale : App::getLocale()]->get($key);
        return $translate != $key ? $translate : $default;
    }

    public static function getTranslator($locale = null)
    {
        $locale = $locale ? $locale : App::getLocale();
        return self::instance()->translators[$locale];
    }
}