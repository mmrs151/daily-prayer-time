<?php
require_once(__DIR__.'/../Models/Processors/CsvProcessor.php');
require_once(__DIR__.'/../Models/Processors/LanguageProcessor.php');
require_once(__DIR__.'/../Models/Processors/OtherProcessor.php');
require_once(__DIR__.'/../Models/Processors/HijriProcessor.php');
require_once(__DIR__.'/../Models/Processors/QuickUpdateProcessor.php');
require_once(__DIR__.'/../Models/Processors/ThemeSettingsProcessor.php');
require_once(__DIR__.'/../Models/Processors/DigitalScreenProcessor.php');
require_once(__DIR__.'/../Models/Processors/StartTimeProcessor.php');
require_once(__DIR__.'/../Models/DailyShortCode.php');
require_once(__DIR__.'/../Models/db.php');
require_once(__DIR__.'/../Models/HijriDate.php');
require_once(__DIR__.'/../Models/StartTime/WorldCities.php');


ini_set('auto_detect_line_endings', true);

if (isset($_POST['set-start-time'])) {
    $data = [
        'city' => sanitize_text_field($_POST['city']),
        'method' => sanitize_text_field($_POST['method']),
        'fajr-delay' => sanitize_text_field($_POST['fajr-delay']),
        'zuhr-delay' => sanitize_text_field($_POST['zuhr-delay']),
        'asr-delay' => sanitize_text_field($_POST['asr-delay']),
        'maghrib-delay' => sanitize_text_field($_POST['maghrib-delay']),
        'isha-delay' => sanitize_text_field($_POST['isha-delay']),
        'higher-lat' => sanitize_text_field($_POST['higher-lat']),
        'method' => sanitize_text_field($_POST['method']),
        'fajr-angle' => sanitize_text_field($_POST['fajr-angle']),
        'isha-angle' => sanitize_text_field($_POST['isha-angle']),
        'isha-angle' => sanitize_text_field($_POST['isha-angle']),
        'asr-method' => sanitize_text_field($_POST['asr-method']),
    ];
    $startTimeProcessor = new DPTStartTimeProcessor($data);
    $startTimeProcessor->process();
}

if (isset($_POST['submit'])) {
    $csvProcessor = new DPTCsvProcessor($_FILES);

    if ( $csvProcessor->isValidFile() ) {
        $csvProcessor->process();
    } else {
        echo "<p class='ui-state-error dptCenter'><b>Invalid csv file ?</b>";
        echo "</br>Found: <i>(". $csvProcessor->getFileType() .")</i></p>";
    }
}

if (! empty($_POST['languageSettings'])) {
    $data = [
        'prayersLocal' => array_map( 'sanitize_text_field', $_POST['prayersLocal']),
        'headersLocal' => array_map( 'sanitize_text_field', $_POST['headersLocal']),
        'monthsLocal' => array_map( 'sanitize_text_field', $_POST['monthsLocal']),
        'numbersLocal' => array_map( 'sanitize_text_field', $_POST['numbersLocal']),
        'timesLocal' => array_map( 'sanitize_text_field', $_POST['timesLocal']),
    ];
    $languageProcessor = new DPTLanguageProcessor($data);
    $languageProcessor->process();
}

if (! empty($_POST['hijriSettings'])) {
    $data = [
        'hijri-chbox' => sanitize_text_field($_POST['hijri-chbox']),
        'hijri-adjust' => sanitize_text_field($_POST['hijri-adjust'])
    ];
    $hijri = new DPTHijriProcessor($data);
    $hijri->process();
}

if (! empty($_POST['otherSettings'])) {
    $data = [
        'jumuah' => sanitize_text_field($_POST['jumuah']),
        'ramadan-chbox' => sanitize_text_field($_POST['ramadan-chbox'] ?? ''),
        'asrSelect' => sanitize_text_field($_POST['asrSelect']),
        'jamah_chas' => sanitize_text_field($_POST['jamah_changes']),
        'imsaq' => sanitize_text_field($_POST['imsaq']),
        'tomorrow_time' => sanitize_text_field($_POST['tomorrow_time'] ?? ''),
    ];
    $otherProcessor = new DPTOtherProcessor($data);
    $otherProcessor->process();
}

if (! empty($_POST['quickUpdate'])) {
    $quickUpdateProcessor = new DPTQuickUpdateProcessor($_POST['thisMonth']);
    $quickUpdateProcessor->process();
}

if (! empty($_POST['themeSettings'])) {
    $data = [
        'hideTableBorder' => sanitize_text_field($_POST['hideTableBorder']),
        'tableBackground' => sanitize_text_field($_POST['tableBackground']),
        'tableHeading' => sanitize_text_field($_POST['tableHeading']),
        'tableHeadingFont' => sanitize_text_field($_POST['tableHeadingFont']),
        'evenRow' => sanitize_text_field($_POST['evenRow']),
        'fontColor' => sanitize_text_field($_POST['fontColor']),
        'highlight' => sanitize_text_field($_POST['highlight']),
        'notificationBackground' => sanitize_text_field($_POST['notificationBackground']),
        'notificationFont' => sanitize_text_field($_POST['notificationFont']),
        'prayerName' => sanitize_text_field($_POST['prayerName']),
        'prayerNameFont' => sanitize_text_field($_POST['prayerNameFont']),
        'digitalScreenRed' => sanitize_text_field($_POST['digitalScreenRed']),
        'digitalScreenLightRed' => sanitize_text_field($_POST['digitalScreenLightRed']),
        'digitalScreenGreen' => sanitize_text_field($_POST['digitalScreenGreen']),
        'digitalScreenPrayerName' => sanitize_text_field($_POST['digitalScreenPrayerName']),
    ];
    $themeSettings = new ThemeSettingsProcessor($data);
    $themeSettings->process();
}

