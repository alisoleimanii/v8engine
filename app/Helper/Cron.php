<?php


namespace App\Helper;


use Core\Scheduler;
use Illuminate\Support\Traits\Macroable;

class Cron
{
    use Macroable;

    private string $name;
    private string $path;
    private int $delay;
    private string $buffer = "";

    /**
     * Cron constructor.
     * @param $path string Cron php file path
     * @param int $delay Delay between execute cron (in minutes)
     */
    public function __construct(string $path, int $delay = 1)
    {
        $this->path = $path;
        $this->delay = $delay;
    }

    /**
     * Set cron name (it`s necessary for work with delays)
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function run()
    {
        if ($this->shouldBeRun()) {
            $this->setLastRun();
            @ob_clean();
            require $this->path;
            $this->buffer = ob_get_clean();
            echo $this->buffer;
        }
    }

    private function setLastRun()
    {
        !isset($this->name) ?: Scheduler::updateCronLastRun($this->name);
    }

    private function getLastRun()
    {
        return !isset($this->name) ?: last(Scheduler::getRunList($this->name) ?? []);
    }

    private function shouldBeRun()
    {
        $lastRun = $this->getLastRun();
        return ($lastRun ?? 0) <= time() - ($this->delay * 60);
    }
}