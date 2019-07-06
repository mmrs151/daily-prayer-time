<?php

class HijriDate
{
    private $hijriMonth = array ("Muharram", "Safar", "Rabī al-Awwal", "Rabī ath-Thānī ", "Jumādā al-Ula", "Jumādā ath-Thāniya", "Rajab", "Sha'ban", "Ramadan", "Shawwal", "Dhū al-Qa'da", "Dhū al-Hijjah");

    /**
     * @param $day
     * @param $month
     * @param $year
     * @param bool $stringDate
     * @param bool $sunset
     *
     * @return array|string
     */
    public function getDate($day, $month, $year, $stringDate=false, $sunset=false)
    {
        if ($sunset == true) {
            $dt = new DateTime('tomorrow');
            $tomorrow = $dt->format('d/m/Y');
            $parts = explode('/', $tomorrow);
            $day = $parts[0];
            $month = $parts[1];
            $year = $parts[2];
        }

        $date = $this->greg2Hijri($day, $month, $year);

        $day = $date['day'];
        $month = $this->hijriMonth[(int) $date['month'] - 1];
        $year = $date['year'];

        if ($stringDate) {
            return "{$month} {$day}, {$year}";
        }

        return array(
            'day' => $day,
            'month' => $month,
            'year' => $year
        );
    }

    /**
     * @param int $day
     * @param int $month
     * @param int $year
     *
     * @return array|string
     */
    private function greg2Hijri($day, $month, $year)
    {
        $day   = (int) $day + (int) get_option('hijri-adjust');
        $month = (int) $month;
        $year  = (int) $year;


        if (($year > 1582) or (($year == 1582) and ($month > 10)) or (($year == 1582) and ($month == 10) and ($day > 14)))
        {
            $jd = $this->intPart((1461*($year+4800+$this->intPart(($month-14)/12)))/4)+$this->intPart((367*($month-2-12*($this->intPart(($month-14)/12))))/12)-
                  $this->intPart( (3* ($this->intPart(  ($year+4900+    $this->intPart( ($month-14)/12)     )/100)    )   ) /4)+$day-32075;
        }
        else
        {
            $jd = 367*$year-$this->intPart((7*($year+5001+$this->intPart(($month-9)/7)))/4)+$this->intPart((275*$month)/9)+$day+1729777;
        }


        $l = $jd-1948440+10632;
        $n = $this->intPart(($l-1)/10631);
        $l = $l-10631*$n+354;
        $j = ($this->intPart((10985-$l)/5316))*($this->intPart((50*$l)/17719))+($this->intPart($l/5670))*($this->intPart((43*$l)/15238));
        $l = $l-($this->intPart((30-$j)/15))*($this->intPart((17719*$j)/50))-($this->intPart($j/16))*($this->intPart((15238*$j)/43))+29;

        $month = $this->intPart((24*$l)/709);
        $day   = $l-$this->intPart((709*$month)/24);
        $year  = 30*$n+$j-30;

        return array(
            'day' => $day,
            'month' => $month,
            'year' => $year
        );
    }

    /**
     * @param float $float
     *
     * @return float
     */
    private function intPart($float)
    {
        if ($float < -0.0000001)
            return ceil($float - 0.0000001);
        else
            return floor($float + 0.0000001);

    }
}
