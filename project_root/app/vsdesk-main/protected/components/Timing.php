<?php

class Timing
{

    private $weekends = array(6, 7);
    public $holidays = array();
    private $format = 'Y-m-d H:i';

    public function __construct()
    {
        if (ini_get('date.timezone') == '') {
            date_default_timezone_set(Yii::app()->params['timezone']);
        }
    }

    /**
     * Расчет времен решения и реакции на задачу
     * $created - время создания заявки d-m-Y H:i (17-07-2014 23:54)
     * $reaction_time - время реакции на заявку H:i (23:54)
     * $solve_time - время решения завяки H:i (23:54)
     * $wdstart - время начала рабочего дня H:i (23:54)
     * $wdend - время окончания рабочего дня H:i (23:54)
     * $weekend - учет выходных (1 - учитывая выходные, 0 - не учитывая выходные)
     * $add_mins - кол-во минут, которое прибавляется или вычитается от каждой из метрик
     * return array(время окончания реакции, время окончания решения)
     */
    public function get_solution_dates(
        $created,
        $reaction_time,
        $solve_time,
        $wdstart,
        $wdend,
        $weekend,
        $radd_mins = 0,
        $sadd_mins = 0,
        $holidays_in
    ) {

        $this->holidays = explode(',', $holidays_in);
        //var_dump($this->holidays);
        $created_obj = new DateTime($created);

        $reaction_time_arr = explode(':', $reaction_time);
        $reaction_time_min = $reaction_time_arr[0] * 60 + $reaction_time_arr[1]; // in minutes

        $solve_time_arr = explode(':', $solve_time);
        $solve_time_min = $solve_time_arr[0] * 60 + $solve_time_arr[1]; // in minutes

        // прибавляем или вычитаем доп. минуты от метрик
        if ($radd_mins != 0) {
            // даже если $add_mins отрицательный, то все верно сработает. просто складываем величины.
            $reaction_time_min = $reaction_time_min + $radd_mins;
        }
        if ($sadd_mins != 0) {
            // даже если $add_mins отрицательный, то все верно сработает. просто складываем величины.
            $solve_time_min = $solve_time_min + $sadd_mins;
        }

        $solve_react_diff_min = $solve_time_min - $reaction_time_min;

        $wdstart_obj = DateTime::createFromFormat('H:i', $wdstart)
            ->setDate($created_obj->format('Y'), $created_obj->format('m'), $created_obj->format('d'));
        $wdstart_date = array($wdstart_obj->format('Y'), $wdstart_obj->format('m'), $wdstart_obj->format('d'));
        $wdstart_time = array($wdstart_obj->format('H'), $wdstart_obj->format('i'));
        $wdstart_min = $wdstart_time[0] * 60 + $wdstart_time[1]; // in minutes

        $wdend_obj = DateTime::createFromFormat('H:i', $wdend)
            ->setDate($created_obj->format('Y'), $created_obj->format('m'), $created_obj->format('d'));;
        $wdend_date = array($wdend_obj->format('Y'), $wdend_obj->format('m'), $wdend_obj->format('d'));
        $wdend_time = array($wdend_obj->format('H'), $wdend_obj->format('i'));
        $wdend_min = $wdend_time[0] * 60 + $wdend_time[1]; // in minutes

        // получаем день старта и время старта
        $started_obj = clone $created_obj;
        $started_date = array($started_obj->format('Y'), $started_obj->format('m'), $started_obj->format('d'));
        $started_time = array($started_obj->format('H'), $started_obj->format('i'));

        if ($started_obj < $wdstart_obj) {
            $started_obj->setTime($wdstart_time[0], $wdstart_time[1]);
        }

        if (($started_obj > $wdend_obj)
            || ($this->is_holiday($started_date) === true && $weekend == 0)
        ) {
            $started_obj = $weekend == 0 ? new DateTime($this->get_next_business_day($started_date))
                : $started_obj->modify('+1 day');
            $started_obj->setTime($wdstart_time[0], $wdstart_time[1]);
            $started_date = array($started_obj->format('Y'), $started_obj->format('m'), $started_obj->format('d'));
            $started_time = array($started_obj->format('H'), $started_obj->format('i'));
        }
        $started_min = $started_time[0] * 60 + $started_time[1];

        // > full metrik code was here <

        $full_reaction_min = $this->get_full_metric_min($reaction_time_min, $wdstart_obj, $wdend_obj, $started_min,
            $wdend_min);
        $reaction_obj = clone $started_obj;
        $reaction_obj->modify('+' . $full_reaction_min . ' minutes');
        $reaction_date = $this->get_calculated_date($reaction_obj, $started_obj, $wdstart_obj, $wdend_obj, $weekend);

        // > get date code was here <

        // work with solve time
        $full_solve_min = $this->get_full_metric_min($solve_time_min, $wdstart_obj, $wdend_obj, $started_min,
            $wdend_min);
        $solve_obj = clone $started_obj;
        $solve_obj->modify('+' . $full_solve_min . ' minutes');
        $solve_date = $this->get_calculated_date($solve_obj, $started_obj, $wdstart_obj, $wdend_obj, $weekend);

        return array(
            'reaction' => $reaction_date->format($this->format),
            'solution' => $solve_date->format($this->format)
        );
    }

