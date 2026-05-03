<?php 
$html = '';
$sunriseOrZawal = $this->dptHelper->getSunriseOrZawalOrIshraq($this->row);
$nextPrayer = $this->dptHelper->getNextPrayer($this->row);
?>

<div class="my-masjid">

    <!--BANNER SECTION - Next Prayer Display -->
    <div class="banner-section">
        <?php echo $this->getHiddenVariables(); ?>
        <div class="next-prayer-label">Next Prayer</div>
        <div class="next-prayer-name"><?php echo $this->getLocalPrayerNames()[$nextPrayer] ?? ucfirst($nextPrayer); ?></div>
        <div class="time-remaining">
            <span class="dptScTime" id="nextPrayerCountdown">00:00:00</span>
        </div>
    </div>

    <!-- PRAYER TIMES TABLE -->
    <div class="prayer-table">

        <!-- Fajr -->
        <div class="prayer-row <?php echo $this->dptHelper->getNextPrayerClass('fajr', $this->row, true) ? 'current' : ''; ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['fajr']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo do_shortcode("[fajr_start]"); ?></span>
                <span class="iqamah-time"><?php echo do_shortcode("[fajr_prayer]"); ?></span>
            </div>
        </div>

        <!-- Sunrise / Zawal / Ishraq -->
        <div class="prayer-row <?php echo $this->dptHelper->getNextPrayerClass($sunriseOrZawal, $this->row) ? 'current' : ''; ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()[$sunriseOrZawal]; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo do_shortcode("[{$sunriseOrZawal}]"); ?></span>
                <span class="iqamah-time"></span>
            </div>
        </div>

        <!-- Zuhr -->
        <div class="prayer-row <?php echo $this->dptHelper->getNextPrayerClass('zuhr', $this->row) ? 'current' : ''; ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['zuhr']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo do_shortcode("[zuhr_start]"); ?></span>
                <span class="iqamah-time"><?php echo do_shortcode("[zuhr_prayer]"); ?></span>
            </div>
        </div>

        <!-- Asr -->
        <div class="prayer-row <?php echo $this->dptHelper->getNextPrayerClass('asr', $this->row) ? 'current' : ''; ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['asr']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo do_shortcode("[asr_start]"); ?></span>
                <span class="iqamah-time"><?php echo do_shortcode("[asr_prayer]"); ?></span>
            </div>
        </div>

        <!-- Maghrib -->
        <div class="prayer-row <?php echo $this->dptHelper->getNextPrayerClass('maghrib', $this->row) ? 'current' : ''; ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['maghrib']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo do_shortcode("[maghrib_start]"); ?></span>
                <span class="iqamah-time"><?php echo do_shortcode("[maghrib_prayer]"); ?></span>
            </div>
        </div>

        <!-- Isha -->
        <div class="prayer-row <?php echo $this->dptHelper->getNextPrayerClass('isha', $this->row) ? 'current' : ''; ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['isha']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo do_shortcode("[isha_start]"); ?></span>
                <span class="iqamah-time"><?php echo do_shortcode("[isha_prayer]"); ?></span>
            </div>
        </div>

    </div>

</div>