<?php
class DSTemplateLoader
{
    /**
     * A reference to an instance of this class.
     */
    private static $instance;

    /**
     * The array of templates that this plugin tracks.
     */
    protected $templates;

    /**
     * Returns an instance of this class.
     */
    public static function get_instance() {

        if ( null == self::$instance ) {
            self::$instance = new DSTemplateLoader();
        }

        return self::$instance;
    }

    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    private function __construct() {

        $this->templates = array();

        add_filter('page_attributes_dropdown_pages_args', array( $this, 'register_project_templates' ) );

        add_filter( 'wp_insert_post_data', array( $this, 'register_project_templates' ) );

        add_filter( 'template_include', array( $this, 'view_project_template' ) );

        $this->templates = array( '../Views/DSTemplate.php' => 'Digital Screen Prayer Time', 
        '../Views/DPTFullWidth.php' => 'Full Width Monthly Prayer Time', );
    }


    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     *
     */

    public function register_project_templates( $atts ) {

        $theme = wp_get_theme();

        $cache_key = 'page_templates-' . md5( $theme->get_theme_root() . '/' . $theme->get_stylesheet() );

        $templates = $theme->get_page_templates();

        $templates = array_merge( $templates, $this->templates );

        wp_cache_set( $cache_key, $templates, 'themes', 300 );

        add_filter( 'theme_page_templates', function( $page_templates ) use ( $templates ) {
            return $templates;
        });

        return $atts;

    }

    /**
     * Checks if the template is assigned to the page
     */
    public function view_project_template( $template ) {

        global $post;

        if ( isset($post->ID) && ! isset( $this->templates[ get_post_meta(
                $post->ID, '_wp_page_template', true
            ) ] )
        ) {

            return $template;
        }

        $file = plugin_dir_path( __FILE__ ) . get_post_meta( $post->ID, '_wp_page_template', true );

        if ( file_exists( $file ) ) {
            return $file;
        } else {
            echo esc_attr( $file );
        }

        return $template;

    }

}

add_action( 'plugins_loaded', array( 'DSTemplateLoader', 'get_instance' ) );
