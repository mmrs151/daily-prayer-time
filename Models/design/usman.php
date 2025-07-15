<?php
$html = '';
?>
<!-- MASJID DESIGN -->
<div class="usman-body">
    <div class="container-fluid d-masjid-e-usman">
        <div class="row mobile-logo-row">
            <div class="col-sm-12 mobile-logo-column text-center" >
                <?php echo $this->getLogoUrl(); ?>
            
            </div>
        </div>
        <div class="row height-100">
            <!-- LEFT SIDE TIME TABLE AND INFORMATION -->
            <div class="col-md-6 col-sm-12 col-12 left-main-column">
                <div class="row left-main-col-dateandtime">
                    <div class="col-md-4 col-sm-4 col-4 digital-clock">
                        <h2 id="hours"></h2>
                        <h2 id="colon">:</h2>
                        <h2 id="min"></h2>
                    </div>
                    <div class="col-md-2 col-sm-2 col-2 seconds-count">
                        <h4 id="sec">42</h4>
                        <p id="ampm">PM</p>
                    </div>
                    <div class="col-md-6 col-sm-6 col-6 english-arabic-date">
                        <p class="english-date"><?php echo date_i18n('l ' . get_option('date_format')); ?></p>
                        <?php echo $this->getHijriDate(date("d"), date("m"), date("Y"), $this->getRow()); ?>
                    </div>
                </div>

                <div class="row left-main-col-prayernames">
                    <div class="col-md-12 col-sm-12 col-12 prayernames-column">
                        <div class="row lmc-heading">
                            <div class="col-md-4 col-sm-4 col-4 empty-space"></div>
                            <div class="col-md-4 col-sm-4 col-4">
                                <h3 class="start-title"><?php echo strtoupper($this->getLocalHeaders()['begins']); ?></h3>
                            </div>
                            <div class="col-md-4 col-sm-4 col-4 jamah-column">
                                <h3 class="jamah-title"><?php echo strtoupper($this->getLocalHeaders()['iqamah']); ?></h3>
                            </div>
                        </div>

                        <?php
                        $prayers = ['fajr', 'zuhr', 'asr', 'maghrib', 'isha'];
                        foreach ($prayers as $prayer) {
                            $nextPrayerClass = $this->getNextPrayerClass($prayer, $this->row);
                            $prayerName = $this->getLocalPrayerNames()[$prayer];
                            $startTime = do_shortcode("[$prayer" . "_start]");
                            $jamahTime = do_shortcode("[$prayer" . "_prayer]");
                            echo "
                            <div class='row {$prayer}-section {$nextPrayerClass}'>
                                <div class='col-md-4 col-sm-4 col-4 {$prayer}-title title-value'>
                                    <span class='title'>{$prayerName}</span>
                                </div>
                                <div class='col-md-4 col-sm-4 col-4 {$prayer}-start-time time-value'>
                                    <h3>{$startTime}</h3>
                                </div>
                                <div class='col-md-4 col-sm-4 col-4 {$prayer}-jamah-time time-value'>
                                    <h3>{$jamahTime}</h3>
                                </div>
                            </div>";
                        }
                        ?>

                        <div class="row jummah-section <?php echo $this->getNextPrayerClass('jumuah', $this->row); ?>">
                            <div class="col-md-4 col-sm-4 col-4 jummah-title title-value">
                                <span class="title"><?php echo stripslashes($this->getLocalHeaders()['jumuah']); ?></span>
                            </div>
                            <div class="col-md-8 col-sm-8 col-8 jummah-jamah-time time-value jummah">
                                <?php echo $this->getJumuahTimesArray(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row left-main-col-sun-times pt-md-3 text-center highlight">
                    <div class="col-md-3 col-sm-3 col-3 sub-sadiq-section">
                        <p class="sub-sadiq-title">SUBH-SADIQ</p><br>
                        <h4 class="sub-sadiq-time"><?php echo do_shortcode("[fajr_start]"); ?></h4>
                    </div>
                    <div class="col-md-3 col-sm-3 col-3 sunrise-section">
                        <p class="sunrise-title"><?php echo $this->getLocalPrayerNames()['sunrise']; ?></p><br>
                        <h4 class="sunrise-time"><?php echo do_shortcode("[sunrise]"); ?></h4>
                    </div>
                    <div class="col-md-3 col-sm-3 col-3 zawaal-section">
                        <p class="zawaal-title"><?php echo $this->prayerLocal['zawal']; ?></p><br>
                        <h4 class="zawaal-time"><?php echo do_shortcode("[zawal]"); ?></h4>
                    </div>
                    <div class="col-md-3 col-sm-3 col-3 sunset-section">
                        <p class="sunset-title">SUNSET</p><br>
                        <h4 class="sunset-time"><?php echo do_shortcode("[maghrib_start]"); ?></h4>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE BANNER AND MASJID NAME -->
            <?php
            $transitionEffect = get_option('transitionEffect');
            $transitionSpeed = get_option('transitionSpeed');
            $leftClass = 'col-md-12 col-sm-12 col-12';
            $rightClass = '';
            $slides = '';

            if (get_option('quran-chbox') || get_option('slider-chbox')) {
                $leftClass = 'col-md-4 col-sm-4 col-4';
                $rightClass = 'col-md-8 col-sm-8 col-8';

                if (get_option('quran-chbox')) {
                    $slides = $this->getQuranSlides($transitionSpeed);
                } else {
                    $slides = $this->getPresentationRow($transitionSpeed);
                }
            }
            ?>
            <div class="col-md-6 right-main-column height-100">
                <div class="row banner-section">
                    <div id="carouselExampleIndicators" class="carousel slide <?php echo $transitionEffect; ?> height-100" data-bs-ride="carousel">
                        <div class="carousel-inner height-100">
                            <?php echo $slides; ?>
                        </div>
                    </div>
                </div>
                <div class="row masjid-name-logo">
                    <div class="col-md-3 logo-section">
                        <?php echo $this->getLogoUrl(); ?>
                    </div>
                    <div class="col-md-9 afterlogo-text">
                        <h1 class="bottomright-masjidname"><?php echo get_bloginfo('name'); ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>