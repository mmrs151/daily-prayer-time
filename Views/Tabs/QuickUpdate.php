<?php
require_once(__DIR__ .'/../../Models/db.php');

$quickMonth = sanitize_text_field($_POST["quickMonth"]);
$quickMonth = $quickMonth ?: date('m');
$monthName = date('F');
$futureMonths = [];
for($i=date('m'); $i<=12; $i++) {
    $dateObj   = DateTime::createFromFormat('!m', $i);
    $futureMonths[$i] = $dateObj->format('F');
}

$options = "";
foreach($futureMonths as $key=>$month_name){
    $selected = "";
    if($key == $quickMonth){
        $selected = " selected='selected'";
    }
    $options .= "<option value='" . esc_attr($key) . "' $selected>". esc_html($month_name) . "</option>";
}

$db = new DatabaseConnection();
$data = $db->getPrayerTimeForMonth( $quickMonth );

$prayerNames = $timetable->getLocalPrayerNames();

if ( empty($data)) {
    $msg = '<h3>Please upload prayer time to use this page ';
    echo $msg . '</h3>';
} else {
    echo "
<div class='container-fluid'>
    <form name='quickUpdateMonth' method='post'>
        <div class='row font-weight-bold' style='padding-bottom: 10px;'>
            <div class='col-sm-4' style='padding-left:0px;'><p class='h4'>Update Iqamah time for:</p> </div>
            <div class='col-sm-2'>
                <select name='quickMonth' class='form-select-sm quickMonth'>
                     ". $options ." 
                </select>               
            </div> 
            <div class='col-sm-2'>
                <input type='submit' value='Load month' class='btn btn-success' style='float: left; margin-right: 20px;'>
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
                    <th>". esc_html($prayerNames['fajr']) ."</th>
                    <th>". esc_html($prayerNames['zuhr']) ."</th>
                    <th>". esc_html($prayerNames['asr']) ."</th>
                    <th>". esc_html($prayerNames['maghrib']) ."</th>
                    <th>". esc_html($prayerNames['isha']) ."</th>
                </tr>
            </thead>
    ";

    foreach ($data as $key => $value) {
        $date = $value['d_date'];
        $displayDate = date("M d", strtotime($date));
        $todayDate = date("M d", strtotime(date('Y-m-d')));
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
                <td><input type='time' name='thisMonth[".$key."][fajr_jamah]' value=". date('H:i', strtotime(esc_attr($value['fajr_jamah']))) ." ></td>
                <td><input type='time' name='thisMonth[".$key."][zuhr_jamah]' value=". date('H:i', strtotime(esc_attr($value['zuhr_jamah']))) ." ></td>
                <td><input type='time' name='thisMonth[".$key."][asr_jamah]' value=". date('H:i', strtotime(esc_attr($value['asr_jamah']))) ."></td>
                <td><input type='time' name='thisMonth[".$key."][maghrib_jamah]' value=". date('H:i', strtotime(esc_attr($value['maghrib_jamah']))) ." ></td>
                <td><input type='time' name='thisMonth[".$key."][isha_jamah]' value=". date('H:i', strtotime(esc_attr($value['isha_jamah']))) ." ></td>
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