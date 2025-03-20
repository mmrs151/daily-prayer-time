<?php /* Template Name: Digital Screen Prayer Time */

if (is_page_template( '../Views/DSTemplate.php' )) {

    function enqueue_dpt_scripts() {
        wp_enqueue_script('dpt', plugin_dir_url(__FILE__) . 'js/dpt.js', array('jquery'), '1.0', true);
    }

    add_action('wp_enqueue_scripts', 'enqueue_dpt_scripts');

    wp_enqueue_script( 'dpt_bootstrap_js', plugins_url( '../Assets/js/bootstrap.bundle.min.js', __FILE__ ), array( 'jquery' ), DPT_PLUGIN_VERSION);

    wp_register_style( 'dpt_bootstrap', plugins_url('../Assets/css/bootstrap.min.css', __FILE__), array(), DPT_PLUGIN_VERSION );
    
    wp_enqueue_style( 'dpt_bootstrap' );

    // Set headers to prevent caching
    add_action('send_headers', function() {
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
    });
}

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <?php if ( ! get_theme_support( 'title-tag' ) ): ?>
        <title><?php wp_title(); ?></title>
    <?php endif; ?>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php //wp_head(); ?>

    <style>
        <?php echo esc_html(get_option("ds-additional-css") )?>
    </style>
    
</head>

<body class="google-font">
<?php

while ( have_posts() ) : the_post(); ?>

    <div class="entry-content">

        <?php the_content(); ?>

    </div><!-- .entry-content -->

<?php
endwhile;
?>
<?php wp_footer(); ?>
</body>
</html>
