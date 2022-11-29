<?php
require_once "Hijri/hijri.class.php";

class HijriDate
{
    private $isRamadan = false;

    private $hijriMonth = array ("Muharram", "Safar", "Rabī al-Awwal", "Rabī ath-Thānī", "Jumādā al-Ula", "Jumādā ath-Thāniya", "Rajab", "Sha'ban", "Ramadan", "Shawwal", "Dhū al-Qa'da", "Dhū al-Hijjah");
    
    private $arabicMonth = array (
        "Muharram" => "ٱلْمُحَرَّم",  
        "Safar" => "صَفَر",  
        "Rabī al-Awwal" => "رَبِيع ٱلْأَوَّل",
        "Rabī ath-Thānī" => "رَبِيع ٱلثَّانِي",
        "Jumādā al-Ula" => "جُمَادَىٰ ٱلْأُولَىٰ",
        "Jumādā ath-Thāniya" => "جُمَادَىٰ ٱلثَّانِيَة",
        "Rajab" => "رَجَب",
        "Sha'ban" => "شَعْبَان",
        "Ramadan" => "رَمَضَان",
        "Shawwal" => "شَوَّال",
        "Dhū al-Qa'da" => "ذُو ٱلْقَعْدَة",
        "Dhū al-Hijja" => "ذُو ٱلْحِجَّة"
    );
    
    private $arabicNumbers = [
        0 => '٠',
        1 => '١',
        2 => '٢',
        3 => '٣',
        4 => '٤',
        5 => '٥',
        6 => '٦',
        7 => '٧',
        8 => '٨',
        9 => '٩'
    ];

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

        $isUmmulQura = get_option('hijri-ummul-qura');
        $date = $this->greg2Hijri($day, $month, $year);
        if ($isUmmulQura) {
            $date = $this->getUmmulQuraDate($day, $month, $year);
        }

        $day = $date['day'];
        $month = $this->hijriMonth[(int) $date['month'] - 1];
        $year = $date['year'];

        $this->isRamadan = strpos($month, 'Ramadan');

        if ( get_option('hijri-arabic-chbox') ) {
            $day = $this->getArabicNumber($day);
            $month = $this->getArabicMonth($month);
            $year = $this->getArabicNumber($year);
        }

        if ($stringDate) {
            return "{$day} {$month} {$year}";
        }

        return array(
            'day' => $day,
            'month' => $month,
            'year' => $year
        );
    }

    public function getToday() {
        return $this->getDate(date("d"), date("m"), date("Y"), true, false);
    }

    public function isRamadan()
    {
        return $this->isRamadan OR get_option('ramadan_chbox');
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

    private function getArabicNumber($number)
    {
        $arabicNumber = '';
        $numbers = str_split($number);
        foreach($numbers as $key) {
            $arabicNumber .= $this->arabicNumbers[$key];
        }

        return $arabicNumber;
    }

    private function getArabicMonth($month)
    {
        return $this->arabicMonth[$month];
    }

    private function getUmmulQuraDate(int $day, int $month, int $year)
    {
        $day = $day + (int) get_option('hijri-adjust');

        $settings['umalqura'] = false; //get from settings 
        $d = new dpt\Calendar($settings);

        $hijri = $d->GregorianToHijri($year, $month,$day);

        return array(
            'day' => $hijri["d"],
            'month' => $hijri["m"],
            'year' => $hijri['y']
        );
    }
}
