<?php

require_once(__DIR__. '/db.php');
require_once (__DIR__ . '/StartTime/WorldCities.php');

class Init
{
    /**
     * A reference to an instance of this class.
     */
    private static $instance;

    /**
     * Returns an instance of this class.
     */
    public static function get_instance() {

        if ( null == self::$instance ) {
            self::$instance = new Init();
        }

        return self::$instance;
    }

    /** @var DatabaseConnection */
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseConnection();
        $this->importSampleCsv();
        $cities = new WorldCities();
        $cities->importCities();
    }

    private function importSampleCsv()
    {
        foreach ($this->getYearlyData() as $dateInYear) {
            $row = array (
                'd_date' => $dateInYear,
                'fajr_begins' => '00:00',
                'fajr_jamah' => '00:00',
                'sunrise' => '00:00',
                'zuhr_begins' => '00:00',
                'zuhr_jamah' => '00:00',
                'asr_mithl_1' => '00:00',
                'asr_mithl_2' => '00:00',
                'asr_jamah' => '00:00',
                'maghrib_begins' => '00:00',
                'maghrib_jamah' => '00:00',
                'isha_begins' => '00:00',
                'isha_jamah' => '00:00',
                'is_ramadan' => 0,
                'hijri_date' => 0
            );
            $this->db->insertRow($row);
        }
        add_option('dpt-init', '1');
    }

    private function getYearlyData()
    {
        $year = date('Y');
        
        $range = array();
        $start = strtotime($year.'-01-01');
        $end = strtotime($year.'-12-31');

        do {
            $range[] = date('Y-m-d',$start);
            $start = strtotime("+ 1 day",$start);
        } while ( $start <= $end );

        return $range;
    }
}