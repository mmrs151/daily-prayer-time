<?php
/*
Plugin Name: Daily Prayer Time
Version: 2020.05.01
Plugin URI: https://wordpress.org/plugins/daily-prayer-time-for-mosques/
Description: Display yearly, monthly and daily prayer time, ramadan time vertically or horizontally, in any language
Author: <a href="http://mmrs151.wordpress.com">mmrs151</a>
Contributors: <a href="http://vergedesign.co.uk/">Hjeewa</a>, <a href="https://profiles.wordpress.org/kams01">kams01</a>
*/
require_once ('Models/Init.php');
require_once ('Models/DailyShortCode.php');
require_once ('Models/MonthlyShortCode.php');
require_once ('Models/UpdateStyles.php');
require_once ('Models/DSTemplateLoader.php');
require_once ('Models/DPTAjaxHandler.php');
require_once ('Models/DigitalScreen.php');
require_once ('Models/AssetsLoader.php');
require_once ('API/v1/PrayerTimeController.php');


class DailyPrayerTime extends WP_Widget
{
    public function __construct()
    {
        $widget_details = array(
            'className' => 'DailyPrayerTime',
            'description' => 'Show daily prayer time vertically or horizontally'
        );
        parent::__construct('DailyPrayerTime', 'Daily Prayer Time', $widget_details);
        
        /** Loading js/css/fonts etc */
        new AssetsLoader();
    
        /** Initialise API */
        new PrayerTimeController();
    
        if (get_option('dpt-init') != 1) {
            new Init();
        }
    }
    
    public function form($instance)
    {
        include 'Views/dptWidgetForm.php';
        ?>
        
        <div class='mfc-text'>
        
        </div>
        
        <?php
        
        echo $args['after_widget'];
        echo "<a href='http://www.uwt.org/' target='_blank'>Support The Ummah</a></br></br>";
    }
    
    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }
    
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        
        include 'Models/dptWidget.php';
        
        echo $args['after_widget'];
    }
}

add_action('widgets_init', 'init_dpt_widget');
function init_dpt_widget()
{
    register_widget('DailyPrayerTime');
}
############################# END OF WIDGET ############################################

#============================= SHORTCODE =================================================
$monthlyShortcode = new MonthlyShortCode();
add_shortcode( 'monthlytable', array($monthlyShortcode, 'printMonthlyTimeTable') );

$dailyShortCode = new DailyShortCode();
add_shortcode( 'dailytable_vertical', array($dailyShortCode, 'verticalTime') );
add_shortcode( 'dailytable_horizontal', array($dailyShortCode, 'horizontalTime') );
add_shortcode( 'display_ramadan_time', array($dailyShortCode, 'scRamadanTime') );
add_shortcode( 'daily_next_prayer', array($dailyShortCode, 'scNextPrayer') );
add_shortcode( 'fajr_prayer', array($dailyShortCode, 'scFajr') );
add_shortcode( 'sunrise', array($dailyShortCode, 'scSunrise') );
add_shortcode( 'zuhr_prayer', array($dailyShortCode, 'scZuhr') );
add_shortcode( 'asr_prayer', array($dailyShortCode, 'scAsr') );
add_shortcode( 'maghrib_prayer', array($dailyShortCode, 'scMaghrib') );
add_shortcode( 'isha_prayer', array($dailyShortCode, 'scIsha') );
add_shortcode( 'fajr_start', array($dailyShortCode, 'scFajrStart') );
add_shortcode( 'zuhr_start', array($dailyShortCode, 'scZuhrStart') );
add_shortcode( 'asr_start', array($dailyShortCode, 'scAsrStart') );
add_shortcode( 'maghrib_start', array($dailyShortCode, 'scMaghribStart') );
add_shortcode( 'isha_start', array($dailyShortCode, 'scIshaStart') );
add_shortcode( 'display_iqamah_update', array($dailyShortCode, 'scIqamahUpdate') );
add_shortcode( 'digital_screen', array($dailyShortCode, 'scDigitalScreen') );

$ajax = new DPTAjaxHandler();

#============================= MENU PAGES =========================================== #
add_action( 'admin_menu', "prayer_settings");
function prayer_settings()
{
    add_menu_page(
        'Daily Prayer Time',
        'Prayer time',
        'manage_options',
        'dpt',
        'renderMainPage',
        plugins_url( 'Assets/images/icon19.png', __FILE__ )
    );

    add_submenu_page('dpt', 'Settings', 'Settings', 'manage_options', 'dpt', 'renderMainPage');
    add_submenu_page('dpt', 'Helps and Tips', 'Helps and Tips', 'manage_options', 'helps-and-tips', 'helps_and_tips');

    function renderMainPage() { include 'Views/widget-admin.php'; }

    function helps_and_tips()
    {
        include('Views/HelpsAndTips.php');
    }
}

#============================ DEACTIVATION =========================================== #
register_deactivation_hook( __FILE__, 'pluginUninstall' );
function pluginUninstall() {

}
