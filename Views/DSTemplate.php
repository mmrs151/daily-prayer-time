<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <link rel='stylesheet' href="<?php echo plugin_dir_url(__FILE__) . '../Assets/css/google-font-ubuntu.css'; ?>">
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . '../Assets/css/bootstrap.min.css'; ?>">
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
<script src="<?php echo  plugin_dir_url(__FILE__) . '../Assets/js/bootstrap.bundle.min.js'; ?>"></script>
</body>
</html>
