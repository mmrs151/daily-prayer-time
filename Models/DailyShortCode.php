<?php
require_once('db.php');
require_once(__DIR__ .'/../Views/DailyTimetablePrinter.php');
require_once(__DIR__ .'/../Views/TimetablePrinter.php' );
require_once(__DIR__ .'/QuranADay/VersePrinter.php');
require_once(__DIR__ .'/DPTHelper.php');

class DailyShortCode extends TimetablePrinter
{
    /** @var boolean */
    private $isJamahOnly = false;

    /** @var boolean */
    private $isAzanOnly = false;

    /** @var boolean */
    private $isHanafiAsr = false;

    /** @var array */
    protected $row = array();

    /** @var  DailyTimetablePrinter */
    private $timetablePrinter;

    /** @var  string */
    private $title;

    /** @var  bool */
    private $hideRamadan = false;

    /** @var bool  */
    private $hideTimeRemaining = false;

    /** @var bool  */
    private $displayHijriDate = false;

    /** $var db */
    protected $db;

    /** $var bool */
    protected $deactivateTomorrow = false;

    /** @var bool */
    protected $clsPrayerFinished = '';

    /** @var DPTHelper */
    protected $dptHelper;

    public function __construct()
    {
        $this->db = new DatabaseConnection();
        $this->row = $this->db->getPrayerTimeForToday();
        $this->timetablePrinter = new DailyTimetablePrinter();
        $this->deactivateTomorrow = get_option('tomorrow_time');
        $this->dptHelper = new DPTHelper();

        parent::__construct();
    }

    public function setDeactivateTomorrow() 
    {
        $this->deactivateTomorrow = true;
    }

    public function setAnnouncement($text, $day)
    {
        $this->row['announcement'] =  $this->getAnnouncement($day, $text);
    }

    public function setJamahOnly()
    {
        $this->isJamahOnly = true;
    }

    public function setAzanOnly()
    {
        $this->isAzanOnly = true;
    }

    public function setHanafiAsr()
    {
        if ($this->getAsrMethod() == 'asr_mithl_2') {
            $this->isHanafiAsr = true;
        }
    }

    public function hideRamadan()
    {
        $this->hideRamadan = true;
    }

    public function hideTimeRemaining()
    {
        $this->hideTimeRemaining = true;
    }

    public function displayHijriDate()
    {
        $this->displayHijriDate = true;
    }

    /**
     * @param  array  $attr
     * @return string
     */
    public function verticalTime($attr=array())
    {
        $this->timetablePrinter->setVertical(true);

        $row = $this->getRow($attr);

        if ($this->isJamahOnly) {
            return $this->timetablePrinter->verticalTimeJamahOnly($row);
        }

        if ($this->isAzanOnly) {
            return $this->timetablePrinter->verticalTimeAzanOnly($row);
        }

        return $this->timetablePrinter->printVerticalTime($row);
    }

    /**
     * @param  array  $attr
     * @return string
     */
    public function horizontalTime($attr=array())
    {
        $this->timetablePrinter->setHorizontal(true);

        $row = $this->getRow($attr);

        if ($this->isJamahOnly) {
            return $this->timetablePrinter->horizontalTimeJamahOnly($row);
        }

        if ($this->isAzanOnly) {
            return $this->timetablePrinter->horizontalTimeAzanOnly($row);
        }

        if (isset($attr['use_div_layout'])) {
            return $this->timetablePrinter->horizontalTimeDiv($row);
        }

        return $this->timetablePrinter->printHorizontalTime($row);
    }

