<?php
use IslamicNetwork\PrayerTimes\PrayerTimes;
use IslamicNetwork\PrayerTimes\Method as PrayerTimesMethod;
require_once(__DIR__. '/../db.php');

if ( ! class_exists('DPTStartTimeProcessor')) {
    class DPTStartTimeProcessor
    {
        /** @var DatabaseConnection */
        private $db;
        
        /** @var string[]  */
        private $prayerNames = array('fajr', 'sunrise', 'zuhr', 'asr', 'sunset', 'maghrib', 'isha' );

        private PrayerTimes $islamicNetworkPrayerTimes;
        
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
        }
        
        public function process()
        {
            set_transient('nearest_city', $this->data['city']);
            $calcMethod = $this->data['method'];
            $asrMethod = $this->data['asr-method'];
            $latLong = $this->getLatLong($this->data['city']);
            $higherLatMethod = (int)$this->data['higher-lat'];

            $this->islamicNetworkPrayerTimes = new PrayerTimes($calcMethod);
            if ($calcMethod == 23) { // custom settings
                $method = new PrayerTimesMethod('My Custom Method');
                $fajrAngle = (int)$this->data['fajr-angle'];
                $ishaAngle = (int)$this->data['isha-angle'];

                $method->setFajrAngle((int)$this->data['fajr-angle']);
                $method->setIshaAngleOrMins((int)$this->data['isha-angle']);

                $this->islamicNetworkPrayerTimes->setMethod($method);


                delete_option('fajr-angle');
                delete_option('isha-angle');

                add_option('fajr-angle', $fajrAngle);
                add_option('isha-angle', $ishaAngle);
            }
            $this->islamicNetworkPrayerTimes->setAsrJuristicMethod($asrMethod);
            $this->islamicNetworkPrayerTimes->setSchool($asrMethod);
            $this->islamicNetworkPrayerTimes->setLatitudeAdjustmentMethod($higherLatMethod);


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

            $year = date('Y');
            $date = new DateTime($year . '-1-1');
            $endDate = new DateTime(($year + 1) . '-1-1');
            
            while ($date < $endDate)
            {
                $day = date('Y-m-d');
                $times = $this->islamicNetworkPrayerTimes->getTimes($date, $latLong['lat'], $latLong['lng']);
                $this->setTimezone($date);
                $times = [
                    'fajr' => $times['Fajr'],
                    'sunrise' => $times['Sunrise'],
                    'zuhr' => $times['Dhuhr'],
                    'asr' => $times['Asr'],
                    'sunset' => $times['Sunset'],
                    'maghrib' => $times['Maghrib'],
                    'isha' => $times['Isha']
                ];

                $times = array_combine($this->prayerNames, $times);

                $row = array (
                    'd_date' => $date->format('Y-m-d'),
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
                $date->modify('+1 day'); // next day
            }
        }

        private function setTimezone(DateTime $date): bool
        {
            $timezone_string = get_option('timezone_string');

            if ($timezone_string) {
                $timezone = new DateTimeZone($timezone_string);
            } else {
                // Fallback to the offset if timezone string is not set
                $offset = get_option('gmt_offset');
                $timezone = timezone_name_from_abbr('', $offset * 3600, 0);
                if ($timezone === false) {
                    $timezone = 'UTC';
                }
                $timezone = new DateTimeZone($timezone);
            }
            $dateTime = $date->setTimezone($timezone);

            return $timezone->getTransitions($dateTime->getTimestamp(), $dateTime->getTimestamp())[0]['isdst'];
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
