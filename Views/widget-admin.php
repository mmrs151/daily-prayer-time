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
        'city' => $_POST['city'],
        'method' => $_POST['method'],
        'fajr-delay' => $_POST['fajr-delay'],
        'zuhr-delay' => $_POST['zuhr-delay'],
        'asr-delay' => $_POST['asr-delay'],
        'maghrib-delay' => $_POST['maghrib-delay'],
        'isha-delay' => $_POST['isha-delay'],
        'higher-lat' => $_POST['higher-lat'],
        'calc-method' => $_POST['calc-method'],
        'fajr-angle' => $_POST['fajr-angle'],
        'isha-angle' => $_POST['isha-angle'],
        'isha-angle' => $_POST['isha-angle'],
        'asr-method' => $_POST['asr-method'],
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
        'prayersLocal' => $_POST['prayersLocal'],
        'headersLocal' => $_POST['headersLocal'],
        'monthsLocal' => $_POST['monthsLocal'],
        'numbersLocal' => $_POST['numbersLocal'],
        'timesLocal' => $_POST['timesLocal'],
    ];
    $languageProcessor = new DPTLanguageProcessor($data);
    $languageProcessor->process();
}

if (! empty($_POST['hijriSettings'])) {
    $data = [
        'hijri-chbox' => $_POST['hijri-chbox'],
        'hijri-adjust' => $_POST['hijri-adjust']
    ];
    $hijri = new DPTHijriProcessor($data);
    $hijri->process();
}

if (! empty($_POST['otherSettings'])) {
    $data = [
        'jumuah' => $_POST['jumuah'],
        'ramadan-chbox' => $_POST['ramadan-chbox'],
        'asrSelect' => $_POST['asrSelect'],
        'jamah_changes' => $_POST['jamah_changes'],
        'imsaq' => $_POST['imsaq'],
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
        'hideTableBorder' => $_POST['hideTableBorder'],
        'tableBackground' => $_POST['tableBackground'],
        'tableHeading' => $_POST['tableHeading'],
        'tableHeadingFont' => $_POST['tableHeadingFont'],
        'evenRow' => $_POST['evenRow'],
        'fontColor' => $_POST['fontColor'],
        'highlight' => $_POST['highlight'],
        'notificationBackground' => $_POST['notificationBackground'],
        'notificationFont' => $_POST['notificationFont'],
        'prayerName' => $_POST['prayerName'],
        'prayerNameFont' => $_POST['prayerNameFont'],
        'digitalScreenRed' => $_POST['digitalScreenRed'],
        'digitalScreenLightRed' => $_POST['digitalScreenLightRed'],
        'digitalScreenGreen' => $_POST['digitalScreenGreen'],
        'digitalScreenPrayerName' => $_POST['digitalScreenPrayerName'],
    ];
    $themeSettings = new ThemeSettingsProcessor($data);
    $themeSettings->process();
}

if (! empty($_POST['digitalScreen'])) {
    $data = [
        'ds-logo' => $_POST['ds-logo'],
        'slider-chbox' => $_POST['slider-chbox'],
        'nextPrayerSlide' => $_POST['nextPrayerSlide'],
        'transitionEffect' => $_POST['transitionEffect'],
        'transitionSpeed' => $_POST['transitionSpeed'],
        'slider1' => $_POST['slider1'],
        'slider1Url' => $_POST['slider1Url'],
        'slider2' => $_POST['slider2'],
        'slider2Url' => $_POST['slider2Url'],
        'slider3' => $_POST['slider3'],
        'slider3Url' => $_POST['slider3Url'],
        'slider4' => $_POST['slider4'],
        'slider4Url' => $_POST['slider4Url'],
        'slider5' => $_POST['slider5'],
        'slider5Url' => $_POST['slider5Url'],
        'slider6' => $_POST['slider6'],
        'slider6Url' => $_POST['slider6Url'],
        'slider7' => $_POST['slider7'],
        'slider7Url' => $_POST['slider7Url'],
        'slider8' => $_POST['slider8'],
        'slider8Url' => $_POST['slider8Url'],
        'slider9' => $_POST['slider9'],
        'slider9Url' => $_POST['slider9Url'],
        'slider10' => $_POST['slider10'],
        'slider10Url' => $_POST['slider10Url'],
        'slider11' => $_POST['slider11'],
        'slider11Url' => $_POST['slider11Url'],
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
    <span class="dpt-donation"><a href="http://www.uwt.org/" target="_blank">Support The Ummah</a></span>
    <span class="dpt-donation"><a href="https://wordpress.org/support/view/plugin-reviews/daily-prayer-time-for-mosques/reviews/#new-post" target="_blank">Write a Review </a></span>