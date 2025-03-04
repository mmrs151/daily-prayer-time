<?php
$nextPrayer = ucfirst($this->getNextPrayer($row));
foreach ($this->localPrayerNames as $name) {
    if ($nextPrayer == $name) {
        $highlight = 'highlight';
    }
}
if(isset($row['announcement']) && ! empty( $row['announcement'] )) {
    $announcement = "<tr><th colspan='7' style='text-align:center' class='notificationBackground notificationFont'>".$row['announcement']. "</th></tr>";
}
$sunriseOrZawal = $this->dptHelper->getSunriseOrZawal($row);
if ($sunriseOrZawal == 'zawal') {
    $sunriseOrZawalTime = $this->dptHelper->getZawalTime($row['zuhr_begins']);
} else {
    $sunriseOrZawalTime = $this->formatDateForPrayer($row['sunrise']);
}
?>


<div class="dpt-horizontal-wrapper customStyles">
    <div class="dpt-heading">
        <h3 class="date side-by-side">
            <?php
                echo esc_html($row['widgetTitle']) .
                ' ' . date_i18n( get_option( 'date_format' ) );
                 if($row['displayHijriDate']) echo ' - '. $this->hijriDate->getDate(date("d"), date("m"), date("Y"), true)
            ?>
        </h3>
        <h3 class="timetable-link side-by-side">
            <a href="/monthly">Full Timetable <i class="fa fa-chevron-right"></i></a>
        </h3>
    </div>
    <div class="dpt-wrapper-container">

        <div class="prayer-time prayer-fajr <?php if ($nextPrayer == $this->localPrayerNames['fajr']) echo "highlight"; ?>">
        <span class="iconify-inline icon" data-icon="lucide:sunrise"></span>

            <h3 id="fajrRamadhan"><?php echo esc_html($this->localPrayerNames['fajr']) ?></h3>
            <div
                class="prayer-start">
                <?php echo  esc_html( $this->formatDateForPrayer($row["fajr_begins"]) );?>
            </div>
            <div class="prayer-jamaat"><?php echo  esc_html( $this->formatDateForPrayer($row["fajr_jamah"]) );?></div>

        </div> <!-- END of prayer time-->
        <div class="prayer-time prayer-sunrise <?php if ($nextPrayer == ucfirst($sunriseOrZawal)) echo "highlight"; ?>">
        <span class="iconify-inline icon" data-icon="bi:sunrise-fill"></span>

            <h3><?php echo esc_html( ucfirst($sunriseOrZawal) )?></h3>
            <div class="prayer-jamaat"><?php echo  esc_html( $sunriseOrZawalTime );?></div>
            <div>&nbsp;</div>

        </div> <!-- END of prayer time-->
        <div class="prayer-time prayer-dhuhr <?php if ('nextPrayer' ==  $this->getNextPrayerClass('zuhr', $row)) echo "highlight"; ?>">
        <span class="iconify-inline icon" data-icon="emojione:sun"></span>

            <h3><?php echo esc_html( $this->localPrayerNames['zuhr'] )?></h3>
            <div class="prayer-start"><?php echo  esc_html( $this->formatDateForPrayer($row["zuhr_begins"]) );?></div>
            <div class="prayer-jamaat"><?php echo  esc_html( $this->formatDateForPrayer($row["zuhr_jamah"]) );?></div>

        </div> <!-- END of prayer time-->
        <div class="prayer-time prayer-asr <?php if ($nextPrayer == $this->localPrayerNames['asr']) echo "highlight"; ?>">
        <span class="iconify-inline icon" data-icon="bi:sun"></span>

            <h3><?php echo esc_html( $this->localPrayerNames['asr'] )?></h3>
            <div class="prayer-start"><?php echo  esc_html( $this->formatDateForPrayer($row["asr_begins"]) );?></div>
            <div class="prayer-jamaat"><?php echo  esc_html( $this->formatDateForPrayer($row["asr_jamah"]) );?></div>

        </div> <!-- END of prayer time-->
        <div class="prayer-time prayer-maghrib <?php if ($nextPrayer == $this->localPrayerNames['maghrib']) echo "highlight"; ?>">
        <span class="iconify-inline icon" data-icon="carbon:sunset"></span>

            <h3 id="maghribRamadhan"><?php echo esc_html( $this->localPrayerNames['maghrib'] )?></h3>
            <div class="prayer-start"><?php echo  esc_html( $this->formatDateForPrayer($row["maghrib_begins"]) );?></div>
            <div class="prayer-jamaat"><?php echo  esc_html( $this->formatDateForPrayer($row["maghrib_jamah"]) );?></div>

        </div> <!-- END of prayer time-->
        <div class="prayer-time prayer-isha <?php if ($nextPrayer == $this->localPrayerNames['isha']) echo "highlight"; ?>">
        <span class="iconify-inline icon" data-icon="bi:moon-stars-fill"></span>

            <h3><?php echo esc_html( $this->localPrayerNames['isha'] )?></h3>
            <div class="prayer-start"><?php echo  esc_html( $this->formatDateForPrayer($row["isha_begins"]) );?></div>
            <div class="prayer-jamaat"><?php echo  esc_html( $this->formatDateForPrayer($row["isha_jamah"]) );?></div>

        </div> <!-- END of prayer time-->
        <div class="prayer-time prayer-jumuah <?php if ('nextPrayer' ==  $this->getNextPrayerClass('jumuah', $row)) echo "highlight"; ?>">
            <span class="iconify-inline icon" data-icon="fa-solid:mosque""></span>

            <h3><?php echo esc_html( $this->headersLocal['jumuah'] )?></h3>
            <div class="prayer-jamaat"><?php echo   $this->getJumuahTimesArray(true);?></div>
            <div>&nbsp;</div>

        </div> <!-- END of prayer time-->

    </div> <!-- END of wrapper container-->

<?php if(isset($row['announcement']) && ! empty( $row['announcement'] )) {?>
    <div class="dpt-announcement"><h3><?php echo  $row['announcement'] ?></h3></div>
<?php } ?>
</div>

<?php

if ( $this->dptHelper->isRamadan() && ! $row['hideRamadan']) { ?>

<script>

(function(){
    var words = [
        'Fajr',
        'Suhoor',
        ], i = 0;
    setInterval(function(){
        jQuery('#fajrRamadhan').fadeOut(function(){
            jQuery(this).html(words[i=(i+1)%words.length]).fadeIn();
        });
    }, 3000);

})();

(function(){
    var words = [
        'Maghrib',
        'Iftaar',
        ], i = 0;
    setInterval(function(){
        jQuery('#maghribRamadhan').fadeOut(function(){
            jQuery(this).html(words[i=(i+1)%words.length]).fadeIn();
        });
    }, 3000);

})();
</script>

<?php } ?>


