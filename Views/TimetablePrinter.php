<?php

require_once(__DIR__ . '/../Models/HijriDate.php');

class TimetablePrinter
{
    protected $prayerLocal = array(
        "fajr" => "Fajr",
        "sunrise" => "Sunrise",
        "zuhr" => "Zuhr",
        "asr" => "Asr",
        "maghrib" => "Maghrib",
        "isha" => "Isha"
    );

    protected $headersLocal = array(
        "prayer" => "Prayer",
        "begins" => "Begins",
        "iqamah" => "Iqamah",
        "standard" => "Standard",
        "hanafi" => "Hanafi",
        "fast_begins" => "Fast Begins",
        "fast_ends" => "Fast Ends",
        "jumuah" => "Jumuah"
    );

    protected $numbersLocal = array(
        0 => '0',
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
        9 => '9'
    );

    protected $monthsLocal = array(
        'january' => 'January',
        'february' => 'February',
        'march' => 'March',
        'april' => 'April',
        'may' => 'May',
        'june' => 'June',
        'july' => 'July',
        'august' => 'August',
        'september' => 'September',
        'october' => 'October',
        'november' => 'November',
        'december' => 'December'
    );


    /** @var array  */
    protected $timesLocal = array(
        'date' => 'Date',
        'day' => 'Day',
        'minute' => 'Minutes',
        'hours' => 'Hours',
        'iqamah update' => 'IQAMAH UPDATE FOR TOMORROW',
        'next prayer' => 'Next ...'
    );

    /** @var string */
    protected $tableClass;

    /** @var array  */
    protected $localPrayerNames;

    /** @var array  */
    protected $localHeaders;

    /** @var array  */
    protected $localNumbers;

    /** @var  array */
    protected $localTimes;

    /** @var  HijriDate */
    protected $hijriDate;

    /** @var string  */
    protected $hijridateString;

    /** $var bool */
    protected $isVertical = false;

    /** $var bool */
    protected $isHorizontal = false;

    /**
     * TimetablePrinter constructor.
     */
    public function __construct()
    {
        $this->localPrayerNames = $this->getLocalPrayerNames();
        $this->localHeaders = $this->getLocalHeaders();
        $this->localNumbers = $this->getLocalNumbers();
        $this->localTimes = $this->getLocalTimes();

        $this->hijriDate = new HijriDate();
        $this->hijridateString = $this->hijriDate->getDate(date("d"), date("m"), date("Y"), true);
    }

    public function getTableClass()
    {
        return get_option('hideTableBorder');
    }

    public function getLocalPrayerNames($forAdmin=false, $enableJumuah=false)
    {
        $prayers_local = get_option('prayersLocal');

        $localPrayerName =  $prayers_local;
        if ( empty($prayers_local)) {
            $localPrayerName =  $this->prayerLocal;
        } elseif (count($prayers_local) != count($this->prayerLocal)) {
            delete_option( 'prayersLocal' );
            $localPrayerName = $this->prayerLocal;
        }

        if (! $forAdmin && $enableJumuah){
            if ($this->todayIsFriday()) {
                $localPrayerName['zuhr'] = $this->getLocalHeaders()['jumuah'];
            }
        }

        $localPrayerName = array_map( 'sanitize_text_field', $localPrayerName);
        return array_map('stripslashes', $localPrayerName);
    }

    public function getLocalHeaders()
    {
        $headers_local = get_option('headersLocal');

        if ( empty($headers_local)) {
            return $this->headersLocal;
        } elseif (count($headers_local) != count($this->headersLocal)) {
            delete_option( 'headersLocal' );
            return $this->headersLocal;
        }

        $headers_local = array_map( 'sanitize_text_field', $headers_local);

        return array_map('stripslashes', $headers_local);
    }

    public function getLocalMonths()
    {
        $monthsLocal = get_option('monthsLocal');

        if ( empty( $monthsLocal )) {
            $monthsLocal = $this->monthsLocal;
        }

        if ( get_option("ramadan-chbox") ) {
            $monthsLocal['ramadan'] = 'Ramadan';
        } else {
            unset( $monthsLocal['ramadan'] );
        }

        $monthsLocal = array_map( 'sanitize_text_field', $monthsLocal);

        return array_map('stripslashes', $monthsLocal);
    }

