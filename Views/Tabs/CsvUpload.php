<?php
    
    $method = 1;
    $year = date('Y');
    $date = strtotime($year. '-1-1');
    $endDate = strtotime(($year+ 1). '-1-1');
    error_log('time Zone: ' . get_option('gmt_offset'));
    error_log('time Zone city: ' . get_option('timezone_string'));
//    while ($date < $endDate)
//    {
//        $times = $prayTime->getPrayerTimes($date, $latitude, $longitude, $timeZone);
//        $day = date('M d', $date);
//        print $day. "\t". implode("\t", $times). "\n";
//        $date += 24* 60* 60;  // next day
//    }
    
    $prayTime = new PrayTime($method);
    
    
    
    
    
$adapter = new DatabaseConnection();
$rows = $adapter->getRows();
$header = (array_keys($rows[1]));
$writeFile = plugin_dir_path(__FILE__) . '../../Assets/prayer-time-'.date('Y'). '.csv';
try {
    $f = fopen($writeFile, "w");
    fputcsv($f, $header);
    foreach ($rows as $line) {
        fputcsv($f, array_values($line));
    }
    fclose($f);
    $readFile = plugins_url('../../Assets/prayer-time-'.date('Y').'.csv', __FILE__);
} catch (\Exception $e) {
    die($e->getMessage());
}
?>
<h3>CSV Upload</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <form enctype="multipart/form-data" name="csvUpload" method="post" action="">
                <div class="row" style="padding-bottom:10px;">
                    <div class="form-group col-sm-6">
                        <input type="file" name="timetable" id="timetable" accept=".csv" class="form-control">
                    </div>
                    <div class="form-group col-sm-6">
                        <input type='submit' name="submit" id="submit" class='button button-primary' value='Upload prayer time'>
                    </div>
                </div>
            </form>
        <div>
            Select your nearest city
            <select class="selectpicker" data-live-search="true">
                <option data-tokens=""></option>
                <option data-tokens="mustard">Burger, Shake and a Smile</option>
                <option data-tokens="frosting">Sugar, Spice and all things nice</option>
            </select>
        </div>
        </div>
        <div class="col-sm-6 col-xs-12 highlight">
            <h3 class="pt-2"><code>INSTRUCTIONS</code></h3>
            <ol>
                <li><a class="url" href="<?= $readFile ?>"> Download current timetable <i class="fa fa-cloud-download" aria-hidden="true"></i></a> (<i>do not change the column heading</i>)</li>
                <li><a class="url" target="_blank" href="https://www.salahtimes.com/search"> Get prayer start time for your city <i class="fa fa-external-link" aria-hidden="true"></i></a></li>
                <li>Valid date formats are:
                    <ul class='green' style="padding-left: 20px;">
                        <li type="disc"> YYYY-MM-DD <i class='smallFont'>(2023-10-20)</i></li>
                        <li type="disc"> YY-MM-DD <i class='smallFont'>(23-10-20)</i></li>
                        <li type="disc"> MM/DD/YYYY <i class='smallFont'>(10/20/2021)</i></li>
                        <li type="disc"> DD-MM-YYYY <i class='smallFont'>(20-10-2023)</i></li>
                        <li type="disc"> DD.MM.YYYY <i class='smallFont'>(13.10.2034)</i></li>
                    </ul>
                </li>
                <li>Valid time format is <span class="red">HH:MM</span> [24 hours]</li>
                <li><a class="url" href="admin.php?page=helps-and-tips">Check Helps and Tips for usage <i class="fa fa-cogs" aria-hidden="true"></i></a></li>
            </ol>
        </div>
    </div>
</div>