        /**
     * @param  string $widgetNotice
     * @return string
     */
    public function getAnnouncement($day, $widgetNotice="")
    {
        $widgetNotice = trim( $widgetNotice );
        $day = trim($day);

        if (empty($widgetNotice)) {
            return "";
        }

        $today = date('l');
        $announcement = "";
        $exploded = explode(PHP_EOL, $widgetNotice);
        foreach($exploded as $line) {
            $announcement .= $line . "</br>";
        }
        if ( $today == ucfirst( $day ) || $day == 'everyday' ) {
            return trim($announcement);
        }
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function scNextPrayer($attr)
    {
        $row = $this->getRow($attr);
        $row['displayHijriDate'] = $this->displayHijriDate;
        if (isset($attr['display_dates'])) {
            return $this->getNextIqamahTime($row, true);
        }
        return $this->getNextIqamahTime($row);
    }


    public function scRamadanTime($attr)
    {
        $row = $this->getRow($attr);

        return $this->timetablePrinter->displayRamadanTime($row);
    }

    public function scFajr($attr)
    {
        $jamah = $this->row['fajr_jamah'];
        $begins = $this->row['fajr_begins'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($jamah) ) {
                $jamah = $this->row['tomorrow']['fajr_jamah'];
                $begins = $this->row['tomorrow']['fajr_begins'];
            }
        }

        $result = "<span class='scFajr " . $this->dptHelper->getNextPrayerClass('fajr', $this->row, true) ."'>";

        $start = '';
        $jamah = "<span class='dpt_jamah " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($jamah) . "</span>";
        if ( isset($attr['start_time']) ) {
            $start = "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>";
        }

        return $result . $start . $jamah . "</span>";
    }

    public function scFajrStart($attr)
    {
        $begins = $this->row['fajr_begins'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($begins) ) {
            $begins = $this->row['tomorrow']['fajr_begins'];
            }
        }

