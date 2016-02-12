<?php
namespace Nnmer\UtilsBundle\Lib;

class DateTime {
    /**
     * Calculating duration of time
     *
     * @param string|int $time1  -from
     * @param string|int $time2    - bigger  - to
     * @param string $calcullationBy  how to calculate the duration (hours|minutes) hours by default
     * @return int  duration
     */
    static public function calculateDuration($time1, $time2, $calculationBy = 'minutes'){
        $time1 = (is_int($time1))?$time1:strtotime($time1);
        $time2	= (is_int($time2))?$time2:strtotime($time2);
        $length = abs($time2 - $time1);
        switch ($calculationBy) {
            case 'months':
                $length = round(floor($length/(60*60*24))/30,0);
                break;
            case 'days':
                $length = floor($length/(60*60*24));
                break;
            case 'minutes':
                $m = $length / 60;
                $length = $m;
                break;
            case 'hours':
            default:
                $h = floor($length/3600);
                $m = ($length % 3600) / 3600;
                $length = $h + $m;
                break;

        }
        return $length;
    }

    /**
     *
     * @param $minutes
     * @param bool $humanize If False: 15 minutes will be as 0.25, If set to True: will be 0.15
     * @return float
     */
    static public function convertMinutesToHours($minutes,$humanize = false)
    {
        $h = floor($minutes/60);
        $i = 60;
        if ($humanize)
            $i = 100;
        $m = ($minutes % 60)/$i;
        return $h + $m;
    }

    /**
     * Check if 2 date ranges intersects
     * @param $date1start
     * @param $date1end
     * @param $date2start
     * @param $date2end
     * @return array|bool  false if no intersect, array if intersection found
     */
    static public function is2DateRangesIntersect($date1start, $date1end, $date2start, $date2end)
    {
        $date1start = strtotime($date1start);
        $date1end   = strtotime($date1end);
        $date2start = strtotime($date2start);
        $date2end   = strtotime($date2end);

        $sstart = max($date1start,$date2start);
        $send   = min($date1end,$date2end);

        if ($sstart <= $send){
            return array('start'=>$sstart,'end'=>$send);
        }else{
            return false;
        }

    }

    /**
     *
     * Check whether date2range is fully lay inside the date1range
     * It is important that $date2range should that range which you want to check
     *
     * @param $date1start
     * @param $date1end
     * @param $date2start
     * @param $date2end
     * @return bool
     */
    static public function isRange2FullyInRange1($date1start, $date1end, $date2start, $date2end)
    {
        $date1start = strtotime($date1start);
        $date1end   = strtotime($date1end);
        $date2start = strtotime($date2start);
        $date2end   = strtotime($date2end);

        if ($date1start <= $date2start && $date1end >= $date2end){
            return true;
        }else{
            return false;
        }

    }

    /**
     *
     * Check whether the provided string is valid date time string YYYY-MM-DD HH:ii:ss
     *
     * @param $date
     *
     * @return int
     */
    static public function isValidDateTimeStringFormat($date)
    {
        return preg_match("/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/", $date);
    }
}

