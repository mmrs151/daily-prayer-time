<?php
    class WorldCities
    {
        private $citiesFile;
        
        private $dbTable;
        
        public function __construct()
        {
            global $wpdb;
    
            $this->citiesFile = plugin_dir_path(__FILE__) . '../../Assets/world-cities.csv';
            $tableName = $wpdb->prefix . "timetable_cities";
            $this->dbTable = "`".DB_NAME ."`.`" .$tableName."`";
            
            $this->createTable();
        }
        
        public function importCities()
        {
            global $wpdb;
    
            $file = fopen($this->citiesFile, "r");
            $columns = $row = fgetcsv($file, 10000, ",");
            $sqlInsert = "INSERT INTO " . $this->dbTable. " (" . implode(',', $columns) . ") VALUES ";
            $values = "";
            while (($row = fgetcsv($file, 10000, ",")) !== FALSE)
            {
                $values .= "('". implode("','", $this->getEscapedRow($row)) . "'),";
            }
            $values = rtrim($values, ",");
            $sqlInsert .= $values . ';';
            $wpdb->query($sqlInsert);
            
            fclose($file);
        }
        
        private function getEscapedRow($row)
        {
            $escapedRow = [];
            foreach ($row as $item) {
                $escapedRow[] = esc_sql($item);
                
            }
            return $escapedRow;
        }
        
        private function createTable()
        {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            
            $sql = "CREATE TABLE IF NOT EXISTS " . $this->dbTable. " (
                id INT NOT NULL AUTO_INCREMENT,
                city VARCHAR(64) NULL,
                lat VARCHAR(64) NULL,
                lng VARCHAR(64) NULL,
                country VARCHAR(64) NULL,
                PRIMARY KEY  (id)
                ) $charset_collate;";
    
            $wpdb->get_var("SHOW TABLES LIKE '". $this->tableName . "'");
            if($wpdb->num_rows != 1) {
                dbDelta( $sql );
            }
        }
        
        public function getCities()
        {
            global $wpdb;
            $sql = "SELECT * FROM " . $this->dbTable . " ORDER BY country, city ASC";
            return $wpdb->get_results($sql, ARRAY_A);
        }
    }