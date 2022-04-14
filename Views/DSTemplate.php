<?php
if (is_page_template( '../Views/DSTemplate.php' )) {

    wp_enqueue_script('dpt-admin', plugins_url( '../Assets/js/dpt-admin.js', __FILE__ ), array( 'jquery' ), DPT_PLUGIN_VERSION);
    wp_enqueue_script( 'dpt_bootstrap_js', plugins_url( '../Assets/js/bootstrap.bundle.min.js', __FILE__ ), array( 'jquery' ), DPT_PLUGIN_VERSION);

    wp_add_inline_script( 'dpt-admin', 'DPTURLS = ' . json_encode( 
        array( 
            'fajrAdhan' => plugin_dir_url(__FILE__) . '../Assets/files/fajr.mp3',
            'otherAdhan' => plugin_dir_url(__FILE__) . '../Assets/files/adhan.mp3'
        )), 'before' );

    wp_register_style( 'dpt_bootstrap', plugins_url('../Assets/css/bootstrap.min.css', __FILE__), array(), DPT_PLUGIN_VERSION );
    wp_enqueue_style( 'dpt_bootstrap' );
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