    /**
     * Check if the date is a holiday or not
     *
     * @param string('Y-m-d')|array('Y', 'm', 'd') $date
     * @return TRUE|FALSE
     */
    public function is_holiday($date)
    {
        if (is_array($date)) {
            $date = implode('-', $date);
        }

        $cur_day_obj = new DateTime($date);

        if (in_array($cur_day_obj->format('N'), $this->weekends)
            || in_array($cur_day_obj->format('d.m.*'), $this->holidays)
            || in_array($cur_day_obj->format('d.m.Y'), $this->holidays)
        ) {
            return true;
        }

        return false;
    }

    /**
     * Get next business day after holiday or weekend
     *
     * @param string ('Y-m-d') || array('Y', 'm', 'd') || DateTime object - $date
     * @return date 'Y-m-d'
     */
    public function get_next_business_day($date)
    {
        if (is_array($date)) {
            $date = implode('-', $date);
        }
        /** @var TYPE_NAME $date */
        $cur_day_obj = is_object($date) ? clone $date : new DateTime($date);
        $cur_day_obj->modify('+1 Weekday'); // next closest business day
        $next_day = $cur_day_obj->format('Y-m-d');
        if ($this->is_holiday($next_day) === true) {
            return $this->get_next_business_day($cur_day_obj);
        } // return next business day
        else {
            return $next_day;
        }
    }

    private function get_calculated_date($cur_date_obj, $start_obj, $wdstart_obj, $wdend_obj, $weekend)
    {
        if ($weekend == 0) {
            $end_date_obj = clone $cur_date_obj;
            $add_days = 0;
            $one_day = new DateInterval('P1D');

            $period = new DatePeriod($start_obj, $one_day, $end_date_obj->modify('+1 second'));
            foreach ($period as $day) {
                if ($this->is_holiday(array($day->format('Y'), $day->format('m'), $day->format('d'))) === true) {
                    $add_days++;
                }
            }
            $cur_date_obj->modify('+ ' . $add_days . ' days');
            unset($day, $period, $add_days, $end_date_obj);
        }
        $cur_date = array($cur_date_obj->format('Y'), $cur_date_obj->format('m'), $cur_date_obj->format('d'));

        $wdstart_obj->setDate($cur_date[0], $cur_date[1], $cur_date[2]);
        $wdend_obj->setDate($cur_date[0], $cur_date[1], $cur_date[2]);

        if ($this->is_holiday($cur_date) === true && $weekend == 0) {
            $cur_date_obj = new DateTime($this->get_next_business_day($cur_date) .
                $cur_date_obj->format('H') . ':' . $cur_date_obj->format('i'));
            $cur_date = array($cur_date_obj->format('Y'), $cur_date_obj->format('m'), $cur_date_obj->format('d'));
            $wdstart_obj->setDate($cur_date[0], $cur_date[1], $cur_date[2]);
            $wdend_obj->setDate($cur_date[0], $cur_date[1], $cur_date[2]);
        }

        if ($cur_date_obj < $wdstart_obj) {
            $cur_date_obj->setTime($wdstart_obj->format('H'), $wdstart_obj->format('i'));
        }

        if ($cur_date_obj > $wdend_obj) {
            $diff = $wdend_obj->diff($cur_date_obj);
            $diff_min = $diff->days * 24 * 60;
            $diff_min += $diff->h * 60;
            $diff_min += $diff->i;

            $cur_date_obj = $weekend == 0 ? new DateTime($this->get_next_business_day($cur_date_obj))
                : $cur_date_obj->modify('+1 day');
            $cur_date_obj->setTime($wdstart_obj->format('H'), $wdstart_obj->format('i'));
            $cur_date_obj->modify('+' . $diff_min . ' minutes');
            unset($diff_min);
        }

        return $cur_date_obj;
    }

