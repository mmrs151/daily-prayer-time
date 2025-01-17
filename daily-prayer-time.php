<?php
/*
Plugin Name: Daily Prayer Time
Version: 2025.01.17
Plugin URI: https://wordpress.org/plugins/daily-prayer-time-for-mosques/
Description: Masjid Prayer time in any language, in any screen
Author: <a href="http://mmrs151.wordpress.com">mmrs151</a>
Contributors: <a href="http://vergedesign.co.uk/">Hjeewa</a>, <a href="https://profiles.wordpress.org/kams01">kams01</a>
Text Domain: daily-prayer-time
Domain Path: /languages
*/
if ( ! function_exists( 'dpt_fs' ) ) {
    // Create a helper function for easy SDK access.
    function dpt_fs() {
        global $dpt_fs;

        if ( ! isset( $dpt_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $dpt_fs = fs_dynamic_init( array(
                'id'                  => '15569',
                'slug'                => 'daily-prayer-time-for-mosques',
                'premium_slug'        => 'daily-prayer-time-premium',
                'type'                => 'plugin',
                'public_key'          => 'pk_9fc9f990dae6915c3d494a59d644d',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'dpt',
                    'account'        => false,
                    'contact'        => false,
                ),
            ) );
        }

        return $dpt_fs;
    }

    // Init Freemius.
    dpt_fs();
    // Signal that SDK was initiated.
    do_action( 'dpt_fs_loaded' );
}

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
require_once ('Models/CustomPluginSettings.php');
require_once ('Models/QuranADay/QuranDB.php');

define('DPT_PLUGIN_VERSION', "2025.01.17");
define('DPT_PLUGIN_FILE', plugin_basename(__FILE__));

class DailyPrayerTime extends WP_Widget
{
    public function __construct()
    {
        update_option('dpt_dubug', true);
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
//        delete_option('dpt-init'); // RESET EVERY REFRESH, ONLY AVAILABLE FOR TESTING
        
        new AdminMenu();
    
        new DPTAjaxHandler();
        
        new Shortcodes();

        new CustomPluginSettings();
    }
    
    public function form($instance)
    {
        include 'Views/dptWidgetForm.php';
        ?>
        
        <div class='mfc-text'>
        
        </div>
        
        <?php
        
        echo $args['after_widget'];
        echo "<a href='https://donate.uwt.org/Account/Index.aspx' target='_blank'>Send Sadaqa to my Grave</a></br></br>";
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
