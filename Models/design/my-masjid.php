<?php
    $html = '';
    $sunriseOrZawal = $this->dptHelper->getSunriseOrZawalOrIshraq($this->row);
    $nextPrayer = $this->dptHelper->getNextPrayer($this->row);
    $nextPrayerClass = $this->getNextPrayerClass($nextPrayer, $this->row);
    $now = date('H:i');
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
        <div class="prayer-row <?php echo $this->getNextPrayerClass('fajr', $this->row, true) ? 'current' : ''; ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['fajr']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo $this->formatTime($this->row['fajr_begins']); ?></span>
                <span class="iqamah-time"><?php echo $this->formatTime($this->row['fajr_jamah']); ?></span>
            </div>
        </div>

        <!-- Sunrise / Zawal / Ishraq -->
        <div class="prayer-row <?php echo $this->getNextPrayerClass($sunriseOrZawal, $this->row) ? 'current' : ''; ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()[$sunriseOrZawal]; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo $this->formatTime($this->row['sunrise']); ?></span>
                <span class="iqamah-time"></span>
            </div>
        </div>

        <!-- Zuhr -->
        <div class="prayer-row <?php echo $this->getNextPrayerClass('zuhr', $this->row) ? 'current' : ''; ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['zuhr']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo $this->formatTime($this->row['zuhr_begins']); ?></span>
                <span class="iqamah-time"><?php echo $this->formatTime($this->row['zuhr_jamah']); ?></span>
            </div>
        </div>

        <!-- Asr -->
        <div class="prayer-row <?php echo $this->getNextPrayerClass('asr', $this->row) ? 'current' : ''; ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['asr']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo $this->formatTime($this->row['asr_begins']); ?></span>
                <span class="iqamah-time"><?php echo $this->formatTime($this->row['asr_jamah']); ?></span>
            </div>
        </div>

        <!-- Maghrib -->
        <div class="prayer-row <?php echo $this->getNextPrayerClass('maghrib', $this->row) ? 'current' : ''; ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['maghrib']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo $this->formatTime($this->row['maghrib_begins']); ?></span>
                <span class="iqamah-time"><?php echo $this->formatTime($this->row['maghrib_jamah']); ?></span>
            </div>
        </div>

        <!-- Isha -->
        <div class="prayer-row <?php echo $this->getNextPrayerClass('isha', $this->row) ? 'current' : ''; ?>">
            <div class="prayer-name"><?php echo $this->getLocalPrayerNames()['isha']; ?></div>
            <div class="prayer-time">
                <span class="start-time"><?php echo $this->formatTime($this->row['isha_begins']); ?></span>
                <span class="iqamah-time"><?php echo $this->formatTime($this->row['isha_jamah']); ?></span>
            </div>
        </div>

    </div>

</div>