<?php 
$html = '';
$sunriseOrZawal = $this->dptHelper->getSunriseOrZawalOrIshraq($this->row);
$prayerNames = $this->getLocalPrayerNames();
$headers = $this->getLocalHeaders();
?>
<div class="container-fluid x-board-my-masjid">
    <?php echo $this->getHiddenVariables(); ?>
    
    <!-- TOP SECTION: Logo, Name, Date -->
    <div class="row top-section">
        <div class="col-12 top-inner">
            <div class="logo-name">
                <?php echo $this->getLogoUrl(); ?>
                <span class="masjid-name"><?php echo get_bloginfo('name'); ?></span>
            </div>
            <div class="date-display">
                <span class="english-date"><?php echo date_i18n('l ' . get_option('date_format')); ?></span>
                <?php echo $this->getHijriDate(date("d"), date("m"), date("Y"), $this->getRow()); ?>
            </div>
        </div>
    </div>

    <!-- MIDDLE SECTION: Clock + Next Prayer -->
    <div class="row middle-section">
        <!-- Clock Column (hidden on mobile) -->
        <div class="col-4 clock-column d-none d-md-block">
            <div class="clock-wrap">
                <div class="clock align-middle">
                    <ul class="clock">
                        <li id="hours"></li>
                        <li id="pointx">:</li>
                        <li id="min"></li>
                        <li id="pointx">:</li>
                        <li id="sec"></li>
                    </ul>
                </div>
            </div>
            <div class="digital-time">
                <span id="hours"></span><span>:</span><span id="min"></span><span id="sec"></span>
            </div>
        </div>

        <!-- Next Prayer Banner -->
        <div class="col-12 col-md-8 next-prayer-banner">
            <?php 
            $html .= $this->getFirstSlide();
            echo $html;
            ?>
        </div>
    </div>

    <!-- PRAYER TIMES TABLE -->
    <div class="row prayer-table-section">
        <div class="col-12">
            <table class="prayer-table">
                <thead>
                    <tr>
                        <th><?php echo $headers['prayer']; ?></th>
                        <th class="ar"><?php echo $prayerNames['fajr']; ?></th>
                        <th class="text-end"><?php echo strtoupper($headers['begins']); ?></th>
                        <th class="text-end"><?php echo strtoupper($headers['iqamah']); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $prayers = ['fajr', 'zuhr', 'asr', 'maghrib', 'isha'];
                    foreach ($prayers as $prayer) {
                        $nextClass = $this->dptHelper->getNextPrayerClass($prayer, $this->row, $prayer === 'fajr');
                        $name = $prayerNames[$prayer];
                        $start = do_shortcode("[{$prayer}_start]");
                        $jamah = do_shortcode("[{$prayer}_prayer]");
                        $arabic = $prayer === 'fajr' ? 'فجر' : ($prayer === 'zuhr' ? 'ظهر' : ($prayer === 'asr' ? 'عصر' : ($prayer === 'maghrib' ? 'مغرب' : 'عشاء')));
                        ?>
                        <tr class="<?php echo $nextClass; ?>">
                            <td class="name"><?php echo $name; ?></td>
                            <td class="ar"><?php echo $arabic; ?></td>
                            <td class="time text-end bold"><?php echo $start; ?></td>
                            <td class="time text-end"><?php echo $jamah; ?></td>
                        </tr>
                    <?php } ?>
                    <!-- Sunrise -->
                    <tr class="dimmed <?php echo $this->dptHelper->getNextPrayerClass($sunriseOrZawal, $this->row); ?>">
                        <td class="name"><?php echo $prayerNames[$sunriseOrZawal]; ?></td>
                        <td class="ar"><?php echo $sunriseOrZawal === 'zawal' ? 'زوال' : 'شروق'; ?></td>
                        <td class="time text-end"><?php echo do_shortcode("[{$sunriseOrZawal}]"); ?></td>
                        <td class="time text-end">-</td>
                    </tr>
                    <!-- Jumuah -->
                    <?php if (!empty($headers['jumuah'])): ?>
                    <tr class="jumuah-row <?php echo $this->dptHelper->getNextPrayerClass('jumuah', $this->row); ?>">
                        <td class="name"><?php echo stripslashes($headers['jumuah']); ?></td>
                        <td class="ar">جمعة</td>
                        <td class="time text-end" colspan="2"><?php echo $this->getJumuahTimesArray(); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- BOTTOM SECTION -->
    <div class="row bottom-section">
        <div class="col-12 text-end">
            <span class="watermark"><?php echo get_bloginfo('url'); ?></span>
        </div>
    </div>
</div>