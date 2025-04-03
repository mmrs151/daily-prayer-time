<?php

require_once(__DIR__ . '/../Models/HijriDate.php');
require_once(__DIR__ . '/../Models/DPTHelper.php');

class TimetablePrinter
{
    protected $prayerLocal = array(
        "fajr" => "Fajr",
        "sunrise" => "Sunrise",
        "zuhr" => "Zuhr",
        "asr" => "Asr",
        "maghrib" => "Maghrib",
        "isha" => "Isha",
        "zawal" => "Zawal"
    );

    protected $headersLocal = array(
        "prayer" => "Prayer",
        "begins" => "Begins",
        "iqamah" => "Iqamah",
        "standard" => "Standard",
        "hanafi" => "Hanafi",
        "fast_begins" => "Suhoor End",
        "fast_ends" => "Iftar Start",
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
        'minute' => 'Minute',
        'minutes' => 'Minutes',
        'hour' => 'Hour',
        'hours' => 'Hours',
        'second' => 's',
        'iqamah update' => 'IQAMAH CHANGES:',
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

    /** @var DPTHelper */
    private $dptHelper;

    public function __construct()
    {
        $this->localPrayerNames = $this->getLocalPrayerNames();
        $this->localHeaders = $this->getLocalHeaders();
        $this->localNumbers = $this->getLocalNumbers();
        $this->localTimes = $this->getLocalTimes();
        $this->dptHelper = new DPTHelper();
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
        }

        if (is_array($localPrayerName)) {
            $localPrayerName = array_map( 'sanitize_text_field', $localPrayerName);
            $localPrayerName = array_map('stripslashes', $localPrayerName);
        }

        return $localPrayerName;
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

        if (is_array($headers_local)) {
            $headers_local = array_map( 'sanitize_text_field', $headers_local);
            $headers_local = array_map('stripslashes', $headers_local);
        }

        return $headers_local;
    }

    public function getLocalMonths()
    {
        $monthsLocal = get_option('monthsLocal');

        if ( empty( $monthsLocal )) {
            $monthsLocal = $this->monthsLocal;
        }

        if ( $this->isRamadan() ) {
            $monthsLocal['ramadan'] = 'Ramadan';
        } else {
            unset( $monthsLocal['ramadan'] );
        }

        if (is_array($monthsLocal)) {
            $monthsLocal = array_map( 'sanitize_text_field', $monthsLocal);
            $monthsLocal = array_map('stripslashes', $monthsLocal);
        }

        return $monthsLocal;
    }

    public function getLocalNumbers()
    {
        $numbers_local = get_option('numbersLocal');

        if (is_array($numbers_local)) {
            $numbers_local = array_map( 'sanitize_text_field', $numbers_local);
        }

        return empty($numbers_local) ? $this->numbersLocal : $numbers_local;
    }


    public function getLocalTimes()
    {
        $times = get_option('timesLocal');
        if (is_array($times)) {
            $times = array_map( 'sanitize_text_field', $times);
        }

        if ( empty($times)) {
            return $this->timesLocal;
        } elseif (count($times) != count($this->timesLocal)) {
            delete_option( 'timesLocal' );
            return $this->timesLocal;
        }

        return $times;
    }

    public function getLocalTimesKeys()
    {
            return array_keys($this->timesLocal);
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
        return $this->dptHelper->formatDate($mysqlDate, $format);
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

        foreach ($result as $char) {
            if (array_key_exists($char, $this->localNumbers)) {
                $intlDate .= $this->localNumbers[$char];
            } else {
                $intlDate .= $char;
            }
        }

        return $intlDate;
    }

    public function formatDateForPrayer24Hour($mysqlDate, $imsak=false)
    {
        $phpDate = strtotime($mysqlDate);
        if ($imsak) {
            $phpDate = $phpDate - ((int)get_option('imsaq') * 60);
        }
        return date('H:i', $phpDate);
    }