    public function getLocalNumbers()
    {
        $numbers_local = get_option('numbersLocal');
        $numbers_local = array_map( 'sanitize_text_field', $numbers_local);

        return empty($numbers_local) ? $this->numbersLocal : $numbers_local;
    }


    public function getLocalTimes()
    {
        $times = get_option('timesLocal');
        $times = array_map( 'sanitize_text_field', $times);

        if ( empty($times)) {
            return $this->timesLocal;
        } elseif (count($times) != count($this->timesLocal)) {
            delete_option( 'timesLocal' );
            return $this->timesLocal;
        }

        return $times;
    }

    public function setVertical($value=true)
    {
        $this->isVertical = $value;
    }

    public function setHorizontal($value=true)
    {
        $this->isHorizontal = $value;
    }

    /**
     * @param  string $mysqlDate
     * @param  string $format
     * @return string
     */
    public function formatDate($mysqlDate, $format=null)
    {
        $phpDate = strtotime($mysqlDate);

        $date =  date( get_option('date_format'), $phpDate );
        if ($format) {
            $date = date($format, $phpDate);
        }

        return $date;
    }

    /**
     * @param  string $mysqlDate
     * @return string
     */
    public function formatDateForPrayer($mysqlDate, $imsak=false)
    {
        $phpDate = strtotime($mysqlDate);
        if ($imsak) {
            $phpDate = $phpDate - ((int)get_option('imsaq') * 60);
        }
        $wpDate = date(get_option('time_format'), $phpDate);
    
        $result = str_split($wpDate);
        $intlDate = '';
        $this->localNumbers = $this->getLocalNumbers();
        foreach ($result as $number) {
            $intlDate .= $this->localNumbers[$number];
            if (empty($this->localNumbers[$number]) && $number !== '0') {
                $intlDate .= $number;
            }
        }
    
        return $intlDate;
    }

    /**
     * @param  string $month
     * @param  string $day
     * @return string
     */
    protected function getClass($month, $day)
    {
        if ($day == user_current_time('j') && $month == user_current_time('m')){
            return "class = highlight";
        }
    }

    /**
     * @param  bool $isRamadan
     * @param  array $data
     * @param  bool $azanOnly
     * @return string|null
     */
    protected function getFastingTdWithData($isRamadan, $data, $azanOnly=null, $imsaq=false)
    {
        $html = "";

        if  ($isRamadan && ! $azanOnly && $imsaq) {
            $html = "<td class='fasting'>" . $this->formatDateForPrayer($data, $imsaq) . "</td>";
        } elseif ($isRamadan && $imsaq) {
            $html = "<td class='fasting'>" . $this->formatDateForPrayer($data, $imsaq). "</td>";
            $html .= "<td>" . $this->formatDateForPrayer($data). "</td>";

        } elseif  ($isRamadan && ! $imsaq) {
            $html = "<td class='fasting'>" . $this->formatDateForPrayer($data). "</td>";
        } elseif  ($isRamadan && ! $azanOnly && $imsaq) {
            $html = "<td class='fasting'>" . $this->formatDateForPrayer($data). "</td>";
        } elseif ($azanOnly) {
            $html = "<td>" . $this->formatDateForPrayer($data). "</td>";
        }

        return $html;
    }
    
    /**
     * @param array $row
     * @param bool $displayDates
     * @return string
     */
    protected function getNextIqamahTime(array $row, $displayDates=false)
    {
        $diff = $this->getNextIqamahTimeDiff($row);
        $nextPrayer = $this->getNextPrayer($row);
        if($row['displayHijriDate']) {
            $hijriDate = $this->hijriDate->getDate(date("d"), date("m"), date("Y"), true);
        }

        $printDates = '';
        if ($displayDates) {
            $printDates = '<div>
        <span class="scDate">
        ' . date_i18n( get_option( 'date_format' ) ) . '
        </span>
        <span class="scHijri">
            ' . $hijriDate . '
        </span>';
        }

        return
            $printDates . '
        <div class="dptScNextPrayer">
            <span class="green">
                <span class="nextPrayer">' . $this->getHeading($row, $nextPrayer). '</span> ' .
                $this->getTimeLeftString($diff, $row);
    }

