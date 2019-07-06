<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

class DatabaseConnection
{
    /** @var string  */
    private $dbTable = "";

    /** @var string */
    private $tableName = "";

    /** @var array */
    private $transients = array('prayerTimeForToday', 'fajrJamahForTomorrow', 'jamahChanges', 'fajrForTomorrow');


    public function __construct()
    {
        global $wpdb;

        $this->tableName = $wpdb->prefix . "timetable";
        $this->dbTable = "`".DB_NAME ."`.`" .$this->tableName."`";

        $this->createTableIfNotExist();
    }

    /**
     * @return array
     */
    public function getPrayerTimeForToday()
    {
        if (false === ( $result = $this->getTransient( 'prayerTimeForToday' )) ) {
            error_log('prayer time for today');

            global $wpdb;

            $today = user_current_time( 'Y-m-d' );
            $sql = "SELECT * FROM  $this->dbTable WHERE d_date = '$today' LIMIT 1";
            $result = $wpdb->get_row($sql, ARRAY_A);
            $result['jamah_changes'] = $this->getJamahChanges();

            set_transient('prayerTimeForToday', $result,DAY_IN_SECONDS );
        }

        return $result;
    }

    public function getIqamahTimeForToday()
    {
        error_log('iqamah time for today');
        $result = $this->getPrayerTimeForToday();
        return array($result['fajr_jamah'], $result['sunrise'], $result['zuhr_jamah'], $result['asr_jamah'], $result['maghrib_jamah'], $result['isha_jamah']);
    }



    /**
     * @return array
     */
    public function getFajrJamahForTomorrow()
    {
        if (false === ( $result = $this->getTransient( 'fajrForTomorrow' )) ) {
            error_log('fajr for tomorrow');
            global $wpdb;

            $sql = "SELECT fajr_jamah FROM  $this->dbTable WHERE d_date =  CURDATE()  + INTERVAL 1 DAY;";
            $row = $wpdb->get_row($sql, ARRAY_A);
            $result = $row['fajr_jamah'];

            set_transient('fajrForTomorrow', $result,DAY_IN_SECONDS );
        }

        return $result;
    }

    /**
     * @param int $min
     * @return array
     */
    public function getJamahChanges($min=null)
    {
        error_log('jamah chnages');
        $xmin = get_option( 'jamah_changes' );
        $xmin = empty($min) ? $xmin : $min;

        if ( $xmin < 1) {
            return;
        }

        global $wpdb;

        $sql = "SELECT
                abs(TIME_TO_SEC(TIMEDIFF(today.fajr_jamah, tomorrow.fajr_jamah)) / 60) as fajr_jamah,
                abs(TIME_TO_SEC(TIMEDIFF(today.zuhr_jamah, tomorrow.zuhr_jamah)) /60) as zuhr_jamah,
                abs(TIME_TO_SEC(TIMEDIFF(today.asr_jamah, tomorrow.asr_jamah)) /60) as asr_jamah,
                abs(TIME_TO_SEC(TIMEDIFF(today.maghrib_jamah, tomorrow.maghrib_jamah)) /60) as maghrib_jamah,
                abs(TIME_TO_SEC(TIMEDIFF(today.isha_jamah, tomorrow.isha_jamah)) /60) as isha_jamah
            FROM $this->dbTable  today
            INNER JOIN $this->dbTable tomorrow
            ON today.d_date = tomorrow.d_date + INTERVAL 1 DAY
            WHERE today.d_date = CURDATE() + INTERVAL 1 DAY;";

        $result = $wpdb->get_row($sql, ARRAY_A);

        if ( empty($result) ) {
            return null;
        }

        // get jamah name that has changes more than x min
        $jamahNamesArray = array();
        foreach($result as $key=>$time) {
            if ((int)$time >= (int)$xmin) {
                $diff = (int)$time - (int)$xmin;
                $jamahNamesArray[$key] = $diff;
            }
        }

        $jamahNamesString = implode(",", array_keys($jamahNamesArray));

        if (empty($jamahNamesString)) {
            return null;
        }

        $sql = "SELECT " . $jamahNamesString . "
            FROM $this->dbTable
            WHERE d_date = CURDATE() + INTERVAL 1 DAY;";

        $result = $wpdb->get_row($sql, ARRAY_A);

        return $result;
    }

