<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <link href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <?php if ( ! get_theme_support( 'title-tag' ) ): ?>
        <title><?php wp_title(); ?></title>
    <?php endif; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php //wp_head(); ?>
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
