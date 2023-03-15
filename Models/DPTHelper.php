<?php
class DPTHelper
{
    /** @var  HijriDate */
    private $hijriDate;
    
    function __construct()
    {
        $this->hijriDate = new HijriDate();
        
    }

    function todayIsFriday()
    {
        return date_i18n('D') == 'Fri';
    }

    function tomorrowIsFriday()
    {
        return date_i18n('D') == 'Thu';
    }

    function isRamadan()
    {
        return $this->hijriDate->isRamadan();
    }

    function getIqamahClass($number)
    {
        if ( $number > 30 ) {
            return 'green';
        } elseif ( $number > 15 ) {
            return 'orange';
        }

        return 'red';
    }

    /**
     * dim display overnight between Isha and Fajr start
     */
    function canDimOvernight($dbRow, $disableOvernightDim=false)
    {
        if ($disableOvernightDim) {
            return 0;
        }

        $userTime = user_current_time( 'H:i');
        $now = new DateTime();
        $now->setTimestamp(strtotime($userTime));

        $isha = new DateTime();
        $isha->setTimestamp(strtotime($dbRow['isha_jamah']));
        $isha->modify('+30 mins');

        $fajr = new DateTime();
        $fajr->setTimestamp(strtotime($dbRow['fajr_begins']));

        if ($now < $fajr || $now > $isha) {
            return 1;
        }

        return 0;
    }

    /**
     * set khutbah dimming time on friday between sunrise and Asr 
     */
    function getKhutbahDim(array $dbRow): int
    {
        if (! $this->todayIsFriday()) {
            return 0;
        }

        $userTime = user_current_time( 'H:i');
        $now = new DateTime();
        $now->setTimestamp(strtotime($userTime));

        $sunrise = new DateTime();
        $sunrise->setTimestamp(strtotime($dbRow['sunrise']));

        $asr = new DateTime();
        $asr->setTimestamp(strtotime($dbRow['asr_begins']));

        if ($now > $sunrise && $now < $asr) {
            return (int)get_option('khutbahDim');

        }

        return 0;
    }

    /**
     * get diming time for taraweeh during ramadan after isha and before fajr
     */
    function getTaraweehDim(array $dbRow): int
    {
        if (! $this->isRamadan()) {
            return 0;
        }

        $userTime = user_current_time( 'H:i');
        $now = new DateTime();
        $now->setTimestamp(strtotime($userTime));

        $maghrib = new DateTime();
        $maghrib->setTimestamp(strtotime($dbRow['maghrib_jamah']));

        if ($now > $maghrib) {
            return (int)get_option('taraweehDim');

        }

        return 0;
    }

    /**
     * @param  string $mysqlDate
     * @param  string $format
     * @return string
     */
    function formatDate($mysqlDate, $format=null)
    {
        $phpDate = strtotime($mysqlDate);

        $date =  date( get_option('date_format'), $phpDate );
        if ($format) {
            $date = date($format, $phpDate);
        }

        return $date;
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

    protected function getNextPrayerClass($prayerName, $row, $isFajr=false)
    {
        $nextPrayerName = $this->getNextPrayer($row);
        if ($this->todayIsFriday() && $nextPrayerName == 'zuhr') {
            $nextPrayerName = 'jumuah';
        }

        if ($isFajr && is_null($nextPrayerName)) {
            return 'class="nextPrayer"';
        }

        if (strpos($nextPrayerName, $prayerName) !== false) {
            return 'class="nextPrayer"';
        }

        return '';
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

    	/**
     * @param array $row
     *
     * @return array
     */
    public function getJamahTime(array $row)
    {
        $row = $this->updateZuhrWithJummahTimes($row);

        $value = array( $row["fajr_jamah"], $row['sunrise'], $row["zuhr_jamah"], $row["asr_jamah"], $row["maghrib_jamah"], $row["isha_jamah"]);

        $prayerName = array(
            "fajr",
            "sunrise",
            "zuhr",
            "asr",
            "maghrib",
            "isha"
        );

        return array_combine( array_keys($prayerName), $value );

    }

        /**
     * if today is friday
     *  and now is before zuhr then set zuhr to jummah 1
     *  if now is > jummah1 and now is < jummah 3, then set zuhr to jummah2
     *  if now is > jummah2 and now is < asr, then set zuhr to jummah3
     * 
     * if tomorrow is friday, then set tomorrow zuhr to jummah1
     */
    public function updateZuhrWithJummahTimes(array $row)
    {
        // if($this->dptHelper->todayIsFriday()) {
            $userTime = user_current_time( 'H:i');
            $now = new DateTime();
            $now->setTimestamp(strtotime($userTime));
    
            $zuhr = new DateTime();
            $zuhr->setTimestamp(strtotime($row['zuhr_begins']));

            $asr = new DateTime();
            $asr->setTimestamp(strtotime($row['asr_jamah']));
            
            $jumuah1 = new DateTime();
            $jumuah1->setTimestamp(strtotime(get_option('jumuah1')));

            $jumuah2 = new DateTime();
            $jumuah2->setTimestamp(strtotime(get_option('jumuah2')));
            
            $jumuah3 = new DateTime();
            $jumuah3->setTimestamp(strtotime(get_option('jumuah3')));

            if ($now < $zuhr) {
                $row['zuhr_jamah'] = $jumuah1->format('H:i:s');
            } else if ($now > $jumuah1 && $now < $jumuah2) {
                $row['zuhr_jamah'] = $jumuah2->format('H:i:s');
            } else if ( $now > $jumuah2 && $now < $asr) {
                $row['zuhr_jamah'] = $jumuah3->format('H:i:s');
            }
        // }

        return $row;
    }
}