    public function get_full_metric_min($metric_time_min, $wdstart_obj, $wdend_obj, $started_min, $wdend_min)
    {
        $se_interval_obj = $wdstart_obj->diff($wdend_obj); // start_end_interval_obj
        $start_end = array($se_interval_obj->h, $se_interval_obj->i);
        $start_end_min = $start_end[0] * 60 + $start_end[1]; // время раб. дня в минутах

        $first_day_min = $wdend_min - $started_min;
        $not_working_min = 1440 - $start_end_min;
        if ($metric_time_min - $first_day_min <= 0) {
            $full_metric_min = $metric_time_min;
        } else {
            $full_days = (int)(($metric_time_min - $first_day_min) / $start_end_min);

            $full_metric_min = $first_day_min + ($start_end_min * $full_days)
                + ($metric_time_min - ($first_day_min + ($start_end_min * $full_days)) + $not_working_min * ($full_days + 1));
        }

        return $full_metric_min;
    }

    public function set_format($format)
    {
        $this->format = $format;
    }

    /**
     * Расчет времен решения и реакции на задачу
     * $created - время создания заявки d-m-Y H:i (17-07-2014 23:54)
     * $reaction_time - время реакции на заявку H:i (23:54)
     * $solve_time - время решения завяки H:i (23:54)
     * $wdstart - время начала рабочего дня H:i (23:54)
     * $wdend - время окончания рабочего дня H:i (23:54)
     * $weekend - учет выходных (1 - учитывая выходные, 0 - не учитывая выходные)
     * $add_mins - кол-во минут, которое прибавляется или вычитается от каждой из метрик
     * $holidays_in - выходные дни
     * $auto_close_hours - нужно ли расчитывать автозакрытие
     * return array(время окончания реакции, время окончания решения, время автоматического закрытия)
     */
    public function get_lead_time(
        $created,
        $reaction_time,
        $solve_time,
        $wdstart,
        $wdend,
        $weekend,
        $radd_mins = 0,
        $sadd_mins = 0,
        $holidays_in,
        $auto_close_hours = null
    ) {
        $this->holidays = explode(',', $holidays_in);
        $created_obj = new DateTime($created);

        $reaction_time_arr = explode(':', $reaction_time);
        $reaction_time_min = $reaction_time_arr[0] * 60 + $reaction_time_arr[1]; // in minutes

        $solve_time_arr = explode(':', $solve_time);
        $solve_time_min = $solve_time_arr[0] * 60 + $solve_time_arr[1]; // in minutes

        // прибавляем или вычитаем доп. минуты от метрик
        if ($radd_mins != 0) {
            $reaction_time_min = $reaction_time_min + $radd_mins;
        } // даже если $add_mins отрицательный, то все верно сработает. просто складываем величины.

        if ($sadd_mins != 0) {
            $solve_time_min = $solve_time_min + $sadd_mins;
        } // даже если $add_mins отрицательный, то все верно сработает. просто складываем величины.

        $wdstart_obj = DateTime::createFromFormat('H:i', $wdstart)->setDate($created_obj->format('Y'),
            $created_obj->format('m'), $created_obj->format('d'));
        $wdstart_date = array($wdstart_obj->format('Y'), $wdstart_obj->format('m'), $wdstart_obj->format('d'));
        $wdstart_time = array($wdstart_obj->format('H'), $wdstart_obj->format('i'));
        $wdstart_min = $wdstart_time[0] * 60 + $wdstart_time[1]; // in minutes

        $wdend_obj = DateTime::createFromFormat('H:i', $wdend)
            ->setDate($created_obj->format('Y'), $created_obj->format('m'), $created_obj->format('d'));
        $wdend_date = array($wdend_obj->format('Y'), $wdend_obj->format('m'), $wdend_obj->format('d'));
        $wdend_time = array($wdend_obj->format('H'), $wdend_obj->format('i'));
        $wdend_min = $wdend_time[0] * 60 + $wdend_time[1]; // in minutes

        // получаем день старта и время старта
        $started_obj = clone $created_obj;
        $started_date = array($started_obj->format('Y'), $started_obj->format('m'), $started_obj->format('d'));
        $started_time = array($started_obj->format('H'), $started_obj->format('i'));

        if ($started_obj < $wdstart_obj) {
            $started_obj->setTime($wdstart_time[0], $wdstart_time[1]);
        }

        if (($started_obj > $wdend_obj) || ($this->is_holiday($started_date) === true && $weekend == 0)) {
            $started_obj = $weekend == 0 ? new DateTime($this->get_next_business_day($started_date)) : $started_obj->modify('+1 day');
            $started_obj->setTime($wdstart_time[0], $wdstart_time[1]);
            $started_date = array($started_obj->format('Y'), $started_obj->format('m'), $started_obj->format('d'));
            $started_time = array($started_obj->format('H'), $started_obj->format('i'));
        }
        $started_min = $started_time[0] * 60 + $started_time[1];

        // > full metrik code was here <
        $full_reaction_min = $this->get_full_metric_min($reaction_time_min, $wdstart_obj, $wdend_obj, $started_min,
            $wdend_min);
        $reaction_obj = clone $started_obj;
        $reaction_obj->modify('+' . $full_reaction_min . ' minutes');
        $reaction_date = $this->get_calculated_date($reaction_obj, $started_obj, $wdstart_obj, $wdend_obj, $weekend);

        // > get date code was here <
        // work with solve time
        $full_solve_min = $this->get_full_metric_min($solve_time_min, $wdstart_obj, $wdend_obj, $started_min,
            $wdend_min);
        $solve_obj = clone $started_obj;
        $solve_obj->modify('+' . $full_solve_min . ' minutes');
        $solve_date = $this->get_calculated_date($solve_obj, $started_obj, $wdstart_obj, $wdend_obj, $weekend);

        $auto_close_date = null;
        if ($auto_close_hours) {
            $full_auto_close_min = $this->get_full_metric_min($auto_close_hours * 60, $wdstart_obj, $wdend_obj,
                $started_min, $wdend_min);
            $auto_close_obj = clone $started_obj;
            $auto_close_obj->modify('+' . $full_auto_close_min . ' minutes');
            $auto_close_date = $this->get_calculated_date($auto_close_obj, $started_obj, $wdstart_obj, $wdend_obj,
                $weekend);
        }

        $started_obj = clone $created_obj;

        $started_obj->modify('+' . $solve_time_min . ' minutes');
        $diff2 = $started_obj->diff($solve_date);

        return array(
            'reaction' => $reaction_date->format($this->format),
            'solution' => $solve_date->format($this->format),
            'auto_close' => empty($auto_close_date) ? null : $auto_close_date->format($this->format),
            'correct_timestamp' => $diff2->format("%d:%h:%i:%s"),
        );
    }


