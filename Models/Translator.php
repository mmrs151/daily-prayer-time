<?php
require_once(__DIR__ . '/../Views/TimetablePrinter.php');

// #TODO @TODO 
class Translator
{
    /** @var TimetablePrinter */
    private $timetablePrinter;

    function __construct()
    {
        $this->timetablePrinter = new TimetablePrinter();    
    }

    function getPrayerNames()
    {
        $this->timetablePrinter->getLocalPrayerNames();
    }

    function getPrayerName($prayerName)
    {
        return $this->getPrayerNames()[$prayerName];
    }

    function getNumbers()
    {
        return $this->timetablePrinter->getLocalNumbers();
    }

    function getNumber(int $number)
    {
        return $this->timetablePrinter->getLocalNumbers();
    }
}