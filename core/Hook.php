<?php


namespace Core;

use Exception;

class Hook
{
    /**
     * Hook Lists
     * @var array $hooks [Hook Name=>callable]
     */
    private array $hooks = [];

    /**
     * Hooks Lunch After Fully Loaded App
     * @var array $afterHooks
     */
    private array $afterHooks = [];

    /**
     * After Hooks who Called in App
     * @var array $calledAfterHooks
     */
    private array $calledAfterHooks = [];

    /**
     * Main Class Instance
     * @var self $instance
     */
    private static self $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private static function instance()
    {
        if (!isset(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    private function addHook($hook, $action)
    {
        if (!isset($this->hooks[$hook]))
            $this->hooks[$hook] = $action;
        else
            throw new Exception("Hook was Already Exists");
    }

    public static function setHook($hook, $action)
    {
        self::instance()->addHook($hook, $action);
    }

    private function callHook($hook, $args)
    {
        if (!isset($this->hooks[$hook]))
            return $this->hooks[$hook](...$args);
        throw new Exception("Hook not Found");
    }

    public static function runHook($hook, ...$args)
    {
        self::instance()->callHook($hook, $args);
    }

    private function addAfterHook($hook, $action)
    {
        if (!isset($this->hooks[$hook]))
            $this->afterHooks[$hook] = $action;
        else
            throw new Exception("Hook was Already Exists");
    }

    public static function setAfterHook($hook, $action)
    {
        self::instance()->addAfterHook($hook, $action);
    }

    private function callAfterHook($hook, $args)
    {
        $this->calledAfterHooks[] = ["hook" => $hook, "args" => $args];
    }

    public static function runAfterHook($hook, ...$args)
    {
        self::instance()->callAfterHook($hook, $args);
    }

    public static function runCalledAfterHooks()
    {
        $instance = self::instance();
        foreach ($instance->calledAfterHooks as $hook) {
            $instance->afterHooks[$hook["hook"]](...$hook["args"]);
        }
    }
}