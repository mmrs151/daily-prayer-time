<?php
    $html = '';
    $sunriseOrZawal = $this->dptHelper->getSunriseOrZawalOrIshraq($this->row);
    $nextPrayer = $this->dptHelper->getNextPrayer($this->row);
?>

<div class="my-masjid">

    <!--BANNER SECTION - Next Prayer Display -->
    <div class="banner-section">
        <div class="next-prayer-label">Next Prayer</div>
        <div class="next-prayer-name"><?php echo $this->getLocalPrayerNames()[$nextPrayer] ?? ucfirst($nextPrayer); ?></div>
        <div class="time-remaining">
            <span class="dptScTime">00:00:00</span>
        </div>
    </div>

    <!-- PRAYER TIMES TABLE -->
    <div class="prayer-table">

        <!-- Fajr -->
        <div class="prayer-row <?php echo $this->getNextPrayerClass('fajr', $this->row, true); ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['fajr']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo do_shortcode("[fajr_start]"); ?></span>
                <span class="iqamah-time"><?php echo do_shortcode("[fajr_prayer]"); ?></span>
            </div>
        </div>

        <!-- Sunrise / Zawal / Ishraq -->
        <div class="prayer-row <?php echo $this->getNextPrayerClass($sunriseOrZawal, $this->row); ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()[$sunriseOrZawal]; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo do_shortcode("[$sunriseOrZawal]"); ?></span>
            </div>
        </div>

        <!-- Zuhr -->
        <div class="prayer-row <?php echo $this->getNextPrayerClass('zuhr', $this->row); ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['zuhr']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo do_shortcode("[zuhr_start]"); ?></span>
                <span class="iqamah-time"><?php echo do_shortcode("[zuhr_prayer]"); ?></span>
            </div>
        </div>

        <!-- Asr -->
        <div class="prayer-row <?php echo $this->getNextPrayerClass('asr', $this->row); ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['asr']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo do_shortcode("[asr_start]"); ?></span>
                <span class="iqamah-time"><?php echo do_shortcode("[asr_prayer]"); ?></span>
            </div>
        </div>

        <!-- Maghrib -->
        <div class="prayer-row <?php echo $this->getNextPrayerClass('maghrib', $this->row); ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['maghrib']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo do_shortcode("[maghrib_start]"); ?></span>
                <span class="iqamah-time"><?php echo do_shortcode("[maghrib_prayer]"); ?></span>
            </div>
        </div>

        <!-- Isha -->
        <div class="prayer-row <?php echo $this->getNextPrayerClass('isha', $this->row); ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['isha']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo do_shortcode("[isha_start]"); ?></span>
                <span class="iqamah-time"><?php echo do_shortcode("[isha_prayer]"); ?></span>
            </div>
        </div>

    </div>

</div>