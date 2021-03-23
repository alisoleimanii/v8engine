<?php
/**
 * @copyright Aliakbar Soleimani 2020
 * Class ServiceProviderBootstrap
 * Bootstrap Files
 */

namespace Core;

use Illuminate\{Http\Request, Routing\Router};

/**
 * Class ServiceProviderBootstrap
 * @package Core
 */
final class ServiceProviderBootstrap
{
    const PROVIDERS_DIR = __DIR__ . "/../app/Provider";

    private static array $services = [
        /*
         * Load Env
         */
        "DotEnv" => "env",

        /*
         * Create Redis Connection
         */
        "Redis" => "redis",

        /*
         * Create Database Connection
         */
        "Database" => "database",

        /*
         * Config Request Router
         */
        "Router" => "request",

        /*
         * Set Translator for Project
         */
        "I18N" => "i18n",

        /*
         * Request Validator
         */
        "Validator" => "validator",

        /*
         * Default Config List
         */
        "Config" => "configs",

        /*
         * Default View Properties
         */
        "View" => "view",

        /*
         * Run Modules
         */
        "Module" => "module"
    ];

    private function getServiceList()
    {
        return collect(self::$services);
    }

    private function load($provider)
    {
        require_once self::PROVIDERS_DIR . "/" . $provider . ".php";
    }

    private function initialize()
    {
        foreach ($this->getServiceList() as $service) {
            $this->load($service);
        }
        return $this;
    }

    public static function run($services = null)
    {
        is_null($services) ?: self::$services = $services;

        return (new self)->initialize();
    }
}