if (! empty($_POST['digitalScreen'])) {
    $data = [
        'ds-logo' => sanitize_text_field($_POST['ds-logo']),
        'ds-scroll-text' => sanitize_text_field($_POST['ds-scroll-text']),
        'ds-scroll-speed' => sanitize_text_field($_POST['ds-scroll-speed']),
        'ds-blink-text' => sanitize_text_field($_POST['ds-blink-text']),
        'ds-additional-css' => sanitize_text_field($_POST['ds-additional-css']),
        'quran-chbox' => sanitize_text_field($_POST['quran-chbox'] ?? ''),
        'slider-chbox' => sanitize_text_field($_POST['slider-chbox']),
        'nextPrayerSlide' => sanitize_text_field($_POST['nextPrayerSlide']),
        'transitionEffect' => sanitize_text_field($_POST['transitionEffect']),
        'transitionSpeed' => sanitize_text_field($_POST['transitionSpeed']),
        'slider1' => sanitize_text_field($_POST['slider1']),
        'slider1Url' => sanitize_text_field($_POST['slider1Url']),
        'slider2' => sanitize_text_field($_POST['slider2']),
        'slider2Url' => sanitize_text_field($_POST['slider2Url']),
        'slider3' => sanitize_text_field($_POST['slider3']),
        'slider3Url' => sanitize_text_field($_POST['slider3Url']),
        'slider4' => sanitize_text_field($_POST['slider4']),
        'slider4Url' => sanitize_text_field($_POST['slider4Url']),
        'slider5' => sanitize_text_field($_POST['slider5']),
        'slider5Url' => sanitize_text_field($_POST['slider5Url']),
        'slider6' => sanitize_text_field($_POST['slider6']),
        'slider6Url' => sanitize_text_field($_POST['slider6Url']),
        'slider7' => sanitize_text_field($_POST['slider7']),
        'slider7Url' => sanitize_text_field($_POST['slider7Url']),
        'slider8' => sanitize_text_field($_POST['slider8']),
        'slider8Url' => sanitize_text_field($_POST['slider8Url']),
        'slider9' => sanitize_text_field($_POST['slider9']),
        'slider9Url' => sanitize_text_field($_POST['slider9Url']),
        'slider10' => sanitize_text_field($_POST['slider10']),
        'slider10Url' => sanitize_text_field($_POST['slider10Url']),
        'slider11' => sanitize_text_field($_POST['slider11']),
        'slider11Url' => sanitize_text_field($_POST['slider11Url']),
    ];
    $themeSettings = new DPTDigitalScreenProcessor($data);
    $themeSettings->process();
}
    $path = plugin_dir_url( __FILE__ ); // I am in Models
    $path .= '../';
?>

<div id="tabs" style="display: none;">
    <ul>
        <li><a href="#tabs-1" data-tab-index="0">Set Prayer Times</a></li>
        <li><a href="#tabs-5" data-tab-index="1">Quick Update Times</a></li>
        <li><a href="#tabs-6" data-tab-index="2">Monitor/mobile Setup</a></li>
        <li><a href="#tabs-3" data-tab-index="4">Hijri Settings</a></li>
        <li><a href="#tabs-4" data-tab-index="3">Theme Settings</a></li>
        <li><a href="#tabs-2" data-tab-index="2">Language Settings</a></li>
        <li><a href="#tabs-7" data-tab-index="7">Misc</a></li>
        <li><a href="#tabs-8" data-tab-index="8">API Doc</a></li>
    </ul>

    <div id="tabs-1" class="wrap" xmlns="http://www.w3.org/1999/html">
        <?php include 'Tabs/CsvUpload.php' ?>
    </div>

    <div id="tabs-2">
        <?php include 'Tabs/ChangeLanguage.php' ?>
    </div>

    <div id="tabs-3">
        <?php include 'Tabs/HijriDate.php' ?>
    </div>

    <div id="tabs-4">
        <?php include 'Tabs/ThemeSettings.php' ?>
    </div>

    <div id="tabs-5">
        <?php include 'Tabs/QuickUpdate.php' ?>
    </div>

    <div id="tabs-6">
        <?php include 'Tabs/DigitalScreen.php' ?>
    </div>

    <div id="tabs-7">
        <?php include 'Tabs/OtherSettings.php' ?>
    </div>
    
    <div id="tabs-8">
        <?php include 'Tabs/APIdoc.php' ?>
    </div>
</div>
    <span class="dpt-donation"><a href="https://donate.uwt.org/Account/Index.aspx" target="_blank">🤲Sadaqa for your grave⚰</a></span>
    <span class="dpt-donation"><a href="https://wordpress.org/support/view/plugin-reviews/daily-prayer-time-for-mosques/reviews/#new-post" target="_blank">💚👍 Like it? </a></span>