<?php

require_once(__DIR__. '/../StartTime/PrayTime.php');
require_once(__DIR__. '/../db.php');

if ( ! class_exists('DPTStartTimeProcessor')) {
    class DPTStartTimeProcessor
    {
        /** @var DatabaseConnection */
        private $db;
        
        /** @var string[]  */
        private $prayerNames = array('fajr', 'sunrise', 'zuhr', 'asr', 'sunset', 'maghrib', 'isha' );
        
        /** @var DPTPrayTime */
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
            if (is_array($data)) {
                $this->data = array_map( 'sanitize_text_field', $data);
            }
            $this->prayTime = new DPTPrayTime();
        }
        
        public function process()
        {
            set_transient('nearest_city', $this->data['city']);
            $calcMethod = $this->data['method'];
            $asrMethod = $this->data['asr-method'];
            $latLong = $this->getLatLong($this->data['city']);
            $timeZone =  get_option('gmt_offset');
            $higherLatMethod = (int)$this->data['higher-lat'];
            
            if ($calcMethod == 6) { // custom settings
                $fajrAngle = (int)$this->data['fajr-angle'];
                $ishaAngle = (int)$this->data['isha-angle'];
                $this->prayTime->setFajrAngle($fajrAngle);
                $this->prayTime->setIshaAngle($ishaAngle);

                delete_option('fajr-angle');
                delete_option('isha-angle');

                add_option('fajr-angle', $fajrAngle);
                add_option('isha-angle', $ishaAngle);
    
            }
            delete_option('fajr-delay');
            delete_option('zuhr-delay');
            delete_option('asr-delay');
            delete_option('maghrib-delay');
            delete_option('isha-delay');
            delete_option('higher-lat');
            delete_option('calc-method');
            delete_option('asr-method');
            
            add_option('fajr-delay', $this->data['fajr-delay']);
            add_option('zuhr-delay', $this->data['zuhr-delay']);
            add_option('asr-delay', $this->data['asr-delay']);
            add_option('maghrib-delay', $this->data['maghrib-delay']);
            add_option('isha-delay', $this->data['isha-delay']);
            add_option('higher-lat', $higherLatMethod);
            add_option('calc-method', $calcMethod);
            add_option('asr-method', $asrMethod);
            
            $this->prayTime->setCalcMethod($calcMethod);
            $this->prayTime->setAsrMethod($asrMethod);
            $this->prayTime->setHighLatsMethod($higherLatMethod);
            
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
            try{
                $dt = new DateTime($date . ' ' . $time);
                $dt->add(new DateInterval('PT' . $mins . 'M'));
            
                return $dt->format('H:i');
            } catch(\Exception $e) {
                error_log('!Failed to Set Prayer Times Automatically - : ' . $e->getMessage());
            }
            return '';
        }
        
        private function getLatLong($cityId)
        {
            $cityId = sanitize_text_field( $cityId );
            $cityId = empty($cityId) ? 26 : $cityId; // DEFAULT LONDON
            
            global $wpdb;
            $sql = "SELECT lat, lng FROM  $this->dbTable WHERE id = " . $cityId;
            $row = $wpdb->get_row($sql, ARRAY_A);
            
            return array(
                'lat' => $row['lat'],
                'lng' => $row['lng']
            );
        }
        
    }
    
}
