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
                wp_enqueue_script('dpt-admin', plugins_url( '../Assets/js/dpt-admin.js', __FILE__ ), array( 'jquery' ), DPT_PLUGIN_VERSION);
                wp_enqueue_script( 'dpt_bootstrap_js', plugins_url( '../Assets/js/bootstrap.bundle.min.js', __FILE__ ), array( 'jquery' ), DPT_PLUGIN_VERSION);
                wp_enqueue_script( 'bs_select_js', plugins_url( '../Assets/js/bootstrap-select.min.js', __FILE__ ), array( 'jquery' ), DPT_PLUGIN_VERSION);
    
                wp_register_style( 'dpt_bootstrap', plugins_url('../Assets/css/bootstrap.min.css', __FILE__), array(), DPT_PLUGIN_VERSION );
                wp_enqueue_style( 'dpt_bootstrap' );

                wp_register_style( 'bs_select_css', plugins_url('../Assets/css/bootstrap-select.min.css', __FILE__), array(), DPT_PLUGIN_VERSION );
                wp_enqueue_style( 'bs_select_css' );
            }
        }
    
        private function add_scripts()
        {
            $path = plugin_dir_url( __FILE__ ); // I am in Models

            wp_enqueue_script( 'dpt',$path. '../Assets/js/dpt.js', array( 'jquery' ), DPT_PLUGIN_VERSION, true );
        
            $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
            $params = array(
                'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
                'fajrAdhanUrl' => 'https://example.com/fajr.mp3',
                'otherAdhanUrl' => 'https://example.com/other.mp3'
            );
            
        
            wp_enqueue_script( 'dpt_popper_js', plugins_url( '../Assets/js/popper.min-1.12.9.js', __FILE__ ), array( 'jquery' ), DPT_PLUGIN_VERSION);
            wp_enqueue_script("jquery-ui-tabs");
            wp_enqueue_script( 'jquery-ui-dialog' );
            wp_enqueue_script("jquery-cookie", plugins_url( '../Assets/js/jquery.cookie.min-1.4.1.js', __FILE__ ), array( 'jquery' ), DPT_PLUGIN_VERSION );
            wp_enqueue_script("jquery-blockUI", plugins_url( '../Assets/js/jquery.blockUI-2.70.js', __FILE__ ), array( 'jquery' ), DPT_PLUGIN_VERSION );
            wp_enqueue_script("jquery-marquee", plugins_url( '../Assets/js/jquery.marquee.min.js', __FILE__ ), array( 'jquery' ), DPT_PLUGIN_VERSION );
            wp_enqueue_script("dpt-noSleep", plugins_url( '../Assets/js/NoSleep.min.js', __FILE__ ), array( 'jquery' ), DPT_PLUGIN_VERSION );
            wp_enqueue_script("dpt-iconify", plugins_url( '../Assets/js/iconify.min.js', __FILE__ ), array( 'jquery' ), DPT_PLUGIN_VERSION );
        
            // Print the script to our page
            wp_localize_script( 'dpt', 'timetable_params', $params );
        }
    
        private function add_stylesheet() {
            wp_register_style( 'timetable-style', plugins_url('../Assets/css/styles.css', __FILE__), array(), DPT_PLUGIN_VERSION );
            wp_enqueue_style( 'timetable-style' );
            
            wp_register_style( 'verge-style', plugins_url('../Assets/css/vergestyles.css', __FILE__), array(),  DPT_PLUGIN_VERSION );
            wp_enqueue_style( 'verge-style' );
            
            wp_register_style( 'jquery-ui_css', plugins_url('../Assets/css/jquery-ui-1.12.1.css', __FILE__), array(), DPT_PLUGIN_VERSION );
            wp_enqueue_style( 'jquery-ui_css' );
            
            wp_register_style("bootstrap-select", plugins_url('../Assets/css/bootstrap-select.min-1.13.14.css', __FILE__), array(), DPT_PLUGIN_VERSION );
            wp_enqueue_style("bootstrap-select");
        
            new UpdateStyles('timetable-style');
        }
    }