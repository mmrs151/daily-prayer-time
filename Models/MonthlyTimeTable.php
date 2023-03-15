<?php
require_once('db.php');
require_once(__DIR__.'/../Views/MonthlyTimetablePrinter.php');

class MonthlyTimeTable
{
    /** @var  integer */
    private $monthNumber;

    /** @var array */
    private $row = array();

    /** @var  MonthlyTimetablePrinter */
    private $timetablePrinter;

    public function __construct($monthNumber)
    {
        $this->monthNumber = $monthNumber;
        $this->row = $this->getMonthlyCalendar($this->monthNumber);
        $this->timetablePrinter = new MonthlyTimetablePrinter();
    }

	/**
     * @param $options
     *
     * @return string
     */
    public function displayTable($options)
    {
        return $this->timetablePrinter->displayTable($this->row, $options);
    }

	/**
     * @param $options
     *
     * @return string
     */
    public function displayTableJamahOnly($options)
    {
        return $this->timetablePrinter->displayTableJamahOnly($this->row, $options);
    }

	/**
     * @param $options
     *
     * @return string
     */
    public function displayTableAzanOnly($options)
    {
        return $this->timetablePrinter->displayTableAzanOnly($this->row, $options);
    }       

    /**
     * return monthly prayer time
     * @param integer $month
     * @return array
     */
    private function getMonthlyCalendar($month)
    {
        $db = new DatabaseConnection();
        if ( $month == 13 ) { // ramadan
            return $db->getPrayerTimeForRamadan();
        }
        return $db->getPrayerTimeForMonth($month, date('Y'));
    }
}
