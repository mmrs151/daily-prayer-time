<?php
use IslamicNetwork\PrayerTimes\Method;

$loadCities = false;
$worldCities = new WorldCities();
$city = $worldCities->getCityById(get_transient('nearest_city'));

if (!empty($_POST['activate-automatic'])) {
    $loadCities = true;
    $cities = $worldCities->getCities();
}

$adapter = new DatabaseConnection();
$rows = $adapter->getRows();

if ( isset($rows[1])) {
    $header = (array_keys($rows[1]));

    $writeFile = plugin_dir_path(__FILE__) . '../../Assets/prayer-time-latest.csv';
    try {
        $f = fopen($writeFile, "w");
        fputcsv($f, $header);
        foreach ($rows as $line) {
            fputcsv($f, array_values($line));
        }
        fclose($f);
        $readFile = plugins_url('../../Assets/prayer-time-latest.csv', __FILE__);
    } catch (\Exception $e) {
        die($e->getMessage());
    }
}
?>
    <h2 class="eict-donation">
        <marquee width=250 behavior='alternate'>
            <a target="_new" 
            href="https://www.justgiving.com/page/muhammad-rahman-151"> 
                URGENT APPEAL
            </a>
        </marquee>
    </h2>
<div class="container-fluid">
    <form enctype="multipart/form-data" id="csvUpload" name="csvUpload" method="post" action="">
    <?php echo wp_nonce_field( 'csvUpload'); ?>
    <div class="row">
        <div class="col-sm-6 col-xs-6 automatic">
            <div class="head">
                <h3 style="padding-bottom: 30px;" xmlns="http://www.w3.org/1999/html">Set Prayer Times Automatically
                    <input type="submit" name="activate-automatic" class="button button-primary" value="Load Cities" onclick="window.location.href='admin.php?page=dpt'">
                <sub><p style="color: red;">(Please check and <a class="url" href="admin.php?page=dpt#tabs-2">Quick Update</a> before publishing)</p></sub></h3>
            </div>
            <div class="upload-step">
                <label>Select your nearest city:</label>
                    <select class="form-select" data-live-search="true" name="city">
                        <option><?php echo $city ?></option>
                        <?php
                        if ($loadCities) {
                            foreach ($cities as $city) {
                                $selected = $city['id'] == get_transient('nearest_city') ? "selected" : null;
                                echo "<option
                                class='auto-settings'
                                value=" . esc_attr($city['id']) . "  ". $selected.">" .
                                    esc_html($city["country"]) . ", " . esc_html($city['city']) . " (Lat:" . esc_html($city['lat']) .")
                                </option>";
                            }
                        }
                        ?>
                    </select>
            </div>
        <div class="automaticPrayerTime">
                <div class="upload-step">
                    <label>Select calculation method:</label>
                    <select class="form-select" name="method" id="calculationMethod">
                        <?php
                        foreach (Method::getMethodCodes() as $key => $method) {
                            $selected = get_option('calc-method') == $key ? "selected" : null;
                            echo "<option value=" . $key . " " . $selected . ">" . $method . "</option>";
                        }
                        ?>
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
        </div>
        <div class="col-sm-6 col-xs-6 manual" style="background: #eeeeee;">
            <h3 class="pb-2">Upload Prayer Times Manually</h3>
            <div class="row pb-2">
                <div class="form-group col-sm-6 col-xs-6">
                    <input type="file" name="timetable" id="timetable" accept=".csv" class="form-control">
                </div>
                <div class="form-group col-sm-6 col-xs-6">
                    <input type='submit' name="submit" id="submit" class='button button-primary' value='Upload prayer time'>
                </div>
            </div>
            <div class="instructions">
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
            </div>
        </div>
    </div>
    </form>
</div>