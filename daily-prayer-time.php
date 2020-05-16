<?php
/*
Plugin Name: Daily Prayer Time
Version: 2020.05.08
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
require_once ('Models/StartTime/PrayTime.php');
require_once ('Models/AdminMenu.php');
require_once ('Models/Shortcodes.php');
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
//        delete_option('dpt-init'); // only enable for testing
        
        new AdminMenu();
    
        new DPTAjaxHandler();
        
        new Shortcodes();
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

#============================ DEACTIVATION =========================================== #
register_deactivation_hook( __FILE__, 'pluginUninstall' );
function pluginUninstall() {}
