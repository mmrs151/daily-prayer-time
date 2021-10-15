<?php

require_once ABSPATH.'wp-admin/includes/upgrade.php';
require_once 'QuranRandomizer.php';

class DPTQuranDB
{
    /** @var string */
    private $dbTable = '';

    /** @var string */
    private $tableName = '';

    public function __construct()
    {
        global $wpdb;

        $this->rand = new QuranRandomizer();

        $this->tableName = $wpdb->prefix.'dptquranADay';
        $this->dbTable = "`".DB_NAME ."`.`" .$this->tableName."`";
    }

    public function createTableIfNotExist()
    {
        global $wpdb;

        $sql = 'CREATE TABLE IF NOT EXISTS '.$this->dbTable."(
                  id int(11) NOT NULL AUTO_INCREMENT,
                  lang varchar(255) DEFAULT NULL,
                  sura tinyint(4) DEFAULT NULL,
                  ayat int(8) DEFAULT NULL,
                  text text,
                  PRIMARY KEY (id)
                ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;";

        $wpdb->get_var("SHOW TABLES LIKE '".$this->tableName."'");

        if ($wpdb->num_rows != 1) {
            dbDelta($sql);
            $this->importCsv('english');
            $this->importCsv('bangla');
        }
    }

    private function importCsv($lang)
    {
        global $wpdb;

        $path = plugin_dir_path(__FILE__).'files/'.$lang.'.csv';

        if (($handle = fopen($path, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, '|')) !== false) {
                $sql = 'INSERT INTO '.$this->tableName."  (lang, sura, ayat, text) values ('".$lang."', '".$data[0]."', '".$data[1]."', '".esc_sql($data[2])."');";
                if (!empty($data[2])) {
                    $wpdb->query($sql);
                }
            }
        } else {
            echo 'no file';
        }
    }

    /**
     * @param string $lang
     *
     * @return array
     */
    public function getQuote($attr=array(), $lang = 'english')
    {
        $minWord = isset($attr['min_word']) ? $attr['min_word'] : 11;
        $maxWord = isset($attr['max_word']) ? $attr['max_word'] : 20;
        $lang = isset($attr['language']) ? $attr['language'] : 'english';

        $sql = 'SELECT *, (length(text)-LENGTH(REPLACE(text, " ", "")) + 1) as size FROM '.$this->tableName.
            ' WHERE lang =  "' . $lang .'" having size >' . $minWord . ' and size < ' . $maxWord . ' order by rand() limit 1;';

        $result = $this->getQuoteWithText($sql);
        $result['name'] = $this->rand->getSuraName($result['sura'], $lang);
        
        return $result;
    }

    /**
     * @param $sql
     *
     * @return array
     */
    private function getQuoteWithText($sql)
    {
        global $wpdb;
        $result = $wpdb->get_row($sql, ARRAY_A);

        return $result;
    }
}
