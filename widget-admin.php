<?php
require_once('Models/Processors/CsvProcessor.php');
require_once('Models/Processors/LanguageProcessor.php');
require_once('Models/Processors/OtherProcessor.php');
require_once('Models/Processors/HijriProcessor.php');
require_once('Models/Processors/QuickUpdateProcessor.php');
require_once('Models/Processors/ThemeSettingsProcessor.php');

require_once ('Models/HijriDate.php');

ini_set('auto_detect_line_endings', true);

if (isset($_POST['submit'])) {
    $csvProcessor = new CsvProcessor($_FILES);

    if ( $csvProcessor->isValidFile() ) {
        $csvProcessor->process();
    } else {
        echo "<p class='ui-state-error dptCenter'><b>Invalid csv file ?</b>";
        echo "</br>Found: <i>(". $csvProcessor->getFileType() .")</i></p>";
    }
}

if (! empty($_POST['languageSettings'])) {
    $languageProcessor = new LanguageProcessor($_POST);
    $languageProcessor->proocess();
}

if (! empty($_POST['hijriSettings'])) {
    $hijri = new HijriProcessor($_POST);
    $hijri->process();
}

if (! empty($_POST['otherSettings'])) {
    $otherProcessor = new OtherProcessor($_POST);
    $otherProcessor->process();
}

if (! empty($_POST['quickUpdate'])) {
    $otherProcessor = new QuickUpdateProcessor($_POST);
    $otherProcessor->process();
}

if (! empty($_POST['themeSettings'])) {
    $themeSettings = new ThemeSettingsProcessor($_POST);
    $themeSettings->process();
}
?>

<div id="tabs" style="display: none;">
    <ul>
        <li><a href="#tabs-1" data-tab-index="0">Upload Timetable</a></li>
        <li><a href="#tabs-2" data-tab-index="1">Change Language</a></li>
        <li><a href="#tabs-3" data-tab-index="2">Hijri settings</a></li>
        <li><a href="#tabs-4" data-tab-index="3">Theme settings</a></li>
        <li><a href="#tabs-5" data-tab-index="4">Quick Update</a></li>
        <li><a href="#tabs-6" data-tab-index="5">Other settings</a></li>
    </ul>

    <div id="tabs-1" class="wrap" xmlns="http://www.w3.org/1999/html">
        <?php include 'Views/Tabs/CsvUpload.php' ?>
    </div>

    <div id="tabs-2">
        <?php include 'Views/Tabs/ChangeLanguage.php' ?>
    </div>

    <div id="tabs-3">
        <?php include 'Views/Tabs/HijriDate.php' ?>
    </div>

    <div id="tabs-4">
        <?php include 'Views/Tabs/ThemeSettings.php' ?>
    </div>

    <div id="tabs-5">
        <?php include 'Views/Tabs/QuickUpdate.php' ?>
    </div>

    <div id="tabs-6">
        <?php include 'Views/Tabs/OtherSettings.php' ?>
    </div>
</div>
    <span class="dpt-donation"><a href="http://www.uwt.org/" target="_blank">Support The Ummah</a></span>
    <span class="dpt-donation"><a href="https://wordpress.org/support/view/plugin-reviews/daily-prayer-time-for-mosques/reviews/#new-post" target="_blank">Write a Review </a></span>