    public function getIntlNumber($numbers)
    {
        $intlDate = '';

        $this->localNumbers = $this->getLocalNumbers();
        $result = str_split($numbers);
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
    protected function getClass($month, $day, $weekday='')
    {
        if ($day == user_current_time('j') && $month == user_current_time('m')){
            return "class = highlight";
        } elseif($weekday=='Friday') {
            return "class = Fri";
        }
    }

    /**
     * @param  array $data
     * @param  bool $azanOnly
     * @return string|null
     */
    protected function getFastingTdWithData($data, $azanOnly=null, $imsaq=false)
    {
        $html = "";

        if  ($this->isRamadan() && ! $azanOnly && $imsaq) {
            $html = "<td class='fasting'>" . $this->formatDateForPrayer($data, $imsaq) . "</td>";
        } elseif ($this->isRamadan() && $imsaq) {
            $html = "<td class='fasting'>" . $this->formatDateForPrayer($data, $imsaq). "</td>";
            $html .= "<td>" . $this->formatDateForPrayer($data). "</td>";

        } elseif  ($this->isRamadan() && ! $imsaq) {
            $html = "<td class='fasting'>" . $this->formatDateForPrayer($data). "</td>";
        } elseif  ($this->isRamadan() && ! $azanOnly && $imsaq) {
            $html = "<td class='fasting'>" . $this->formatDateForPrayer($data). "</td>";
        } elseif ($azanOnly) {
            $html = "<td>" . $this->formatDateForPrayer($data). "</td>";
        }

        return $html;
    }

    /**
     * @param array $row
     * @param bool $displayDates
     * @param bool $apiCall
     * @return string
     */
    public function getNextIqamahTime(array $row, $displayDates=false, $apiCall=false)
    {
        $diff = $this->getNextIqamahTimeDiff($row);
        $nextPrayer = $this->getNextPrayer($row);

        if ($apiCall) {
            return [
                'prayerName' => $nextPrayer,
                'timeLeft' => $diff
            ];
        }

        $hijriDate = '';
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
        $timeLeftText = '';
        $minLeftText = '';
        if ($nextIqamah) {
            $timeLeftText = $this->getLocalizedNumber( $nextIqamah ) .':00';
            $minLeftText =  $this->localTimes["minute"];
            if ($nextIqamah > 60) {
                $hours = $nextIqamah / 60;
                $hours = (int)$hours;
                $mins = $nextIqamah % 60;
                $mins = (int)$mins;
                $timeLeftText = $this->getLocalizedNumber( $hours ) .' '.$this->localTimes["hour"] .' '
                    . $this->getLocalizedNumber( $mins ) .' '
                    .$this->localTimes["minute"];
            }

        }
        return $this->getNextPrayerTime($row, $nextIqamah, $timeLeftText, $minLeftText);
    }

    protected function getNextIqamahTimeDiff(array $row)
    {
        $jamahTime = $this->getJamahTime( $row );
        $now = current_time( 'H:i');
        foreach ($jamahTime as $key=>$jamah) {
            if ($jamah > $now ) {
                if ($key == 'Sunrise') {
                    $this->localPrayerNames[lcfirst($key)] . ':';
                }
                if ($key == 'Zuhr' && $this->isJumahDisplay($row)) {
                    $this->localPrayerNames[lcfirst($key)] . ':';
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
        return $this->dptHelper->getNextPrayer($row);
    }

    protected function getHeading($dbRow, $nextPrayer)
    {
        $iqamah = ($nextPrayer == 'sunrise') ? '' : $this->localHeaders['iqamah'];
        if ($nextPrayer == 'zawal') {
            return $this->localPrayerNames['zuhr'].' '. $iqamah;
        }
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

        if ($nextPrayer == 'zawal') {
            $nextPrayer = 'zuhr';
        }

        $key = ($nextPrayer == 'sunrise') ? $nextPrayer : strtolower($nextPrayer.'_jamah');

        if (isset($dbRow[$key])) {
            $nextPrayerTime = $dbRow[$key];
        }

        if (is_null($nextPrayer)) {
            $nextPrayerTime = $dbRow['nextFajr'];
        }
        $nextPrayerTime24Hours = $this->formatDateForPrayer24Hour($nextPrayerTime);

        return
            '
            <input type="hidden" id="timezoneOffset" value="' . get_option('gmt_offset') .'">
            <h2 id="dptScTimeCountDown" style="display: none">' . $nextPrayerTime24Hours. '</h2>
            <h2 class="dptScTime">
            <input type="hidden" value="' . htmlentities($this->getTimesLocalJson()) . '" id="localizedNumbers">
            <input type="hidden" value="' . htmlentities($this->getTimesLocalJson()) . '" id="localizedTimes">    ' .
            $this->formatDateForPrayer($nextPrayerTime). '
            </h2>
            <span class="timeLeftCountDown timeLeft '.$this->getIqamahClass( $nextIqamah ).'">
                '.  $timeLeftText .'
            </span>
        </div>';
    }

	/**
     * @param array $row
     *
     * @return array
     */
    protected function getJamahTime(array $row)
    {
        $jamahTimes = array( $row["fajr_jamah"], $row['sunrise'], $row["zuhr_jamah"], $row["asr_jamah"], $row["maghrib_jamah"], $row["isha_jamah"]);
        $jamahNames = array_keys($this->prayerLocal);
        array_pop($jamahNames); // remove zawal
        return array_combine( $jamahNames, $jamahTimes );

    }

    /**
     * @param array $row
     *
     * @return array
     */
    protected function getAzanTime(array $row)
    {
        $jamahTimes = array( $row["fajr_begins"], $row['sunrise'], $row["zuhr_begins"], $row["asr_begins"], $row["maghrib_begins"], $row["isha_begins"]);
        $jamahNames = array_keys($this->prayerLocal);
        array_pop($jamahNames); // remove zawal
        return array_combine( $jamahNames, $jamahTimes );
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
        return $this->dptHelper->getIqamahClass($number);
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
            $timeClass = "class='x-time-change bg-dark'";
            $digitalScreenClass = "jamahChanges-" . $orientation;

        }

        $timeRelated = $this->getLocalTimes();

        $print =
            "<span class='jamahChanges " . $digitalScreenClass . "'>
                <span class='x-time-text'>"
                    . stripslashes($timeRelated['iqamah update']) .
                "</span>";

        $prayerNames = $this->getLocalPrayerNames();

        foreach($row['jamah_changes'] as $key=>$time) {
            if (! empty($key) ){
                $prayer = explode('_', $key);
                if ( $this->tomorrowIsFriday() ) {
                    $prayerNames['zuhr'] = $this->getLocalHeaders()['jumuah'];
                }
                $print .= "<span " . $style . $timeClass ." >" . $prayerNames[$prayer[0]] . ": " .  $this->getTimeForIqamahUpdate($prayerNames[$prayer[0]], $time) . "</span>";
            }
        }
        $print .= "</span>";

        return $print;
    }

    private function getTimeForIqamahUpdate($key, $time)
    {
        $jumuahTime = get_option('jumuah1');
        if ( $key === $this->getLocalHeaders()['jumuah'] && $jumuahTime) {
            return $jumuahTime;
        }
        return $this->formatDateForPrayer($time);
    }

    protected function todayIsFriday()
    {
        return $this->dptHelper->todayIsFriday();
    }

    protected function tomorrowIsFriday()
    {
        return $this->dptHelper->tomorrowIsFriday();
    }

    public function getHijriDate($day, $month, $year, $dbRow, $forMonth=false)
    {
        return $this->dptHelper->getHijriDate($day, $month, $year, $dbRow, $forMonth);
    }

    public function isJumahDisplay($dbRow)
    {
        return $this->dptHelper->isJumahDisplay($dbRow);
    }

    private function isSunset($dbRow, $forMonth=false)
    {
        return $this->dptHelper->isSunset($dbRow, $forMonth);
    }

    /**
     * dim display overnight between Isha and Fajr start
     */
    protected function canDimOvernight($dbRow, $disableOvernightDim=false)
    {
        return $this->dptHelper->canDimOvernight($dbRow, $disableOvernightDim);
    }

    /**
     * set khutbah dimming time on friday between sunrise and Asr
     */
    protected function getKhutbahDim(array $dbRow): int
    {
        return $this->dptHelper->getKhutbahDim($dbRow);
    }

    /**
     * get diming time for taraweeh during ramadan after isha and before fajr
     */
    protected function getTaraweehDim(array $dbRow): int
    {
        return $this->dptHelper->getTaraweehDim($dbRow);
    }

    protected function getNextPrayerClass($prayerName, $row, $isFajr=false)
    {
        return $this->dptHelper->getNextPrayerClass($prayerName, $row, $isFajr);
    }

    public function isRamadan()
    {
        return $this->dptHelper->isRamadan();
    }

    public function getJumuahTimesArray($isVertical = false, $separator = " | ")
    {
        $class = 'dsJumuah';
        if ($isVertical) {
            $class = 'dsJumuah-vertical';
        }
        $jumuahText = [];
        $jumuahArray = [get_option('jumuah1'), get_option('jumuah2'), get_option('jumuah3')];
        $jumuahArray = array_filter($jumuahArray);
        foreach ($jumuahArray as $jumuah) {
            $jumuahText[] = '<span class="' . $class . '">' . $this->formatDateForPrayer($jumuah) . '</span>';
        }
        return implode($separator, $jumuahText);
    }

    public function getTimesLocalJson(): string
    {
        return
        json_encode([
            'minute' => $this->getLocalTimes()['minute'] ?? 'minute',
            'minutes' => $this->getLocalTimes()['minutes'],
            'hour' => $this->getLocalTimes()['hour'] ?? 'hour',
            'hours' => $this->getLocalTimes()['hours'],
            'second' => $this->getLocalTimes()['second'],
        ],
            JSON_UNESCAPED_UNICODE);
    }

    public function getNumbersLocalJson(): string
    {
        $localNumbers = $this->getLocalNumbers();
        $localNumbers = array_combine(array_keys($localNumbers), $localNumbers);
        return json_encode($localNumbers, JSON_UNESCAPED_UNICODE);
    }
}
