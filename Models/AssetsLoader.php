<?php
    class AssetsLoader
    {
        private $version = '2020.05.01';
    
        public function __construct()
        {
            add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
            $this->add_scripts();
            $this->add_stylesheet();
        }
    
        function load_admin_scripts($hook)
        {
            if ($hook == 'toplevel_page_dpt') {
                wp_enqueue_script('dpt-admin', plugins_url( '../Assets/js/dpt-admin.js', __FILE__ ), array( 'jquery' ), '4.0.0');
                wp_enqueue_script( 'dpt_bootstrap_js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');
            
                wp_register_style( 'dpt_bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
                wp_enqueue_style( 'dpt_bootstrap' );
            }
        }
    
        private function add_scripts()
        {
            $path = plugin_dir_url( __FILE__ ); // I am in Models

            wp_enqueue_script( 'dpt',$path. '../Assets/js/dpt.js', array( 'jquery' ), $this->version, true );
        
            $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
            $params = array(
                'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
            );
        
            // bootstrap js from CDN
            wp_enqueue_script( 'dpt_popper_js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js');
            wp_enqueue_script( 'jquery-ui-dialog' );
            wp_enqueue_script("jquery-ui",'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array('jquery'), '1.8.8');
            wp_enqueue_script("jquery-cookie",'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js', array('jquery'), '1.4.1');
            wp_enqueue_script("jquery-blockUI",'https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js', array('jquery'), '2.7.0');
        
            // Print the script to our page
            wp_localize_script( 'dpt', 'timetable_params', $params );
        }
    
        private function add_stylesheet() {
            wp_register_style( 'timetable-style', plugins_url('../Assets/css/styles.css', __FILE__), array(), $this->version );
            wp_enqueue_style( 'timetable-style' );
        
            wp_register_style( 'verge-style', plugins_url('../Assets/css/vergestyles.css', __FILE__) );
            wp_enqueue_style( 'verge-style' );
        
            wp_register_style( 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css' );
            wp_enqueue_style( 'jquery-ui' );
        
            wp_register_style( 'dpt_font_awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
            wp_enqueue_style( 'dpt_font_awesome' );
        
            wp_register_style('jquery_ui_css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
            wp_enqueue_style( 'jquery_ui_css' );
        
        
            new UpdateStyles('timetable-style');
        }
    }