<?php


namespace Core;


use App\Helper\Cron;

class Scheduler
{
    private static array $jobs = [];

    const DIR = "dir", DELAY = "delay";

    /**
     * @param $name
     * @param $dir
     * @param $delay
     * @return Cron
     */
    public static function registerJob($dir, $delay)
    {
        $job = new Cron($dir, $delay);
        self::$jobs[] = $job;
        return $job;
    }

    public static function updateCronLastRun($cron)
    {
        $list = static::getRunList();
        $list->{$cron}[] = time();
        if (count($list->{$cron}) > 10)
            array_shift($list->cron);
        static::setRunList($list);
    }

    protected static function setRunList($list)
    {
        file_put_contents(env("STORAGE_PATH", BASEDIR . "/storage") . "/engine/scheduler.json", json_encode($list));
    }

    public static function getRunList($cron = null)
    {
        $path = env("STORAGE_PATH", BASEDIR . "/storage") . "/engine/scheduler.json";
        $scheduler = @json_decode(@file_get_contents($path));
        if (!$scheduler) {
            file_put_contents($path, "{}");
            return new \stdClass;
        }
        if ($cron)
            return @$scheduler->{$cron};

        return $scheduler;
    }

    public static function handle()
    {
        foreach (self::$jobs as $job) {
            $job->run();
        }
    }

}