<?php
require_once('db.php');
require_once(__DIR__.'/../Views/DailyTimetablePrinter.php');
require_once(__DIR__.'/../Views/TimetablePrinter.php' );
require_once (__DIR__ .'/QuranADay/VersePrinter.php');



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

    public function __construct()
    {
        $this->db = new DatabaseConnection();
        $this->row = $this->db->getPrayerTimeForToday();
        $this->timetablePrinter = new DailyTimetablePrinter();
        $this->deactivateTomorrow = get_option('tomorrow_time');
        parent::__construct();
    }

    public function setDeactivateTomorrow() 
    {
        $this->deactivateTomorrow = true;
    }

    public function setAnnouncement($text, $day)
    {
        $this->row['announcement'] =  $this->getAnnouncement( $text, $day);

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
        $this->isHanafiAsr = true;
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
    public function getAnnouncement($widgetNotice="", $day)
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

        $result = "<span class='dpt_jamah " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($jamah) . "</span>";
        if ( isset($attr['start_time']) ) {
            $result = "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>" . $result;
        }
        return $result;
    }

    public function scFajrStart($attr)
    {
        $begins = $this->row['fajr_begins'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($begins) ) {
            $begins = $this->row['tomorrow']['fajr_begins'];
            }
        }

        return "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>";
    }

    public function scSunrise($attr)
    {
        $sunrise = $this->row['sunrise'];

        if (! $this->deactivateTomorrow) {
        
            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($sunrise) ) {
                $sunrise = $this->row['tomorrow']['sunrise'];
            }
        }

        return "<span class='dpt_sunrise " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($sunrise) . "</span>";
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

        $result = "<span class='dpt_jamah " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($jamah) . "</span>";
        
        if ( isset($attr['start_time']) ) {
            $result = "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>" . $result;
        }
        
        return $result;
    }

    public function scZuhrStart($attr)
    {
        $begins = $this->row['zuhr_begins'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($begins) ) {
                $begins = $this->row['tomorrow']['zuhr_begins'];
            }
        }

        return "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>";
    }

    public function scAsr($attr)
    {
        $jamah = $this->row['asr_jamah'];
        $begins = $this->row['asr_mithl_1'];

        
        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($jamah) ) {
                $jamah = $this->row['tomorrow']['asr_jamah'];
                $begins = $this->row['tomorrow']['asr_mithl_1'];
            }
        }

        $result = "<span class='dpt_jamah " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($jamah) . "</span>";

        if ( isset($attr['start_time']) ) {
            $result = "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>" . $result;
        }

        return $result;
    }

    public function scAsrStart($attr)
    {
        $begins = $this->row['asr_mithl_1'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($begins) ) {
                $begins = $this->row['tomorrow']['asr_mithl_1'];
            }
        }

        return "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>";
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

        $result = "<span class='dpt_jamah " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($jamah) . "</span>";
        
        if ( isset($attr['start_time']) ) {
            $result = "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>" . $result;
        }
        
        return $result;
    }

    public function scMaghribStart($attr)
    {
        $begins = $this->row['maghrib_begins'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($begins) ) {
                $begins = $this->row['tomorrow']['maghrib_begins'];
            }
        }

        return "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>";
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

        $result = "<span class='dpt_jamah " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($jamah) . "</span>";

        if ( isset($attr['start_time']) ) {
            $result = "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>" . $result;
        }

        return $result;
    }

    public function scIshaStart($attr)
    {
        $begins = $this->row['isha_begins'];

        if (! $this->deactivateTomorrow) {

            if ( isset($this->row['tomorrow']) && $this->isPrayerFinished($begins) ) {
                $begins = $this->row['tomorrow']['isha_begins'];
            }
        }

        return "<span class='dpt_start " . $this->clsPrayerFinished . "'>" . $this->formatDateForPrayer($begins) . "</span>";
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

        $orientation =  isset($attr['orientation']) ? $attr['orientation'] : '';
        
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

    protected function getRow($attr=array())
    {

        $this->setDisplayForShortCode($attr);

        if (isset($attr['asr'])) {
            $this->setHanafiAsr();
        }

        if (isset($attr['heading'])) {
            $this->setTitle($attr['heading']);
        }

        $row = $this->row;

        if (isset($attr['announcement'])) {
            $day = isset($attr['day']) ? $attr['day'] : 'everyday';
            $row['announcement'] = $this->getAnnouncement($attr['announcement'], $day);
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
            } elseif ( $attr['display'] === 'azan_only' ) {
                $this->setAzanOnly();
            }
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