    /**
     * @param int $monthNumber
     * @return array
     */
    public function getPrayerTimeForMonth($monthNumber)
    {
        global $wpdb;

        $sql = "SELECT * FROM  $this->dbTable WHERE month(d_date) = $monthNumber AND YEAR(d_date) = YEAR(CURDATE()) ORDER BY d_date ASC";
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
    }

    /**
     * @return array
     */
    public function getPrayerTimeForRamadan()
    {
        global $wpdb;

        $sql = "SELECT * FROM  $this->dbTable WHERE is_ramadan = 1 AND YEAR(d_date) = YEAR(CURDATE()) ORDER BY d_date ASC";
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
    }

    /**
     * @param array $row
     * @return int|bool
     */
    public function insertRow($row)
    {
        $this->deleteTransients();

        global $wpdb;

        $createIfNotUpdate = "INSERT INTO " .$this->dbTable. " VALUES (";

        foreach ($row as $key => $value) {
            $createIfNotUpdate .= "'" .$value. "',";
        }
        $createIfNotUpdate = rtrim($createIfNotUpdate, ', ');

        $createIfNotUpdate .= " ) ON DUPLICATE KEY UPDATE ";
        foreach ($row as $key => $value) {
            $createIfNotUpdate .= $key. "='" .$value. "',";
        }
        $createIfNotUpdate = rtrim($createIfNotUpdate, ', ');
        $createIfNotUpdate .= ';';

        return $wpdb->query($createIfNotUpdate);
    }

    private function createTableIfNotExist()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE " . $this->dbTable. "(
                d_date date NOT NULL,
                fajr_begins time DEFAULT '00:00',
                fajr_jamah time DEFAULT '00:00',
                sunrise time DEFAULT '00:00',
                zuhr_begins time DEFAULT '00:00',
                zuhr_jamah time DEFAULT '00:00',
                asr_mithl_1 time DEFAULT '00:00',
                asr_mithl_2 time DEFAULT '00:00',
                asr_jamah time DEFAULT '00:00',
                maghrib_begins time DEFAULT '00:00',
                maghrib_jamah time DEFAULT '00:00',
                isha_begins time DEFAULT '00:00',
                isha_jamah time DEFAULT '00:00',
                is_ramadan SMALLINT DEFAULT NULL,
                hijri_date VARCHAR(256) DEFAULT NULL,
                PRIMARY KEY  (d_date)
                ) $charset_collate;";

        $wpdb->get_var("SHOW TABLES LIKE '". $this->tableName . "'");
        if($wpdb->num_rows != 1) {
            dbDelta( $sql );
        }
    }

    public function updateRow($monthData)
    {
        $this->deleteTransients();

        global $wpdb;

        foreach ($monthData as $day) {
            $wpdb->update(
                $this->tableName,
                array(
                    'fajr_jamah' => $day['fajr_jamah'],
                    'zuhr_jamah' => $day['zuhr_jamah'],
                    'asr_jamah' => $day['asr_jamah'],
                    'maghrib_jamah' => $day['maghrib_jamah'],
                    'isha_jamah' => $day['isha_jamah']
                ),
                array('d_date' => $day['d_date'])
            );
        }
    }

    public function getRows()
    {
        error_log('get rows');

        global $wpdb;
        $sql = "SELECT * FROM " . $this->dbTable;
        $result = $wpdb->get_results($sql, ARRAY_A);
        return $result;
    }

    private function getTransient($transientName)
    {
        if (date_i18n( 'g:ia' ) === '12:00am') {
            delete_transient($transientName);
        }

        return get_transient($transientName);
    }

    private function deleteTransients()
    {
        foreach( $this->transients as $transientName ) {
            delete_transient($transientName);
        }
    }
}


function user_current_time($format="")
{
    $format = $format ? $format : 'mysql';
    $result = current_time($format);
    if (empty($result)) {
        $result =  date( $format, time() + ( get_option( 'gmt_offset' ) * 60 ) );
    }

    return $result;
}
