<?php
    class SidebarWidget extends WP_Widget
    {
        public function __construct()
        {
            $widget_details = array(
                'className' => 'SidebarWidget',
                'description' => 'foo bar'
            );
            parent::__construct('foo_121', 'bar_121', $widget_details);
    
            add_action( 'widgets_init', array( $this, 'init_dpt_widgets' ) );
    
        }
    
        public function form($instance)
        {
            $path = plugin_dir_path( __FILE__ ); // I am in Models
            include $path .'../Views/dptWidgetForm.php';
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
            $path = plugin_dir_path( __FILE__ ); // I am in Models
        
            include $path .'../Models/dptWidget.php';
        
            echo $args['after_widget'];
        }
        
        public function init_dpt_widgets()
        {
            register_widget('SidebarWidget');
        }
    }
    
    