        return "<span class='dpt_start " . $this->clsPrayerFinished . " " . $this->dptHelper->getNextPrayerClass('fajr', $this->row, true) ."'>" . $this->formatDateForPrayer($begins) . "</span>";
    }

    public function scSunrise($attr)
    {
        $sunrise = $this->row['sunrise'];

        if (! $this->deactivateTomorrow) {
        
            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($sunrise) ) {
                $sunrise = $this->row['tomorrow']['sunrise'];
            }
        }

        return "<span class='dpt_sunrise " . $this->clsPrayerFinished . " ". $this->getNextPrayerClass('sunrise', $this->row) ."'>" . $this->formatDateForPrayer($sunrise) . "</span>";
    }

    public function scZuhr($attr)
    {
        $jamah = $this->row['zuhr_jamah'];
        $begins = $this->row['zuhr_begins'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($jamah) ) {
                $jamah = $this->row['tomorrow']['zuhr_jamah'];
                $begins = $this->row['tomorrow']['zuhr_begins'];
            }
        }

        $result = "<span class='scZuhr " . $this->dptHelper->getNextPrayerClass('zuhr', $this->row) ."'>";
        $start = '';
        $jamah = "<span class='dpt_jamah " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($jamah) . "</span>";
        
        if ( isset($attr['start_time']) ) {
            $start = "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>";
        }
        
        return $result . $start . $jamah . "</span>";

    }

    public function scZuhrStart($attr)
    {
        $begins = $this->row['zuhr_begins'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($begins) ) {
                $begins = $this->row['tomorrow']['zuhr_begins'];
            }
        }

        return "<span class='dpt_start " . $this->clsPrayerFinished . " " . $this->dptHelper->getNextPrayerClass('zuhr', $this->row) ."'>" . $this->formatDateForPrayer($begins) . "</span>";
    }

    public function scAsr($attr)
    {
        $method = $this->getAsrMethod($attr);
        $jamah = $this->row['asr_jamah'];
        $begins = $this->row[$method];

        
        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($jamah) ) {
                $jamah = $this->row['tomorrow']['asr_jamah'];
                $begins = $this->row['tomorrow'][$method];
            }
        }

        $result = "<span class='scAsr " . $this->dptHelper->getNextPrayerClass('asr', $this->row) ."'>";
        $start = '';
        $jamah = "<span class='dpt_jamah " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($jamah) . "</span>";

        if ( isset($attr['start_time']) ) {
            $start = "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>";
        }

        return $result . $start . $jamah . "</span>";
    }

    public function scAsrStart($attr)
    {
        $method = $this->getAsrMethod($attr);
        
        $begins = $this->row[$method];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($begins) ) {
                $begins = $this->row['tomorrow'][$method];
            }
        }

        return "<span class='dpt_start " . $this->clsPrayerFinished . " " . $this->dptHelper->getNextPrayerClass('asr', $this->row) ."'>" . $this->formatDateForPrayer($begins) . "</span>";
    }

    private function getAsrMethod($attr=array()) 
    {
        if (isset($attr['asr'])) {
            return 'asr_mithl_2';
        }
        return get_option('asrSelect') == 'hanafi' ? 'asr_mithl_2' : 'asr_mithl_1';

    }

    public function scMaghrib($attr)
    {
        $jamah = $this->row['maghrib_jamah'];
        $begins = $this->row['maghrib_begins'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($jamah) ) {
                $jamah = $this->row['tomorrow']['maghrib_jamah'];
                $begins = $this->row['tomorrow']['maghrib_begins'];
            }
        }

        $result = "<span class='scMaghrib " . $this->dptHelper->getNextPrayerClass('maghrib', $this->row) ."'>";
        $start = '';
        $jamah = "<span class='dpt_jamah " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($jamah) . "</span>";
        
        if ( isset($attr['start_time']) ) {
            $start = "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>";
        }
        
        return $result . $start . $jamah . "</span>";
    }

    public function scMaghribStart($attr)
    {
        $begins = $this->row['maghrib_begins'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($begins) ) {
                $begins = $this->row['tomorrow']['maghrib_begins'];
            }
        }

        return "<span class='dpt_start " . $this->clsPrayerFinished . " " . $this->dptHelper->getNextPrayerClass('maghrib', $this->row) ."'>" . $this->formatDateForPrayer($begins) . "</span>";
    }

    public function scIsha($attr)
    {
        $jamah = $this->row['isha_jamah'];
        $begins = $this->row['isha_begins'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($jamah) ) {
                $jamah = $this->row['tomorrow']['isha_jamah'];
                $begins = $this->row['tomorrow']['isha_begins'];
            }
        }

        $result = "<span class='scIsha " . $this->dptHelper->getNextPrayerClass('isha', $this->row) ."'>";
        $start = '';
        $jamah = "<span class='dpt_jamah " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($jamah) . "</span>";

        if ( isset($attr['start_time']) ) {
            $start = "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>";
        }

        return $result . $start . $jamah . "</span>";
    }

    public function scIshaStart($attr)
    {
        $begins = $this->row['isha_begins'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($begins) ) {
                $begins = $this->row['tomorrow']['isha_begins'];
            }
        }

        return "<span class='dpt_start " . $this->clsPrayerFinished . " " . $this->dptHelper->getNextPrayerClass('isha', $this->row) ."'>" . $this->formatDateForPrayer($begins) . "</span>";
    }

    public function scJummahPrayer($attr)
    {
        return "
        <span class='jummahShortcode'>
            <span class='jummahHeading'>" 
                . stripslashes($this->getLocalHeaders()['jumuah']) . "
            </span>
            <span class='jummahPrayer'>" 
                . $this->getJumuahTimesArray() . "
            </span>
        </span>";
    }

    public function scZawal($attr)
    {
        $zuhrBegins = $this->row['zuhr_begins'];
        if (isset($attr['zuhr_begins'])) {
            $zuhrBegins = $attr['zuhr_begins'];
        }
        $zawal = $this->dptHelper->getZawalTime($zuhrBegins);

        if (! $this->deactivateTomorrow) {
        
            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($zuhrBegins) ) {
                $zuhrBegins = $this->row['tomorrow']['zuhr_begins'];

                $zawal = $this->dptHelper->getZawalTime($zuhrBegins);

            }
        }

        return "<span class='dpt_sunrise " 
                    . $this->clsPrayerFinished . " " 
                    . $this->dptHelper->getNextPrayerClass('zawal', $this->row) ."'>" 
                    . $this->formatDateForPrayer($zawal) 
                . "</span>";
    }

    private function isPrayerFinished($time) 
    {
        $userTime = user_current_time( 'H:i');
        $now = new DateTime();
        $now->setTimestamp(strtotime($userTime));

        $prayerTime = new DateTime();
        $prayerTime->setTimestamp(strtotime($time));

        if ($now > $prayerTime) {
            $this->clsPrayerFinished = 'prayerFinished';
            return true;
        }

        $this->clsPrayerFinished = '';
        return false;
    }
    
    public function scIqamahUpdate($attr)
    {
        $min = isset($attr['threshold']) ? (int) $attr['threshold'] : 1;
        
        $row['jamah_changes'] = $this->db->getJamahChanges($min);

        if (empty($row['jamah_changes'])) { return; }

        $orientation =  isset($attr['orientation']) ? esc_attr($attr['orientation']) : '';
        
        return $this->getJamahChange($row, true, $orientation);
    }

    public function scDigitalScreen($attr)
    {
        $ds =  new DigitalScreen($attr);

        return $ds->displayDigitalScreen();
    }

    public function scQuranVarse($attr)
    {
        $verse = new VersePrinter();
        
        return $verse->printVerse($attr);
    }

    public function scHijriDate($attr)
    {
        return "<span class='scHijriDate'>" . $this->hijriDate->getToday() . "</span>";        
    }

    protected function getRow($attr=array())
    {
        if (!$this->isAzanOnly && !$this->isJamahOnly) {
            $this->setDisplayForShortCode($attr);
        }

        $this->isHanafiAsr = isset($attr['asr']) ? true : $this->setHanafiAsr();

        if (isset($attr['heading'])) {
            $this->setTitle(esc_attr($attr['heading']));
        }

        $row = $this->row;

        if (isset($attr['announcement'])) {
            $day = isset($attr['day']) ? esc_attr($attr['day']) : 'everyday';
            $row['announcement'] = $this->getAnnouncement($day, esc_attr($attr['announcement']));
        }

        if ( $row['jamah_changes']) {
            $row['announcement'] .= $this->timetablePrinter->getJamahChange($this->row);
        }

        $row['widgetTitle'] = $this->title;
        $row['asr_begins'] = $this->isHanafiAsr ? $this->row['asr_mithl_2'] : $this->row['asr_mithl_1'];

        $row['hideRamadan'] = $this->hideRamadan;
        $row['hideTimeRemaining'] = $this->hideTimeRemaining;
        $row['displayHijriDate'] = $this->displayHijriDate;
        $row['nextFajr'] = $this->db->getFajrJamahForTomorrow();

        return $row;
    }

    /**
     * @param array $attr
     */
    private function setDisplayForShortCode($attr)
    {
        if (isset($attr['display'])) {
            if ( $attr['display'] === 'iqamah_only' ) {
                $this->setJamahOnly();
                $this->isAzanOnly = false;
            } elseif ( $attr['display'] === 'azan_only' ) {
                $this->setAzanOnly();
                $this->isJamahOnly = false;
            }
        } else {
            $this->isJamahOnly = false;
            $this->isAzanOnly = false;
        }

        if (isset($attr['hide_time_remaining'])) {
            $this->hideTimeRemaining();
        }

        if (isset($attr['hide_ramadan'])) {
            $this->hideRamadan();
        }

        $hijriCheckbox = get_option('hijri-chbox');
        if (! empty($hijriCheckbox)) {
            $this->displayHijriDate();
        }
    }

    public function getFajrBegins()
    {
        $begins = $this->row['fajr_begins'];
        if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($begins) ) {
            $begins = $this->row['tomorrow']['fajr_begins'];
        }
        return $begins;
    }
}