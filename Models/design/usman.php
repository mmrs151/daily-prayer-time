
<?php 
    $html = '';
?>
<!-- MASJID DESIGN -->
<div class="usman-body">
    <div class="container-fluid d-masjid-e-usman">
        <div class="row height-100">
            <div class="col-md-6 left-main-column">

        <!-- LEFT SIDE TIME TABLE AND INFORMATION -->

                <div class="row left-main-col-dateandtime">
                    <div class="col-md-3 digital-clock">
                        <h2 id="hours"></h2><h2>:</h2><h2 id="min"></h2>
                    </div>
                    <div class="col-md-2 seconds-count">
                        <h4 id="sec">42</h4>
                        <p id="ampm">PM</p>
                    </div>
                    <div class="col-md-7 english-arabic-date">
                        <p class="english-date"><?php echo date_i18n( 'l ' . get_option( 'date_format' ) ) ?></p> 
                        <?php echo $this->getHijriDate(date("d"), date("m"), date("Y"), $this->getRow()); ?>
                    </div>
                </div>
                <div class="row left-main-col-prayernames">
                    <div class="col-md-12 prayernames-column">
                    <div class="row lmc-heading pt-2">
                        <div class="col-md-4 empty-space"></div>
                        <div class="col-md-4">
                            <h3 class="start-title"><?php echo strtoupper($this->getLocalHeaders()['begins']) ?></h3>
                        </div>
                        <div class="col-md-4 jamah-column">
                            <h3 class="jamah-title"><?php echo strtoupper($this->getLocalHeaders()['iqamah']) ?></h3>
                        </div>
                    </div>

                    <div class="row fajr-section <?php echo $this->getNextPrayerClass('fajr', $this->row, true) ?>">
                        <div class="col-md-4 fajr-title title-value">
                            <span class="title"><?php echo $this->getLocalPrayerNames()['fajr'] ?></span>
                        </div>
                        <div class="col-md-4 fajr-start-time time-value">
                            <h3><?php echo do_shortcode("[fajr_start]") ?></h3>
                        </div>
                        <div class="col-md-4 col-sm-4 fajr-jamah-time time-value">
                            <h3><?php echo do_shortcode("[fajr_prayer]") ?></h3>
                        </div>
                    </div>

                    <div class="row zuhr-section <?php echo $this->getNextPrayerClass('zuhr', $this->row) ?>">
                        <div class="col-md-4 zuhr-title title-value">
                            <span class="title"><?php echo $this->getLocalPrayerNames()['zuhr'] ?></span>
                        </div>
                        <div class="col-md-4 zuhr-start-time time-value">
                            <h3><?php echo do_shortcode("[zuhr_start]") ?></h3>
                        </div>
                        <div class="col-md-4 zuhr-jamah-time time-value">
                            <h3><?php echo do_shortcode("[zuhr_prayer]") ?></h3>
                        </div>
                    </div>

                    <div class="row asr-section <?php echo $this->getNextPrayerClass('asr', $this->row) ?>">
                        <div class="col-md-4 asr-title title-value">
                            <span class="title"><?php echo $this->getLocalPrayerNames()['asr'] ?></span>
                        </div>
                        <div class="col-md-4 asr-start-time time-value">
                            <h3><?php echo do_shortcode("[asr_start]") ?></h3>
                        </div>
                        <div class="col-md-4 asr-jamah-time time-value">
                            <h3><?php echo do_shortcode("[asr_prayer]") ?></h3>
                        </div>
                    </div>

                    <div class="row maghrib-section <?php echo $this->getNextPrayerClass('maghrib', $this->row) ?>">
                        <div class="col-md-4 maghrib-title title-value">
                            <span class="title"><?php echo $this->getLocalPrayerNames()['maghrib'] ?></span>
                        </div>
                        <div class="col-md-4 maghrib-start-time time-value">
                            <h3><?php echo do_shortcode("[maghrib_start]") ?></h3>
                        </div>
                        <div class="col-md-4 maghrib-jamah-time time-value">
                            <h3><?php echo do_shortcode("[maghrib_prayer]") ?></h3>
                        </div>
                    </div>

                    <div class="row esha-section <?php echo $this->getNextPrayerClass('isha', $this->row) ?>">
                        <div class="col-md-4 esha-title title-value">
                            <span class="title"><?php echo $this->getLocalPrayerNames()['isha'] ?></span>
                        </div>
                        <div class="col-md-4 esha-start-time time-value">
                            <h3><?php echo do_shortcode("[isha_start]") ?></h3>
                        </div>
                        <div class="col-md-4 esha-jamah-time time-value">
                            <h3><?php echo do_shortcode("[isha_prayer]") ?></h3>
                        </div>
                    </div>

                    <div class="row jummah-section <?php echo $this->getNextPrayerClass('jumuah', $this->row) ?>">
                        <div class="col-md-4 jummah-title title-value">
                            <span class="title"><?php echo stripslashes($this->getLocalHeaders()['jumuah']) ?></span>
                        </div>
                        <div class="col-md-8 jummah-jamah-time time-value jummah">
                            <?php echo $this->getJumuahTimesArray(); ?>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="row left-main-col-sun-times pt-md-3 text-center highlight">

                    <div class="col-md-3 sub-sadiq-section">
                        <p class="sub-sadiq-title">SUBH-SADIQ</p><br>
                        <h4 class="sub-sadiq-time"><?php echo do_shortcode("[fajr_start]") ?></h4>
                    </div>

                    <div class="col-md-3 sunrise-section">
                        <p class="sunrise-tittle"><?php echo $this->getLocalPrayerNames()['sunrise'] ?></p> <br>
                        <h4 class="sunrise-time"><?php echo do_shortcode("[sunrise]") ?></h4>
                    </div>

                    <div class="col-md-3 zawaal-section">
                        <p class="zawaal-title"><?php echo $this->prayerLocal['zawal']; ?></p><br>
                        <h4 class="zawaal-time"><?php echo do_shortcode("[zawal]") ?></h4>
                    </div>

                    <div class="col-md-3 sunset-section">
                        <p class="sunset-title">SUNSET</p><br>
                        <h4 class="sunset-time"><?php echo do_shortcode("[maghrib_start]") ?></h4>
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
            if ( get_option('quran-chbox') || get_option('slider-chbox')) {
                $leftClass = 'col-md-4 col-sm-4 col-4';
                $rightClass = 'col-md-8 col-sm-8 col-8';

                if ( get_option('quran-chbox')) {
                    $slides = $this->getQuranSlides($transitionSpeed);
                } else {
                    $slides = $this->getPresentationRow($transitionSpeed);
                }
            }
        ?>
            <div class="col-md-6 right-main-column">
                <div class="row banner-section">
                <?php 
                    $slidesHtml ='
                        <div id="carouselExampleIndicators" class="carousel slide ' . $transitionEffect . ' height-100" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active"></div>
                                ' . $slides . '
                            </div>';
                            $slidesHtml .= '
                        </div>
                    ';
                echo $slidesHtml;
                ?>
                </div>

                <div class="row masjid-name-logo">
                    <div class="col-md-3 logo-section">
                        <?php echo $this->getLogoUrl(); ?>
                    </div>
                    <div class="col-md-9 afterlogo-text">
                        <h1 class="bottomright-masjidname"><?php echo get_bloginfo( 'name' ); ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>