    protected function getTimeLeftString($nextIqamah, $row)
    {
        if ($nextIqamah) {
            $timeLeftText = $this->getLocalizedNumber( $nextIqamah ) .':00';
            $minLeftText =  $this->localTimes["minute"];
            if ($nextIqamah > 60) {
                $hours = $nextIqamah / 60;
                $hours = (int)$hours;
                $mins = $nextIqamah % 60;
                $mins = (int)$mins;
                $timeLeftText = $this->getLocalizedNumber( $hours ) .' '.$this->localTimes["hours"] .' '. $this->getLocalizedNumber( $mins );
            }

        }
        return $this->getNextPrayerTime($row, $nextIqamah, $timeLeftText, $minLeftText);
    }

    protected function getNextIqamahTimeDiff(array $row)
    {
        $jamahTime = $this->getJamahTime( $row );
        $now = current_time( 'H:i');
        foreach ($jamahTime as $key=>$jamah) {
            $this->nextIqamah = $this->localPrayerNames[lcfirst($key)] . ' ' . $this->localHeaders['iqamah'] . ':';
            if ($jamah >$now ) {
                if ($key == 'Sunrise') {
                    $this->nextIqamah = $this->localPrayerNames[lcfirst($key)] . ':';
                }
                if ($key == 'Zuhr' && $this->isJumahDisplay($row)) {
                    $this->nextIqamah = $this->localPrayerNames[lcfirst($key)] . ':';
                }
                $toTime = strtotime( $jamah );
                $fromTime = strtotime( $now );
                $diff = round(abs($toTime - $fromTime)/60,2);

                return $diff;
            }
        }
    }

	/**
     * @param $row
     *
     * @return string
     */
    protected function getNextPrayer($row)
    {
        $now = current_time( 'H:i');

        $jamahTime = $this->getJamahTime( $row );
        foreach ($jamahTime as $jamah) {
            if ($jamah > $now ) {
                $prayer = array_search( $jamah, $row ); // asr_jamah or asr_begins
                $prayer = explode( '_', $prayer);
                return $prayer[0]; // asr
            }
        }
    }

    protected function getHeading($dbRow, $nextPrayer)
    {
        $iqamah = ($nextPrayer == 'sunrise') ? '' : $this->localHeaders['iqamah'];
        if ( is_null($nextPrayer)) {
            return $this->localPrayerNames['fajr'].' '. $iqamah;
        }
        if ( $this->isJumahDisplay($dbRow) ) {
            return $this->getLocalHeaders()['jumuah'];
        }

        return $this->localPrayerNames[$nextPrayer] .' '. $iqamah;
    }

    protected function getNextPrayerTime($dbRow, $nextIqamah, $timeLeftText, $minLeftText)
    {
        $nextPrayer = $this->getNextPrayer($dbRow);

        $key = ($nextPrayer == 'sunrise') ? $nextPrayer : strtolower($nextPrayer.'_jamah');
        $nextPrayerName = $dbRow[$key];
        if ( is_null($nextPrayer) ) {
            $nextPrayerName = $dbRow['nextFajr'];
        }

        if ( $this->isJumahDisplay($dbRow) ) {
            return '<p class="jumuah">' . get_option('jumuah') . '</span>';
        } else {
            return
                '<h2 class="dptScTime">' .
                $this->formatDateForPrayer($nextPrayerName). '
                </h2> 
                <span class="timeLeftCountDown timeLeft '.$this->getIqamahClass( $nextIqamah ).'"> 
                    '.  $timeLeftText .' 
                </span><span class="minLeftText"> ' . $minLeftText .'</span>
        </div>';
        }
    }


	/**
     * @param array $row
     *
     * @return array
     */
    protected function getJamahTime(array $row)
    {
        $value = array( $row["fajr_jamah"], $row['sunrise'], $row["zuhr_jamah"], $row["asr_jamah"], $row["maghrib_jamah"], $row["isha_jamah"]);

        return array_combine( array_keys($this->prayerLocal), $value );

    }

