<?php

require_once __DIR__ . '/../vendors/working-time/vendor/autoload.php';

use gtvolk\WorkingTime\WorkingTime;

/**
 * Class WorkingTimeComponent
 */
class WorkingTimeComponent
{
    /**
     * @var WorkingTime
     */
    private $workingTime;

    /**
     * @var Sla
     */
    private $sla;

    /**
     * @var Zpriority
     */
    private $priority;

    /**
     * WorkingTimeComponent constructor.
     * @param Sla $sla
     * @param Zpriority $priority
     * @throws Exception
     */
    public function __construct($sla, $priority)
    {
        $this->sla = $sla;
        $this->priority = $priority;
        $this->workingTime = static::createFromSla($sla);
    }

    /**
     * @param null $date
     * @return string
     * @throws Exception
     */
    public function getReaction($date = null)
    {
        $reactionMin = 60 * (int)$this->sla->retimeh + (int)$this->sla->retimem + (int)$this->priority->rcost;

        return $this->workingTime->modify($reactionMin, $date);
    }

    /**
     * @param null $date
     * @return string
     * @throws Exception
     */
    public function getSolution($date = null)
    {
        $solutionMin = 60 * (int)$this->sla->sltimeh + (int)$this->sla->sltimem + (int)$this->priority->scost;
        // echo '<br> ^^^^^^<br>';
        // var_dump($this->sla->sltimeh);
        // echo '<br> ****<br>';
        // echo '<br> ^^^^^^<br>';
        // echo $solutionMin;
        // echo '<br> ****<br>';
        return $this->workingTime->modify($solutionMin, $date);
    }

    /**
     * @param null $date
     * @return string|null
     * @throws Exception
     */
    public function getAutoClose($date = null)
    {
        if ($this->sla->autoClose) {
            $autoCloseMin = 60 * (int)$this->sla->autoCloseHours;
            return $this->workingTime->modify($autoCloseMin, $date);
        }

        return null;
    }

    /**
     * @param array $config
     * @return WorkingTime
     * @throws Exception
     */
    public static function create($config)
    {
        return new WorkingTime($config);
    }

    /**
     * @param Sla $sla
     * @return WorkingTime
     * @throws Exception
     */
    public static function createFromSla($sla)
    {
        $time = $sla->wstime . '-' . $sla->wetime;
        $config = [
            'workingDays' => [
                '1' => $time,
                '2' => $time,
                '3' => $time,
                '4' => $time,
                '5' => $time,
            ],
        ];

        if ($sla->round_days) {
            $config['workingDays']['6'] = $time;
            $config['workingDays']['0'] = $time;
        } else {
            $config['weekends'] = ['6', '0'];
        }

        if ($sla->taxes) {
            $taxes = str_replace(['.*', '*', ' '], '', $sla->taxes);
            $holidays = explode(',', str_replace(['.'], '-', $taxes));
            $config['holidays'] = $holidays;
        }

        return static::create($config);
    }
}
