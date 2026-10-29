<?php
// Run only with WP-CLI on the dedicated local demo installation.
if (!defined('WP_CLI') || !WP_CLI || get_option('blogname') !== 'Daily Prayer Time demo') {
    throw new RuntimeException('Refusing to seed a non-demo WordPress site.');
}

require_once __DIR__ . '/../Models/Processors/CsvProcessor.php';

if (!get_option('dpt_demo_seeded')) {
    $fixture = __DIR__ . '/../tests/fixtures/eict-2026.csv.txt';
    $processor = new DPTCsvProcessor(array('timetable' => array(
        'name' => 'eict.csv', 'type' => 'text/csv', 'tmp_name' => $fixture
    )));
    if (!$processor->isValidFile()) {
        throw new RuntimeException('The sample timetable could not be read.');
    }
    ob_start();
    $processor->process();
    $result = ob_get_clean();
    if (strpos($result, '365 Rows processed') === false) {
        throw new RuntimeException('The sample timetable import did not finish: ' . strip_tags($result));
    }
    update_option('dpt_demo_seeded', 1);
}

if (!get_option('dpt_demo_slides_seeded')) {
    $pluginFile = dirname(__DIR__) . '/daily-prayer-time.php';
    update_option('slider-chbox', 'slider');
    update_option('transitionSpeed', 5000);
    update_option('slider1', plugins_url('Assets/images/new.jpg', $pluginFile));
    update_option('slider2', plugins_url('Assets/images/EICT.png', $pluginFile));
    update_option('slider2Days', '5');
    update_option('slider3', plugins_url('Assets/images/logo.png', $pluginFile));
    update_option('slider3Days', '0,6');
    update_option('slider4', 'Today only: slide scheduling is working');
    update_option('slider4Days', (string) current_time('w'));
    update_option('dpt_demo_slides_seeded', 1);
}

function dpt_demo_page($title, $slug, $content) {
    $existing = get_page_by_path($slug);
    $data = array(
        'post_title' => $title,
        'post_name' => $slug,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_type' => 'page',
    );
    if ($existing) {
        $data['ID'] = $existing->ID;
        return wp_update_post($data, true);
    }
    return wp_insert_post($data, true);
}

$shortcodes = array(
    'monthlytable', 'dailytable_vertical', 'dailytable_horizontal',
    'display_ramadan_time', 'daily_next_prayer', 'fajr_prayer', 'sunrise',
    'zawal', 'ishraq', 'zuhr_prayer', 'asr_prayer', 'maghrib_prayer',
    'isha_prayer', 'fajr_start', 'zuhr_start', 'asr_start', 'maghrib_start',
    'isha_start', 'jummah_prayer', 'display_iqamah_update', 'digital_screen',
    'quran_verse', 'hijri_date'
);

$links = array();
$horizontalPageId = 0;
$verticalPageId = 0;
foreach ($shortcodes as $tag) {
    $slug = 'shortcode-' . str_replace('_', '-', $tag);
    $isHorizontal = $tag === 'dailytable_horizontal';
    $isVertical = $tag === 'dailytable_vertical';
    $isDailyTable = $isHorizontal || $isVertical;
    $id = dpt_demo_page($isDailyTable ? 'Daily prayer times' : '[' . $tag . ']', $slug,
        $isDailyTable ? '[' . $tag . ']' :
        '<p>This page renders <code>&#91;' . esc_html($tag) . '&#93;</code>.</p>[' . $tag . ']');
    if (is_wp_error($id)) {
        throw new RuntimeException($id->get_error_message());
    }
    if ($isHorizontal) {
        $horizontalPageId = $id;
    }
    if ($isVertical) {
        $verticalPageId = $id;
    }
    if ($tag === 'digital_screen') {
        update_post_meta($id, '_wp_page_template', '../Views/DSTemplate.php');
        wp_update_post(array('ID' => $id, 'post_content' => '[' . $tag . ']'));
    }
    $links[] = '<li><a href="' . esc_url(get_permalink($id)) . '"><code>&#91;' . esc_html($tag) . '&#93;</code></a></li>';
}

$variants = array(
    'Digital screen, portrait' => '[digital_screen view="vertical"]',
    'Digital screen, presentation' => '[digital_screen view="presentation"]',
    'Digital screen, EICT template' => '[digital_screen template="eict"]',
    'Daily table, Hanafi' => '[dailytable_vertical asr="hanafi"]',
    'Daily table, Iqamah only' => '[dailytable_horizontal display="iqamah_only"]',
    'Monthly table, Azan only' => '[monthlytable display="azan_only"]',
);
foreach ($variants as $title => $code) {
    $slug = 'variant-' . sanitize_title($title);
    $id = dpt_demo_page($title, $slug,
        '<p>Variant: <code>' . str_replace(array('[', ']'), array('&#91;', '&#93;'), esc_html($code)) . '</code></p>' . $code);
    if (is_wp_error($id)) {
        throw new RuntimeException($id->get_error_message());
    }
    if (strpos($title, 'Digital screen,') === 0) {
        update_post_meta($id, '_wp_page_template', '../Views/DSTemplate.php');
        wp_update_post(array('ID' => $id, 'post_content' => $code));
    }
    $links[] = '<li><a href="' . esc_url(get_permalink($id)) . '">' . esc_html($title) . '</a></li>';
}

$gallery = dpt_demo_page('Shortcode gallery', 'shortcode-gallery',
    '<p>Sample EICT 2026 timetable is loaded. Every registered shortcode has its own page. '
    . 'The digital screen has a daily image, a Friday image, a weekend image, and a message shown only today. '
    . 'Change their weekdays under Daily Prayer Time → Digital Screen → Slider Settings.</p>'
    . '<ul>' . implode('', $links) . '</ul>');
if (is_wp_error($gallery)) {
    throw new RuntimeException($gallery->get_error_message());
}
update_option('show_on_front', 'page');
update_option('page_on_front', $gallery);
if ($horizontalPageId && $verticalPageId && (int) get_option('dpt_demo_css_version', 0) < 3) {
    $css = '';
    foreach (array($horizontalPageId, $verticalPageId) as $pageId) {
        $page = 'body.page-id-' . (int) $pageId;
        $css .= $page . ' header .wp-block-navigation {display:none!important;}'
            . $page . ' header .wp-block-group {padding-top:12px!important;padding-bottom:12px!important;}'
            . $page . ' main {margin-top:0!important;padding-top:16px!important;}'
            . $page . ' main > .wp-block-group {padding-top:0!important;padding-bottom:22px!important;}'
            . $page . ' .wp-block-post-title {'
            . 'font-size:clamp(28px,3vw,40px)!important;line-height:1.15;margin:0 0 14px!important;}'
            . $page . ' .wp-block-post-content {margin-top:0!important;}';
    }
    wp_update_custom_css_post($css);
    update_option('dpt_demo_css_version', 3);
}
echo 'Created shortcode gallery and ' . count($shortcodes) . " shortcode pages.\n";
