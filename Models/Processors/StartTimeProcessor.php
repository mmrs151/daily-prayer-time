<?php

require_once(__DIR__. '/../StartTime/PrayTime.php');
require_once(__DIR__. '/../db.php');


class StartTimeProcessor
{
    /** @var DatabaseConnection */
    private $db;
    
    /** @var string[]  */
    private $prayerNames = array('fajr', 'sunrise', 'zuhr', 'asr', 'sunset', 'maghrib', 'isha' );
    
    /** @var PrayTime */
    private $prayTime;
    
    /** @var array */
    private $data;
    
    /** @var string */
    private $dbTable;
    
    public function __construct(array $data)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . "timetable_cities";
        $this->dbTable = "`".DB_NAME ."`.`" .$tableName."`";
    
        $this->db = new DatabaseConnection();
        $this->data = $data;
        $this->prayTime = new PrayTime();
    }
    
    public function process()
    {
        set_transient('nearest_city', $this->data['city']);
        
        $calcMethod = $this->data['method'];
        $asrMethod = $this->data['asr-method'];
        $latLong = $this->getLatLong($this->data['city']);
        $timeZone =  get_option('gmt_offset');
        
        $this->prayTime->setCalcMethod($calcMethod);
        $this->prayTime->setAsrMethod($asrMethod);
    
        $year = date('Y');
        $date = strtotime($year. '-1-1');
        $endDate = strtotime(($year+ 1). '-1-1');
        
        while ($date < $endDate)
        {
            $day = date('Y-m-d', $date);
            $times = $this->prayTime->getPrayerTimes($date, $latLong['lat'], $latLong['lng'], $timeZone);
            $times = array_combine($this->prayerNames, $times);
            $date += 24* 60* 60;  // next day
    
            $row = array (
                'd_date' => $day,
                'fajr_begins' => $times['fajr'],
                'fajr_jamah' => $this->getJamahTime($day, $times['fajr'], $this->data['fajr-delay']),
                'sunrise' => $times['sunrise'],
                'zuhr_begins' => $times['zuhr'],
                'zuhr_jamah' => $this->getJamahTime($day, $times['zuhr'], $this->data['zuhr-delay']),
                'asr_mithl_1' => $times['asr'],
                'asr_mithl_2' => $times['asr'],
                'asr_jamah' => $this->getJamahTime($day, $times['asr'], $this->data['asr-delay']),
                'maghrib_begins' => $times['maghrib'],
                'maghrib_jamah' => $this->getJamahTime($day, $times['maghrib'], $this->data['maghrib-delay']),
                'isha_begins' => $times['isha'],
                'isha_jamah' => $this->getJamahTime($day, $times['isha'], $this->data['isha-delay']),
                'is_ramadan' => 0,
                'hijri_date' => 0
            );
            $this->db->insertRow($row);
        }
    }
    
    private function getJamahTime($date, $time, $mins)
    {
        $mins = empty($mins) ? 3 : $mins;
        $dt = new DateTime($date . ' ' . $time);
        $dt->add(new DateInterval('PT' . $mins . 'M'));
    
        return $dt->format('H:i');
    }
    
    private function getLatLong($cityId) // LONDON default
    {
        global $wpdb;
        $sql = "SELECT lat, lng FROM  $this->dbTable WHERE id = " . $cityId;
        $row = $wpdb->get_row($sql, ARRAY_A);
        return array(
            'lat' => $row['lat'],
            'lng' => $row['lng']
        );
        
    }
    
}
