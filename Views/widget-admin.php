<?php
require_once(__DIR__.'/../Models/Processors/CsvProcessor.php');
require_once(__DIR__.'/../Models/Processors/LanguageProcessor.php');
require_once(__DIR__.'/../Models/Processors/OtherProcessor.php');
require_once(__DIR__.'/../Models/Processors/AdhanProcessor.php');
require_once(__DIR__.'/../Models/Processors/DebugProcessor.php');
require_once(__DIR__.'/../Models/Processors/HijriProcessor.php');
require_once(__DIR__.'/../Models/Processors/QuickUpdateProcessor.php');
require_once(__DIR__.'/../Models/Processors/ThemeSettingsProcessor.php');
require_once(__DIR__.'/../Models/Processors/DigitalScreenProcessor.php');
require_once(__DIR__.'/../Models/Processors/StartTimeProcessor.php');
require_once(__DIR__.'/../Models/DailyShortCode.php');
require_once(__DIR__.'/../Models/db.php');
require_once(__DIR__.'/../Models/HijriDate.php');
require_once(__DIR__.'/../Models/StartTime/WorldCities.php');
require_once( 'TimetablePrinter.php' );


if (isset($_POST['set-start-time'])  && check_admin_referer( 'csvUpload' )) {
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

if (isset($_POST['submit']) && check_admin_referer( 'csvUpload' )) {
    $csvProcessor = new DPTCsvProcessor($_FILES);

    if ( $csvProcessor->isValidFile() ) {
        $csvProcessor->process();
        delete_transient('nearest_city');
        delete_option('fajr-delay');
        delete_option('zuhr-delay');
        delete_option('asr-delay');
        delete_option('maghrib-delay');
        delete_option('isha-delay');
        delete_option('higher-lat');
        delete_option('calc-method');
        delete_option('asr-method');
    } else {
        echo "<p class='ui-state-error dptCenter'><b>Invalid csv file ?</b>";
        echo "</br>Found: <i>(". $csvProcessor->getFileType() .")</i></p>";
    }
}

if (! empty($_POST['languageSettings'])  && check_admin_referer( 'languageSettings' )) {
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

if (! empty($_POST['hijriSettings']) && check_admin_referer( 'hijriSettings' )) {
    $data = [
        'hijri-chbox' => sanitize_text_field($_POST['hijri-chbox'] ?? ''),
        'hijri-ummul-qura' => sanitize_text_field($_POST['hijri-ummul-qura'] ?? ''),
        'hijri-arabic-chbox' => sanitize_text_field($_POST['hijri-arabic-chbox'] ?? ''),
        'hijri-adjust' => sanitize_text_field($_POST['hijri-adjust']),
        'ramadan_chbox' => sanitize_text_field($_POST['ramadan_chbox'] ?? ''),
        'taraweehDim' => sanitize_text_field($_POST['taraweehDim'] ?? ''),
        'imsaq' => sanitize_text_field($_POST['imsaq'] ?? ''),

    ];
    $hijri = new DPTHijriProcessor($data);
    $hijri->process();
}

if (! empty($_POST['adhanSettings']) && check_admin_referer( 'adhanSettings' )) {
    $data = [
        'activateAdhan' => sanitize_text_field($_POST['activateAdhan'] ?? ''),
        'fajrAdhanUrl' => sanitize_text_field($_POST['fajrAdhanUrl']),
        'otherAdhanUrl' => sanitize_text_field($_POST['otherAdhanUrl']),
        'fajrAdhanBefore' => sanitize_text_field($_POST['fajrAdhanBefore']),
        'zuhrAdhanBefore' => sanitize_text_field($_POST['zuhrAdhanBefore']),
        'asrAdhanBefore' => sanitize_text_field($_POST['asrAdhanBefore']),
        'ishaAdhanBefore' => sanitize_text_field($_POST['ishaAdhanBefore'])
    ];
    $adhanProcessor = new DPTAdhanProcessor($data);
    $adhanProcessor->process();
}

if (! empty($_POST['otherSettings']) && check_admin_referer( 'otherSettings' )) {
    $data = [
        'jumuah1' => sanitize_text_field($_POST['jumuah1']),
        'jumuah2' => sanitize_text_field($_POST['jumuah2']),
        'jumuah3' => sanitize_text_field($_POST['jumuah3']),
        'khutbahDim' => sanitize_text_field($_POST['khutbahDim']),
        'asrSelect' => sanitize_text_field($_POST['asrSelect']),
        'jamah_changes' => sanitize_text_field($_POST['jamah_changes']),
        'zawal' => sanitize_text_field($_POST['zawal']),
        'tomorrow_time' => sanitize_text_field($_POST['tomorrow_time'] ?? ''),
    ];
    $otherProcessor = new DPTOtherProcessor($data);
    $otherProcessor->process();
}

if (! empty($_POST['quickUpdate']) && check_admin_referer( 'quickUpdate' )) {
    $quickUpdateProcessor = new DPTQuickUpdateProcessor($_POST['thisMonth']);
    $quickUpdateProcessor->process();
}

if (! empty($_POST['themeSettings']) && check_admin_referer( 'themeSettings' )) {
    $data = [
        'hideTableBorder' => sanitize_text_field($_POST['hideTableBorder'] ?? ''),
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

if (! empty($_POST['digitalScreen']) && check_admin_referer( 'digitalScreen' )) {
    $data = [
        'ds-logo' => sanitize_text_field($_POST['ds-logo']),
        'ds-scroll-text' => sanitize_text_field($_POST['ds-scroll-text']),
        'ds-scroll-speed' => sanitize_text_field($_POST['ds-scroll-speed']),
        'ds-blink-text' => sanitize_text_field($_POST['ds-blink-text']),
        'ds-fading-msg' => sanitize_text_field($_POST['ds-fading-msg']),
        'ds-additional-css' => sanitize_text_field($_POST['ds-additional-css']),
        'template-chbox' => sanitize_text_field($_POST['template-chbox'] ?? ''),
        'quran-chbox' => sanitize_text_field($_POST['quran-chbox'] ?? ''),
        'slider-chbox' => sanitize_text_field($_POST['slider-chbox'] ?? ''),
        'nextPrayerSlide' => sanitize_text_field($_POST['nextPrayerSlide']),
        'ds-template' => sanitize_text_field($_POST['ds-template'] ?? ''),
        'transitionEffect' => sanitize_text_field($_POST['transitionEffect'] ?? ''),
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

if (! empty($_POST['debugLogSettings']) && check_admin_referer( 'debugLogSettings' )) {
    $data = [
        'debugLog' => sanitize_text_field($_POST['debugLog'] ?? '')
    ];
    $debugProcessor = new DPTdebugProcessor($data);
    $debugProcessor->process();
}

    $path = plugin_dir_url( __FILE__ ); // I am in Views
    $path .= '../';
    $newImage = esc_url( $path . 'Assets/images/new.jpg');
?>
<nav>
  <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Home</button>
    <button class="nav-link" id="nav-quickUpdate-tab" data-bs-toggle="tab" data-bs-target="#nav-quickUpdate" type="button" role="tab" aria-controls="nav-quickUpdate" aria-selected="false">Quick Update</button>
    <button class="nav-link" id="nav-digitalScreen-tab" data-bs-toggle="tab" data-bs-target="#nav-digitalScreen" type="button" role="tab" aria-controls="nav-digitalScreen" aria-selected="false">Digital Screen</button>
    <button class="nav-link" id="nav-hijri-tab" data-bs-toggle="tab" data-bs-target="#nav-hijri" type="button" role="tab" aria-controls="nav-hijri" aria-selected="false">Hijri</button>
    <button class="nav-link" id="nav-theme-tab" data-bs-toggle="tab" data-bs-target="#nav-theme" type="button" role="tab" aria-controls="nav-theme" aria-selected="false">Theme</button>
    <button class="nav-link" id="nav-language-tab" data-bs-toggle="tab" data-bs-target="#nav-language" type="button" role="tab" aria-controls="nav-language" aria-selected="false">Translate</button>
    <button class="nav-link" id="nav-adhan-tab" data-bs-toggle="tab" data-bs-target="#nav-adhan" type="button" role="tab" aria-controls="nav-adhan" aria-selected="false">Adhan</button>
    <button class="nav-link" id="nav-misc-tab" data-bs-toggle="tab" data-bs-target="#nav-misc" type="button" role="tab" aria-controls="nav-misc" aria-selected="false">Misc</button>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab"><?php include 'Tabs/CsvUpload.php' ?></div>
  <div class="tab-pane fade" id="nav-quickUpdate" role="tabpanel" aria-labelledby="nav-quickUpdate-tab"><?php include 'Tabs/QuickUpdate.php' ?></div>
  <div class="tab-pane fade" id="nav-digitalScreen" role="tabpanel" aria-labelledby="nav-digitalScreen-tab"><?php include 'Tabs/DigitalScreen.php' ?></div>
  <div class="tab-pane fade" id="nav-hijri" role="tabpanel" aria-labelledby="nav-hijri-tab"><?php include 'Tabs/HijriDate.php' ?></div>
  <div class="tab-pane fade" id="nav-theme" role="tabpanel" aria-labelledby="nav-theme-tab"><?php include 'Tabs/ThemeSettings.php' ?></div>
  <div class="tab-pane fade" id="nav-language" role="tabpanel" aria-labelledby="nav-language-tab"><?php include 'Tabs/ChangeLanguage.php' ?></div>
  <div class="tab-pane fade" id="nav-adhan" role="tabpanel" aria-labelledby="nav-adhan-tab"><?php include 'Tabs/Adhan.php' ?></div>
  <div class="tab-pane fade" id="nav-misc" role="tabpanel" aria-labelledby="nav-misc-tab"><?php include 'Tabs/OtherSettings.php' ?></div>
</div>
<div class="pt-3">
    <span class="dpt-donation"><a href="https://donate.uwt.org/Account/Index.aspx" target="_blank">Send Sadaqa to my GRAVE</a></span>
    <span class="dpt-donation"><a href="https://wordpress.org/support/view/plugin-reviews/daily-prayer-time-for-mosques/reviews/#new-post" target="_blank">👍 Like it? </a></span>
</div>