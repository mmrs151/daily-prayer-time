<?php
    class Shortcodes
    {
        public function __construct()
        {
            $this->addMonthlyShortCodes();
            $this->addDailyShortCodes();
        }
        
        public function addMonthlyShortCodes()
        {
            $monthlyShortcode = new MonthlyShortCode();
            add_shortcode( 'monthlytable', array($monthlyShortcode, 'printMonthlyTimeTable') );
        }
        
        public function addDailyShortCodes()
        {
            $dailyShortCode = new DailyShortCode();
            $handlers = array(
                'dailytable_vertical' => 'verticalTime',
                'dailytable_horizontal' => 'horizontalTime',
                'display_ramadan_time' => 'scRamadanTime',
                'daily_next_prayer' => 'scNextPrayer',
                'fajr_prayer' => 'scFajr',
                'sunrise' => 'scSunrise',
                'zawal' => 'scZawal',
                'ishraq' => 'scIshraq',
                'zuhr_prayer' => 'scZuhr',
                'asr_prayer' => 'scAsr',
                'maghrib_prayer' => 'scMaghrib',
                'isha_prayer' => 'scIsha',
                'fajr_start' => 'scFajrStart',
                'zuhr_start' => 'scZuhrStart',
                'asr_start' => 'scAsrStart',
                'maghrib_start' => 'scMaghribStart',
                'isha_start' => 'scIshaStart',
                'jummah_prayer' => 'scJummahPrayer',
                'display_iqamah_update' => 'scIqamahUpdate',
                'digital_screen' => 'scDigitalScreen',
                'quran_verse' => 'scQuranVarse',
                'hijri_date' => 'scHijriDate'
            );
            foreach ($handlers as $tag => $method) {
                add_shortcode($tag, function ($attr) use ($dailyShortCode, $method) {
                    $instance = clone $dailyShortCode;
                    return $instance->$method($attr);
                });
            }
        }
    }
