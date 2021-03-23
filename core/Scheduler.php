<?php


namespace Core;


class Scheduler
{
    private static array $jobs = [];

    const DIR = "dir", DELAY = "delay";

    /**
     * @param $dir
     * @param $delay
     * @return bool
     */
    public static function registerJob($dir, $delay)
    {
        self::$jobs[] = [self::DIR => $dir, self::DELAY => $delay];
        return true;
    }

    public static function handle()
    {
        foreach (self::$jobs as $job) {
            require $job[self::DIR];
        }
    }

}