    /**
     * Расчет рабочих часов просроченного времени исполнения заявки
     * @param string $dataEnd - время запланированного завершения заявки d-m-Y H:i (17-07-2014 23:54)
     * @param string $dataFaktEnd - время фактического завершения заявки d-m-Y H:i (17-07-2014 23:54)
     * @param string $wdstart - время начала рабочего дня H:i (23:54)
     * @param string $wdend - время окончания рабочего дня H:i (23:54)
     * @param integer $weekend - учет выходных (1 - учитывая выходные, 0 - не учитывая выходные)
     * @param $holidays_in - выходные дни
     * @return int - время просрочки в часах
     */
    public function getExpiredHours($dataEnd, $dataFaktEnd, $wdstart, $wdend, $weekend, $holidays_in)
    {
        $this->holidays = explode(',', $holidays_in);
        $dateTimeEnd = new DateTime($dataEnd);
        $dateTimeFaktEnd = new DateTime($dataFaktEnd);
        $wdStartTime = explode(':', $wdstart);
        $wdEndTime = explode(':', $wdend);

        $totalHours = 0;
        //echo $dateTimeEnd->format("Y-m-d H:i:s") . ' - ' . $dateTimeFaktEnd->format("Y-m-d H:i:s");
        while ($dateTimeEnd < $dateTimeFaktEnd) {
            $dateTimeEnd->modify('+ 1 hours');

            // Если выходной, дальше не идём.
            if (!$weekend and $this->isWeekend($dateTimeEnd->format("Y-m-d H:i:s"))) {
                continue;
            }
            // Если праздник, дальше не идём.
            if ($this->is_holiday($dateTimeEnd->format("Y-m-d"))) {
                continue;
            }

            if ((int)$dateTimeEnd->format("H") >= (int)$wdStartTime[0] and (int)$dateTimeEnd->format("H") <= (int)$wdEndTime[0]) {
                $totalHours++;
            }
        }

        //$sec = (strtotime($dataEnd)) - (strtotime($dataFaktEnd));
        return $totalHours;
    }

    /**
     * @param $date
     * @return bool
     */
    protected function isWeekend($date)
    {
        return (date('N', strtotime($date)) >= 6);
    }

}