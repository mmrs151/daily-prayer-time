<?php
    class AssetsLoader
    {
        public function __construct()
        {
            add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
            $this->add_scripts();
            $this->add_stylesheet();
        }
    
        function load_admin_scripts($hook)
        {
            if ($hook == 'toplevel_page_dpt') {
                wp_enqueue_script('dpt-admin', plugins_url( '../Assets/js/dpt-admin.js', __FILE__ ), array( 'jquery' ), DailyPrayerTime::VERSION);
                wp_enqueue_script( 'dpt_bootstrap_js', plugins_url( '../Assets/js/bootstrap.min-5.0.2.js', __FILE__ ), array( 'jquery' ), DailyPrayerTime::VERSION);
    
                wp_register_style( 'dpt_bootstrap', plugins_url('../Assets/css/bootstrap.min-5.0.2.css', __FILE__), array(), DailyPrayerTime::VERSION );
                wp_enqueue_style( 'dpt_bootstrap' );
            }
        }
    
        private function add_scripts()
        {
            $path = plugin_dir_url( __FILE__ ); // I am in Models

            wp_enqueue_script( 'dpt',$path. '../Assets/js/dpt.js', array( 'jquery' ), DailyPrayerTime::VERSION, true );
        
            $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
            $params = array(
                'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
            );
        
            // bootstrap js from CDN
            wp_enqueue_script( 'dpt_popper_js', plugins_url( '../Assets/js/popper.min-1.12.9.js', __FILE__ ), array( 'jquery' ), DailyPrayerTime::VERSION);
            wp_enqueue_script("jquery-ui-tabs");
            wp_enqueue_script( 'jquery-ui-dialog' );
            wp_enqueue_script("jquery-cookie", plugins_url( '../Assets/js/jquery.cookie.min-1.4.1.js', __FILE__ ), array( 'jquery' ), DailyPrayerTime::VERSION );
            wp_enqueue_script("jquery-blockUI", plugins_url( '../Assets/js/jquery.blockUI-2.70.js', __FILE__ ), array( 'jquery' ), DailyPrayerTime::VERSION );
            wp_enqueue_script("jquery-marquee", plugins_url( '../Assets/js/jquery.marquee.min.js', __FILE__ ), array( 'jquery' ), DailyPrayerTime::VERSION );
        
            // Print the script to our page
            wp_localize_script( 'dpt', 'timetable_params', $params );
        }
    
        private function add_stylesheet() {
            wp_register_style( 'timetable-style', plugins_url('../Assets/css/styles.css', __FILE__), array(), DailyPrayerTime::VERSION );
            wp_enqueue_style( 'timetable-style' );
            
            wp_register_style( 'verge-style', plugins_url('../Assets/css/vergestyles.css', __FILE__) );
            wp_enqueue_style( 'verge-style' );
            
            wp_register_style( 'jquery-ui_css', plugins_url('../Assets/css/jquery-ui-1.12.1.css', __FILE__), array(), DailyPrayerTime::VERSION );
            wp_enqueue_style( 'jquery-ui_css' );
            
            wp_register_style("bootstrap-select", plugins_url('../Assets/css/bootstrap-select.min-1.13.14.css', __FILE__), array(), DailyPrayerTime::VERSION );
            wp_enqueue_style("bootstrap-select");
        
            new UpdateStyles('timetable-style');
        }
    }