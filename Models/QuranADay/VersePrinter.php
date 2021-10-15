<?php

require_once('QuranDB.php');
require_once(__DIR__.'/../../Views/TimetablePrinter.php' );

class VersePrinter
{
    private $timetablePrinter;
    
    private $db;

    public function __construct()
    {
        $this->timetablePrinter = new TimetablePrinter();
        $this->db = new DPTQuranDB();
        $this->db->createTableIfNotExist();
    }

    public function printVerse($attr=array())
    {
        $quote = $this->db->getQuote($attr);

        return
        "
        <div class='quranVerse'>
            <blockquote>
                <p>" . $quote['text'] . "</p> 
                <span class='link'>
                    <a target='_new' href='http://quran.com/" . $quote['sura']. "/" . $quote['ayat']. "'>
                        <span class='sura'> ~ ". $quote['name']. ": " . $this->timetablePrinter->getIntlNumber($quote['ayat']). "</span>
                    </a>
                </span>
            </blockquote>
        </div>
        ";
    }
}
