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
            add_shortcode( 'dailytable_vertical', array($dailyShortCode, 'verticalTime') );
            add_shortcode( 'dailytable_horizontal', array($dailyShortCode, 'horizontalTime') );

            add_shortcode( 'display_ramadan_time', array($dailyShortCode, 'scRamadanTime') );
            add_shortcode( 'daily_next_prayer', array($dailyShortCode, 'scNextPrayer') );

            add_shortcode( 'fajr_prayer', array($dailyShortCode, 'scFajr') );
            add_shortcode( 'sunrise', array($dailyShortCode, 'scSunrise') );
            add_shortcode( 'zawal', array($dailyShortCode, 'scZawal') );
            add_shortcode( 'zuhr_prayer', array($dailyShortCode, 'scZuhr') );
            add_shortcode( 'asr_prayer', array($dailyShortCode, 'scAsr') );
            add_shortcode( 'maghrib_prayer', array($dailyShortCode, 'scMaghrib') );
            add_shortcode( 'isha_prayer', array($dailyShortCode, 'scIsha') );
            add_shortcode( 'fajr_start', array($dailyShortCode, 'scFajrStart') );
            add_shortcode( 'zuhr_start', array($dailyShortCode, 'scZuhrStart') );
            add_shortcode( 'asr_start', array($dailyShortCode, 'scAsrStart') );
            add_shortcode( 'maghrib_start', array($dailyShortCode, 'scMaghribStart') );
            add_shortcode( 'isha_start', array($dailyShortCode, 'scIshaStart') );
            add_shortcode( 'jummah_prayer', array($dailyShortCode, 'scJummahPrayer') );

            add_shortcode( 'display_iqamah_update', array($dailyShortCode, 'scIqamahUpdate') );
            add_shortcode( 'digital_screen', array($dailyShortCode, 'scDigitalScreen') );
            add_shortcode( 'quran_verse', array($dailyShortCode, 'scQuranVarse') );
            add_shortcode( 'hijri_date', array($dailyShortCode, 'scHijriDate') );
        }
    }