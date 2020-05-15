<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;
$table = $wpdb->prefix."timetable";

delete_option('dpt-init');
delete_option('asrSelect');
delete_option('prayersLocal');
delete_option('headersLocal');
delete_option('numbersLocal');
delete_option('monthsLocal');
delete_option('ramadan-chbox');
delete_option('timesLocal');
delete_option('hijri-chbox');
delete_option('hijri-adjust');
delete_option('tableBackground');
delete_option('tableHeading');
delete_option('evenRow');
delete_option('fontColor');
delete_option('highlight');
delete_option('jamah_changes');
delete_option('slider-chbox');
delete_option('transitionEffect');
delete_option('transitionSpeed');

delete_option('slider1Url');
delete_option('slider2Url');
delete_option('slider3Url');
delete_option('slider4Url');
delete_option('slider5Url');

delete_option('fajr-delay');
delete_option('zuhr-delay');
delete_option('asr-delay');
delete_option('maghrib-delah');
delete_option('isha-delay');
delete_transient('dpt_cities');

$wpdb->query("DROP TABLE IF EXISTS $table");