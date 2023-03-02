<?php
require_once(__DIR__ .'/../../Models/db.php');

$db = new DatabaseConnection();

$quickMonth = date('m');
$quickYear = date('Y');

if (isset($_POST['action']) && $_POST['action'] == 'RESET YEARS') {
    echo "... deleated all other years. Only current year available now.";
    $db->resetOtherYears();
}

if ( isset($_POST['action']) && $_POST['action'] == 'Load Month') {
    $quickMonth = (int)sanitize_text_field($_POST["quickMonth"]);
    preg_match("/\d+/", $quickMonth, $match);
    $quickMonth = $match[0];
}

if ( isset($_POST["quickYear"])) {
    $quickYear = (int)sanitize_text_field($_POST["quickYear"]);
    preg_match("/\d+/", $quickYear, $match);
    $quickYear = $match[0];
}

$monthName = date('F');
$futureMonths = [];
for($i=1; $i<=12; $i++) {
    $dateObj = DateTime::createFromFormat('!m', $i);
    $futureMonths[$i] = $dateObj->format('F');
}

$optionsMonth = "";
foreach($futureMonths as $key=>$month_name){
    $selected = "";
    if($key == $quickMonth){
        $selected = " selected='selected'";
    }
    $optionsMonth .= "<option value='" . esc_attr($key) . "' $selected>". esc_html($month_name) . "</option>";
}

$years = $db->getYers();
$optionsYear = "";
foreach($years as $year){
    $year = $year['year'];
    $selected = "";
    if($year == $quickYear){
        $selected = " selected='selected'";
    }
    $optionsYear .= "<option value='" . esc_attr($year) . "' $selected>". esc_html($year) . "</option>";
}

$data = $db->getPrayerTimeForMonth( $quickMonth, $quickYear );

$timetable = new TimetablePrinter();
$prayerNames = $timetable->getLocalPrayerNames();

if ( empty($data)) {
    $msg = '<h3>Please upload prayer time to use this page ';
    echo $msg . '</h3>';
} else {
    echo "
<div class='container-fluid'>
    <form name='quickUpdateMonth' method='post'>
        <div class='row font-weight-bold' style='padding-bottom: 10px;'>
            <div class='col-sm-3' style='padding-left:0px;'><p class='h4'>Update Prayer time for:</p> </div>
            <div class='col-sm-2'>
                <select name='quickMonth' class='form-select-sm quickMonth'>
                     ". $optionsMonth ." 
                </select>               
            </div> 
            <div class='col-sm-2'>
                <select name='quickYear' class='form-select-sm quickMonth'>
                     ". $optionsYear ." 
                </select>               
            </div> 
            <div class='col-sm-4'>
                <input type='submit' name='action' value='Load Month' class='btn btn-success' style='float: left; margin-right: 20px;'>
                <input type='submit' name='action' value='RESET YEARS' class='btn btn-danger' title='Remove other years, only keep current year'  style='float: left; margin-right: 20px;'>
                </div>
        </div>
    </form>
    <div class='row'>
    <form name='quickUpdate' method='post'>        
        <table class='table table-condensed '>
            <thead class='bg-success text-white'>
                <tr>
                    <th>DATE</th>
                    <th>DAY</th>
                    <th>Start</th>
                    <th>". esc_html($prayerNames['fajr']) ."</th>
                    <th>". esc_html($prayerNames['sunrise']) ."</th>
                    <th>Start</th>
                    <th>". esc_html($prayerNames['zuhr']) ."</th>
                    <th>Start</th>
                    <th>". esc_html($prayerNames['asr']) ."</th>
                    <th>Start</th>
                    <th>". esc_html($prayerNames['maghrib']) ."</th>
                    <th>Start</th>
                    <th>". esc_html($prayerNames['isha']) ."</th>
                </tr>
            </thead>
    ";

    foreach ($data as $key => $value) {
        $date = $value['d_date'];
        $displayDate = date("m/d", strtotime($date));
        $todayDate = date("m/d", strtotime(date('Y-m-d')));
        $today = '';
        if ($displayDate == $todayDate) {
            $today = 'highlight';
        } else {
            $today = '';
        }
        $weekday = date("D", strtotime($date));
        echo "
            <tr class=" . $today . ">
                <td><b>". $displayDate ."</b></td>
                <td class=" . $weekday . "><b>". $weekday ."</b></td>

                <input type='hidden' name='thisMonth[".$key."][d_date]' value=". $date ." >

                <td><input class='qtime' name='thisMonth[".$key."][fajr_begins]' value=". date('H:i', strtotime(esc_attr($value['fajr_begins']))) ." ></td>
                <td class='dptMonthlyIqamah'><input class='qtime' name='thisMonth[".$key."][fajr_jamah]' value=". date('H:i', strtotime(esc_attr($value['fajr_jamah']))) ." ></td>
                <td><input class='qtime' name='thisMonth[".$key."][sunrise]' value=". date('H:i', strtotime(esc_attr($value['sunrise']))) ." ></td>

                <td><input class='qtime' name='thisMonth[".$key."][zuhr_begins]' value=". date('H:i', strtotime(esc_attr($value['zuhr_begins']))) ." ></td>
                <td class='dptMonthlyIqamah'><input class='qtime' name='thisMonth[".$key."][zuhr_jamah]' value=". date('H:i', strtotime(esc_attr($value['zuhr_jamah']))) ." ></td>

                <td><input class='qtime' name='thisMonth[".$key."][asr_begins]' value=". date('H:i', strtotime(esc_attr($value['asr_mithl_1']))) ."></td>
                <td class='dptMonthlyIqamah'><input class='qtime' name='thisMonth[".$key."][asr_jamah]' value=". date('H:i', strtotime(esc_attr($value['asr_jamah']))) ."></td>

                <td><input class='qtime' name='thisMonth[".$key."][maghrib_begins]' value=". date('H:i', strtotime(esc_attr($value['maghrib_begins']))) ." ></td>
                <td class='dptMonthlyIqamah'><input class='qtime' name='thisMonth[".$key."][maghrib_jamah]' value=". date('H:i', strtotime(esc_attr($value['maghrib_jamah']))) ." ></td>

                <td><input class='qtime' name='thisMonth[".$key."][isha_begins]' value=". date('H:i', strtotime(esc_attr($value['isha_begins']))) ." ></td>
                <td class='dptMonthlyIqamah'><input class='qtime' name='thisMonth[".$key."][isha_jamah]' value=". date('H:i', strtotime(esc_attr($value['isha_jamah']))) ." ></td>

            </tr>
        ";
    }
    echo "
        </table>
            <input type='submit' name='quickUpdate' id='quickUpdate' class='btn btn-success' value='Update changes'>
        </form>
        </div>
        </div>
    ";
}
