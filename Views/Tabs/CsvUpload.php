<?php
$worldCities = new WorldCities();
$cities = $worldCities->getCities();
$adapter = new DatabaseConnection();
$rows = $adapter->getRows();

if ( isset($rows[1])) {
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
}
?>
<h3 style="padding-bottom: 30px;" xmlns="http://www.w3.org/1999/html">Set Prayer Times Automatically</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <form enctype="multipart/form-data" id="csvUpload" name="csvUpload" method="post" action="">
                <div class="upload-step">
                    <label>Select your nearest city:</label>
                    <select class="form-select" data-live-search="true" name="city">
                        <option></option>
                        <?php                    
                        foreach ($cities as $city) {
                            $selected = $city['id'] == get_transient('nearest_city') ? "selected" : null;
                            echo "<option class='auto-settings' value=" . esc_attr($city['id']) . "  ". $selected.">" . esc_html($city["country"]) . ", " . esc_html($city['city']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="upload-step">
                    <label>Select calculation method:</label>
                    <select class="form-select" name="method" id="calculationMethod">
                        <option value="0" <?php if (get_option('calc-method') == 0){echo "selected='selected'";}?>>Ithna Ashari</option>
                        <option value="1" <?php if (get_option('calc-method') == 1){echo "selected='selected'";}?>>University of Islamic Sciences, Karachi</option>
                        <option value="2" <?php if (get_option('calc-method') == 2){echo "selected='selected'";}?>>Islamic Society of North America (ISNA)</option>
                        <option value="3" <?php if (get_option('calc-method') == 3){echo "selected='selected'";}?>>Muslim World League (MWL)</option>
                        <option value="4" <?php if (get_option('calc-method') == 4){echo "selected='selected'";}?>>Umm al-Qura, Makkah</option>
                        <option value="5" <?php if (get_option('calc-method') == 5){echo "selected='selected'";}?>>Egyptian General Authority of Survey</option>
                        <option value="7" <?php if (get_option('calc-method') == 7){echo "selected='selected'";}?>>Institute of Geophysics, University of Tehran</option>
                        <option value="6" <?php if (get_option('calc-method') == 6){echo "selected='selected'";}?>>Custom Fajr and Isha angle</option>
                    </select>
                </div>
                <div class="upload-step" id="customMethod" style="background: #f1f1f1; display: none;">
                    <input type="number" value="<?php echo esc_html( get_option('fajr-angle') )?>" placeholder="Fajr angle" name="fajr-angle" style="margin: 20px 0px 0px 30px">
                    <input type="number" value="<?php echo esc_html( get_option('isha-angle') )?>" placeholder="Isha angle" name="isha-angle">
                </div>
                <div class="upload-step">
                    <label>Select Asr juristic method:</label>
                    <select class="form-select" name="asr-method">
                        <option value="0" <?php if (get_option('asr-method') == 0){echo "selected='selected'";}?>>Standard</option>
                        <option value="1" <?php if (get_option('asr-method') == 1){echo "selected='selected'";}?>>Hanafi</option>
                    </select>
                </div>
                <div class="upload-step">
                    <label>Adjusting Methods for Higher Latitudes:</label>
                    <select class="form-select" name="higher-lat">
                        <option></option>
                        <option value="3" <?php if (get_option('higher-lat') == 3){echo "selected='selected'";}?>>Angle Based</option>
                        <option value="2" <?php if (get_option('higher-lat') == 2){echo "selected='selected'";}?>>One Seventh of the Night</option>
                        <option value="1" <?php if (get_option('higher-lat') == 1){echo "selected='selected'";}?>>Middle of the Night</option>
                    </select>
                </div>
                <div class="upload-step">
                    <label>Fajr Iqamah delay:</label><input type="number" name="fajr-delay" min="1" max="120" value="<?php echo esc_html( get_option('fajr-delay') )?>"> mins
                </div>
                <div class="upload-step">
                    <label>Dhuhr Iqamah delay:</label><input type="number" name="zuhr-delay" min="1" max="120" value="<?php echo esc_html( get_option('zuhr-delay') )?>"> mins
                </div>
                <div class="upload-step">
                    <label>Asr Iqamah delay:</label><input type="number" name="asr-delay" min="1" max="120" value="<?php echo esc_html( get_option('asr-delay') )?>"> mins
                </div>
                <div class="upload-step">
                    <label>Maghrib Iqamah delay:</label><input type="number" name="maghrib-delay" min="1" max="120" value="<?php echo esc_html( get_option('maghrib-delay') )?>"> mins
                </div>
                <div class="upload-step">
                    <label>Isha Iqamah delay:</label><input type="number" name="isha-delay" min="1" max="120" value="<?php echo esc_html( get_option('isha-delay') )?>"> mins
                </div>
                <div class="upload-step">
                    <input type='submit' name="set-start-time" id="set-start-time" class='button button-primary' value='Set Prayer Time'>
                </div>
        </div>
        <div class="col-sm-6 col-xs-12 highlight">
            <h3>Upload Prayer Times Manually</h3>
            <div class="row" style="padding-bottom:10px;">
                <div class="form-group col-sm-6">
                    <input type="file" name="timetable" id="timetable" accept=".csv" class="form-control">
                </div>
                <div class="form-group col-sm-6">
                    <input type='submit' name="submit" id="submit" class='button button-primary' value='Upload prayer time'>
                </div>
            </div>
            <h3 class="pt-2"><code>INSTRUCTIONS</code></h3>
            <ol>
                <li><a class="url" href="<?php echo  $readFile ?>"> Download current timetable <i class="fa fa-cloud-download" aria-hidden="true"></i></a> (<i>do not change the column heading</i>)</li>
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
            </form>
        </div>
    </div>
</div>