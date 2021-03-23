<?php
/**
 * @copyright Aliakbar Soleimani 2020
 * Class ServiceProviderBootstrap
 * Bootstrap Files
 */

namespace Core;

use Illuminate\Support\Collection;

/**
 * Class ServiceProviderBootstrap
 * @package Core
 */
final class ServiceProviderBootstrap
{
    const PROVIDERS_DIR = __DIR__ . "/../app/Provider";

    private static Collection $services;

    private function __construct()
    {
        isset(self::$services) ?: self::$services = collect([]);
    }

    private function getServiceList()
    {
        return self::$services = collect(array_merge(self::$services->toArray(), config("services", true)));
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
        is_null($services) ?: self::$services = collect($services);
        return (new self)->initialize();
    }
}