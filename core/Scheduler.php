<?php


namespace Core;


use App\Helper\Cron;


class Scheduler
{
    private static array $jobs = [];

    const DIR = "dir", DELAY = "delay", STATE_ALL = 'all', STATE_WEB = 'web', STATE_BACKGROUND = 'backround';

    /**
     * @param $name
     * @param $job mixed File path or Callable
     * @param $delay
     * @return Cron
     */
    public static function registerJob($job, $delay)
    {
        $job = new Cron($job, $delay);
        self::$jobs[] = $job;
        return $job;
    }

    /**
     * @internal
     */
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

    /**
     * @internal 
     * @param null $cron
     * @return mixed|\stdClass
     */
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

    /**
     * @internal
     */
    public static function handle($state = self::STATE_ALL)
    {
        foreach (self::$jobs as $job) {
            if ($state === self::STATE_BACKGROUND) {
                if ($job->background === true)
                    $job->run();
            } elseif ($state === self::STATE_WEB) {
                if ($job->background === false)
                    $job->run();
            } else
                $job->run();
        }
    }

}