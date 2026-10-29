<?php
// Run only inside the disposable WordPress installation created by tests/run.sh.
if (!defined('ABSPATH') || get_option('blogname') !== 'DPT Tests') {
    throw new RuntimeException('Refusing to run outside the isolated DPT test site.');
}
set_error_handler(function ($severity, $message, $file, $line) {
    if (($severity & (E_WARNING | E_NOTICE | E_USER_WARNING | E_USER_NOTICE))
        && strpos($file, '/wp-content/plugins/dpt/') !== false) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
    return false;
});

require_once __DIR__ . '/../Models/Processors/CsvProcessor.php';
require_once __DIR__ . '/../Models/Processors/DigitalScreenProcessor.php';

$GLOBALS['dpt_checks'] = 0;
function dpt_check($condition, $message) {
    $GLOBALS['dpt_checks']++;
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$fixture = __DIR__ . '/fixtures/eict-2026.csv.txt';
dpt_check(is_readable($fixture), 'EICT CSV fixture is missing');
$handle = fopen($fixture, 'r');
$header = fgetcsv($handle);
$sample = fgetcsv($handle);
fclose($handle);
$validator = new Validator();
dpt_check($validator->checkHeader($header), 'EICT CSV headers were rejected');
dpt_check($validator->isValidData($sample), 'EICT CSV sample row was rejected');

$bad = $sample;
$bad[13] = "1' OR '1'='1";
dpt_check(!$validator->isValidData($bad), 'SQL-like Ramadan value was accepted');
$bad = $sample;
$bad[] = 'extra';
dpt_check(!$validator->isValidData($bad), 'Extra CSV column was accepted');
$bad = $sample;
$bad[4] = '25:99';
ob_start();
$accepted = $validator->isValidData($bad);
ob_end_clean();
dpt_check(!$accepted, 'Invalid prayer time was accepted');

$processor = new DPTCsvProcessor(array('timetable' => array(
    'name' => 'eict.csv', 'type' => 'text/csv', 'tmp_name' => $fixture
)));
dpt_check($processor->isValidFile(), 'EICT CSV was rejected as an upload');
ob_start();
$processor->process();
$importOutput = ob_get_clean();
dpt_check(strpos($importOutput, '365 Rows processed') !== false, 'EICT CSV import did not process 365 rows');

$db = new DatabaseConnection();
$today = current_time('Y-m-d');
$tomorrow = date('Y-m-d', strtotime($today . ' +1 day'));
// Restore the valid sample after checking malformed rows.
$validator->isValidData($sample);
$todayRow = $validator->getValidData();
$todayRow['d_date'] = $today;
$tomorrowRow = $todayRow;
$tomorrowRow['d_date'] = $tomorrow;
$tomorrowRow['fajr_begins'] = '06:15';
dpt_check($db->insertRow($todayRow)['result'] !== false, 'Could not seed today');
dpt_check($db->insertRow($tomorrowRow)['result'] !== false, 'Could not seed tomorrow');
$actual = $db->getPrayerTimeForToday();
dpt_check(substr($actual['fajr_begins'], 0, 5) === '06:26', 'Today lookup changed');
dpt_check(substr($actual['tomorrow']['fajr_begins'], 0, 5) === '06:15', 'Tomorrow lookup changed');
$quotedRow = $todayRow;
$quotedRow['d_date'] = '2099-01-01';
$quotedRow['hijri_date'] = "O'Reilly";
dpt_check($db->insertRow($quotedRow)['result'] !== false, 'Prepared insert rejected quoted data');
global $wpdb;
$storedText = $wpdb->get_var($wpdb->prepare(
    'SELECT hijri_date FROM ' . $wpdb->prefix . 'timetable WHERE d_date = %s', '2099-01-01'
));
dpt_check($storedText === "O'Reilly", 'Prepared insert changed quoted data');

update_option('time_format', 'H:i');
new Shortcodes();
$shortcodes = array(
    'monthlytable', 'dailytable_vertical', 'dailytable_horizontal',
    'display_ramadan_time', 'daily_next_prayer', 'fajr_prayer', 'sunrise',
    'zawal', 'ishraq', 'zuhr_prayer', 'asr_prayer', 'maghrib_prayer',
    'isha_prayer', 'fajr_start', 'zuhr_start', 'asr_start', 'maghrib_start',
    'isha_start', 'jummah_prayer', 'display_iqamah_update', 'digital_screen',
    'quran_verse', 'hijri_date'
);
foreach ($shortcodes as $tag) {
    dpt_check(shortcode_exists($tag), "Missing shortcode [$tag]");
    $html = do_shortcode("[$tag]");
    dpt_check(is_string($html), "[$tag] did not return a string");
    if (in_array($tag, array(
        'monthlytable', 'dailytable_vertical', 'dailytable_horizontal',
        'fajr_prayer', 'zuhr_prayer', 'asr_prayer', 'maghrib_prayer',
        'isha_prayer', 'fajr_start', 'zuhr_start', 'asr_start',
        'maghrib_start', 'isha_start', 'digital_screen', 'hijri_date'
    ), true)) {
        dpt_check(trim($html) !== '', "[$tag] rendered empty content");
    }
}

$cases = array(
    '[dailytable_vertical]', '[dailytable_horizontal]',
    '[dailytable_vertical asr="hanafi"]', '[dailytable_horizontal asr="hanafi"]',
    '[dailytable_vertical display="iqamah_only"]',
    '[dailytable_horizontal display="azan_only"]',
    '[dailytable_horizontal use_div_layout="1"]',
    '[dailytable_horizontal announcement="Friday notice" day="friday"]',
    '[monthlytable]', '[monthlytable display="iqamah_only"]',
    '[monthlytable display="azan_only"]',
    '[digital_screen]', '[digital_screen view="vertical"]',
    '[digital_screen view="presentation"]'
);
foreach ($cases as $case) {
    dpt_check(do_shortcode($case) !== $case, "Shortcode did not execute: $case");
}
$horizontalOutput = do_shortcode('[dailytable_horizontal]');
dpt_check(strpos($horizontalOutput, 'dpt-horizontal-overview') !== false &&
    strpos($horizontalOutput, 'dpt-horizontal-table-scroll') !== false,
    'Horizontal timetable compact layout is missing');
dpt_check(strpos($horizontalOutput, 'dpt-horizontal-begins') !== false &&
    strpos($horizontalOutput, 'dpt-horizontal-iqamah') !== false,
    'Horizontal timetable prayer rows are missing');
$iqamahOnlyOutput = do_shortcode('[dailytable_horizontal display="iqamah_only"]');
dpt_check(strpos($iqamahOnlyOutput, 'dpt-horizontal-iqamah') !== false &&
    strpos($iqamahOnlyOutput, 'dpt-horizontal-begins') === false,
    'Horizontal Iqamah-only view has the wrong prayer rows');
$azanOnlyOutput = do_shortcode('[dailytable_horizontal display="azan_only"]');
dpt_check(strpos($azanOnlyOutput, 'dpt-horizontal-begins') !== false &&
    strpos($azanOnlyOutput, 'dpt-horizontal-iqamah') === false,
    'Horizontal Azan-only view has the wrong prayer rows');
$dailyOutput = do_shortcode('[dailytable_vertical]');
dpt_check(strpos($dailyOutput, 'dpt-vertical-shell') !== false &&
    strpos($dailyOutput, 'dpt-vertical-headings') !== false,
    'Vertical timetable compact layout is missing');
dpt_check(substr_count($dailyOutput, 'dpt-vertical-prayer-row') >= 6,
    'Vertical timetable prayer rows are missing');
$verticalIqamahOutput = do_shortcode('[dailytable_vertical display="iqamah_only"]');
dpt_check(strpos($verticalIqamahOutput, 'dpt-vertical-shell') !== false &&
    strpos($verticalIqamahOutput, 'Iqamah') !== false,
    'Vertical Iqamah-only view failed to render');
$verticalAzanOutput = do_shortcode('[dailytable_vertical display="azan_only"]');
dpt_check(strpos($verticalAzanOutput, 'dpt-vertical-shell') !== false &&
    strpos($verticalAzanOutput, 'Begins') !== false,
    'Vertical Azan-only view failed to render');
dpt_check(strpos($dailyOutput, '06:26') !== false,
    'Daily timetable no longer shows the imported Fajr time: ' . substr(strip_tags($dailyOutput), 0, 350));
$expectedFajrStart = current_time('H:i') > '07:03' ? '06:15' : '06:26';
dpt_check(strpos(do_shortcode('[fajr_start]'), $expectedFajrStart) !== false,
    'Fajr start shortcode switched before or after the wrong Iqamah boundary');
dpt_check(strpos(do_shortcode('[monthlytable]'), 'monthlyTimetable') !== false,
    'Monthly timetable container is missing');
$month = new MonthlyTimeTable((int) current_time('n'));
$monthOptions = array('monthNumber' => (int) current_time('n'));
dpt_check(strpos($month->displayTable($monthOptions), 'Fajr') !== false,
    'Monthly timetable no longer renders imported prayer rows');
dpt_check($month->displayTableJamahOnly($monthOptions) !== '',
    'Monthly Iqamah view rendered empty content');
dpt_check($month->displayTableAzanOnly($monthOptions) !== '',
    'Monthly Azan view rendered empty content');

update_option('dsTemplate', '../../invalid-template');
dpt_check(strpos(do_shortcode('[digital_screen]'), 'x-board') !== false,
    'Invalid template did not safely fall back to the default screen');
delete_option('dsTemplate');
foreach (array('eict', 'usman', 'my-masjid') as $template) {
    update_option('dsTemplate', $template);
    dpt_check(trim(do_shortcode('[digital_screen]')) !== '', "Template $template rendered empty content");
}
delete_option('dsTemplate');
update_option('ds-logo', 'https://example.invalid/logo" onerror="alert(1)');
$screen = do_shortcode('[digital_screen]');
dpt_check(strpos($screen, 'src="https://example.invalid/logo" onerror=') === false,
    'Logo URL escaped into a new HTML attribute');
delete_option('ds-logo');

// Existing slides stay daily; scheduled slides follow the site's weekday.
update_option('slider-chbox', 'slider');
update_option('slider1', 'DAILY_SLIDE');
update_option('slider1Url', 'https://example.invalid/daily');
update_option('slider2', 'TODAY_SLIDE');
update_option('slider2Url', 'https://example.invalid/today');
update_option('slider3', 'OTHER_DAY_SLIDE');
update_option('slider3Url', 'https://example.invalid/other');
$weekday = (int) current_time('w');
update_option('slider2Days', (string) $weekday);
update_option('slider3Days', (string) (($weekday + 1) % 7));
$screen = do_shortcode('[digital_screen]');
dpt_check(strpos($screen, 'DAILY_SLIDE') !== false, 'Legacy slide stopped showing daily');
dpt_check(strpos($screen, 'TODAY_SLIDE') !== false, 'Today-only slide was hidden');
dpt_check(strpos($screen, 'OTHER_DAY_SLIDE') === false, 'Off-day slide was shown');
dpt_check(strpos($screen, 'https://example.invalid/today') !== false,
    'Scheduled slide lost its own link after filtering');
dpt_check((bool) preg_match('/id="dptNextDayRefresh"/', $screen),
    'Digital screen does not schedule a reload for the next weekday');

// Saving all days removes the restriction; invalid values never become weekdays.
$processor = new DPTDigitalScreenProcessor(array(
    'slider2' => 'TODAY_SLIDE',
    'slider2Url' => 'https://example.invalid/today',
    'slider2Days' => array('0', '1', '2', '3', '4', '5', '6', 'evil')
));
$processor->process();
dpt_check(get_option('slider2Days', false) === false, 'All weekdays did not restore daily behavior');
update_option('slider2Days', '');
dpt_check(strpos(do_shortcode('[digital_screen]'), 'TODAY_SLIDE') === false,
    'Slide with no selected weekdays was displayed');
$processor = new DPTDigitalScreenProcessor(array(
    'slider13' => 'LAST_SLIDE',
    'slider13Url' => 'https://example.invalid/last',
    'slider13Days' => array((string) $weekday)
));
$processor->process();
dpt_check(get_option('slider13Days') === (string) $weekday,
    'Thirteenth slide weekday was not saved');

update_option('slider1', 'https://example.invalid/daily-image.png');
update_option('slider2', 'https://example.invalid/today-image.png');
update_option('slider3', 'https://example.invalid/other-image.png');
update_option('slider2Days', (string) $weekday);
update_option('slider3Days', (string) (($weekday + 1) % 7));
$presentation = do_shortcode('[digital_screen view="presentation"]');
dpt_check(strpos($presentation, 'daily-image.png') !== false, 'Daily image missing from presentation');
dpt_check(strpos($presentation, 'today-image.png') !== false, 'Scheduled image missing from presentation');
dpt_check(strpos($presentation, 'other-image.png') === false, 'Off-day image shown in presentation');
foreach (range(1, 13) as $i) {
    delete_option("slider$i");
    delete_option("slider{$i}Url");
    delete_option("slider{$i}Days");
}
delete_option('slider-chbox');

foreach (array('today', 'month', 'year', 'ramadan', 'iqamah', 'tomorrow_fajr', 'iqamah_changes') as $filter) {
    $request = new WP_REST_Request('GET', '/dpt/v1/prayertime');
    $request->set_param('filter', $filter);
    $response = rest_do_request($request);
    dpt_check($response->get_status() === 200, "REST filter $filter failed");
    if ($filter === 'today') {
        $data = $response->get_data();
        dpt_check(substr($data[0]['fajr_begins'], 0, 5) === '06:26',
            'REST today filter changed the imported time');
        dpt_check(array_key_exists('azanTime', $data[0]['next_prayer'])
            && array_key_exists('iqamahTime', $data[0]['next_prayer']),
            'REST next_prayer omitted its time fields');
    }
}

echo 'PASS: ' . $GLOBALS['dpt_checks'] . " WordPress integration checks; EICT CSV, shortcodes, digital screen, and REST filters.\n";