    /**
     * @param array $row
     *
     * @return array
     */
    protected function getAzanTime(array $row)
    {
        $value = array( $row["fajr_begins"], $row['sunrise'], $row["zuhr_begins"], $row["asr_begins"], $row["maghrib_begins"], $row["isha_begins"]);

        return array_combine( array_keys($this->prayerLocal), $value );
    }

    /**
     * @param $numbers
     *
     * @return string
     */
    public function getLocalizedNumber($numbers)
    {
        $numbers = str_split( $numbers );
        $localNumber = "";
        foreach ($numbers as $number) {
            $localNumber .= $this->localNumbers[$number];
        }

        return $localNumber;
    }

    /**
     * @param $number
     *
     * @return string
     */
    protected function getIqamahClass($number)
    {
        if ( $number > 30 ) {
            return 'green';
        } elseif ( $number > 15 ) {
            return 'orange';
        }

        return 'red';
    }

    protected function getJamahChange(array $row, $isDigitalScreen=false, $orientation="")
    {
        $style = null;
        if ($this->isVertical && ! $isDigitalScreen) {
            $style = "style='display: block;'";
        }
        $timeClass = "";
        $digitalScreenClass = "";
        if ($isDigitalScreen) {
            $timeClass = "class='x-time-change'";
            $digitalScreenClass = "jamahChanges-" . $orientation;

        }
        $timeRelated = $this->getLocalTimes();
        $print = "<div class='jamahChanges " . $digitalScreenClass . "'>
            <span class='x-time-text'>" . stripslashes($timeRelated['iqamah update']) . "</span>";
        $prayerNames = $this->getLocalPrayerNames();

        foreach($row['jamah_changes'] as $key=>$time) {
            if (! empty($key) ){
                $prayer = explode('_', $key);
                if ( $this->tomorrowIsFriday() ) {
                    $prayerNames['zuhr'] = $this->getLocalHeaders()['jumuah'];
                } else {
                    $prayerNames['zuhr'] = $this->prayerLocal['zuhr'];
                }
                $print .= "<span " . $style . $timeClass ." >" . $prayerNames[$prayer[0]] . ": " .  $this->getTimeForIqamahUpdate($prayerNames[$prayer[0]], $time) . "</span>";
            }
        }
        $print .= "</div>";

        return $print;
    }

    private function getTimeForIqamahUpdate($key, $time)
    {
        $jumuahTime = get_option('jumuah');
        if ( $key === $this->getLocalHeaders()['jumuah'] && $jumuahTime) {
            return $jumuahTime;
        }
        return $this->formatDateForPrayer($time);
    }

    protected function todayIsFriday()
    {
        return date_i18n('D') == 'Fri';
    }

    protected function tomorrowIsFriday()
    {
        return date_i18n('D') == 'Thu';
    }

    /**
     * @param $day
     * @param $month
     * @param $year
     * @param array $dbRow
     * @param bool $forMonth
     * @return string
     */
    public function getHijriDate($day, $month, $year, $dbRow, $forMonth=false)
    {
        $hijriCheckbox = get_option('hijri-chbox');
        if ( ! empty($hijriCheckbox) ) {
            if( ! empty($dbRow['hijri_date']) ) {
                $hijriDate = $dbRow['hijri_date'];
            } else {
                $hijridateArray = $this->hijriDate->getDate($day, $month, $year, false, $this->isSunset($dbRow, $forMonth));
                $hijriDate = $hijridateArray['day']. ' '. $hijridateArray['month'] . ' ' . $hijridateArray['year'];
            }

            return '<p class="hijriDate"> '. $hijriDate .'</p>';
        }
        return;
    }

    public function isJumahDisplay($dbRow)
    {
        if ( date_i18n('D') == 'Fri' &&
            current_time('timestamp') > strtotime($dbRow['sunrise']) &&
            current_time('timestamp') <= strtotime($dbRow['zuhr_jamah']) + 60
        ) {
            return true;
        }

        return false;
    }

    private function isSunset($dbRow, $forMonth=false)
    {
        return  !$forMonth && current_time('timestamp') > strtotime($dbRow['maghrib_begins']);
    }
}