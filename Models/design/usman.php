
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
                    <div class="col-md-4 digital-clock">
                        <h2>3:01</h2>
                    </div>
                    <div class="col-md-2 seconds-count">
                        <h4>42</h4>
                        <p>PM</p>
                    </div>
                    <div class="col-md-6 english-arabic-date">
                        <p class="english-date">Sun 28th April 2024</p> 
                        <p class="arabic-date">18 Shawwal 1445 H</p>
                    </div>
                </div>
                <div class="row left-main-col-prayernames">
                    <div class="col-md-12 prayernames-column">
                    <div class="row lmc-heading pt-2">
                        <div class="col-md-4 empty-space"></div>
                        <div class="col-md-4">
                            <h3 class="start-title">Start Time</h3>
                        </div>
                        <div class="col-md-4 jamah-column">
                            <h3 class="jamah-title">Jama'h Time</h3>
                        </div>
                    </div>

                    <div class="row fajr-section">
                        <div class="col-md-4 fajr-title title-value">FAJR</div>
                        <div class="col-md-4 fajr-start-time time-value">
                            <h3>4:01</h3>
                        </div>
                        <div class="col-md-4 col-sm-4 fajr-jamah-time time-value">
                            <h3>5:10</h3>
                        </div>
                    </div>

                    <div class="row zuhr-section">
                        <div class="col-md-4 zuhr-title title-value">ZUHR</div>
                        <div class="col-md-4 zuhr-start-time time-value">
                            <h3>1:10</h3>
                        </div>
                        <div class="col-md-4 zuhr-jamah-time time-value">
                            <h3>1:30</h3>
                        </div>
                    </div>

                    <div class="row asr-section">
                        <div class="col-md-4 asr-title title-value">ASR</div>
                        <div class="col-md-4 asr-start-time time-value">
                            <h3>6:08</h3>
                        </div>
                        <div class="col-md-4 asr-jamah-time time-value">
                            <h3>7:00</h3>
                        </div>
                    </div>

                    <div class="row maghrib-section">
                        <div class="col-md-4 maghrib-title title-value">MAGHRIB</div>
                        <div class="col-md-4 maghrib-start-time time-value">
                            <h3>8:27</h3>
                        </div>
                        <div class="col-md-4 maghrib-jamah-time time-value">
                            <h3>8:32</h3>
                        </div>
                    </div>

                    <div class="row esha-section">
                        <div class="col-md-4 esha-title title-value">ESHA</div>
                        <div class="col-md-4 esha-start-time time-value">
                            <h3>9:42</h3>
                        </div>
                        <div class="col-md-4 esha-jamah-time time-value">
                            <h3>10:00</h3>
                        </div>
                    </div>

                    <div class="row jummah-section">
                        <div class="col-md-4 jummah-title title-value">JUMMAH</div>
                        <div class="col-md-4 jummah-start-time time-value">
                            <h3>1:10</h3>
                        </div>
                        <div class="col-md-4 jummah-jamah-time time-value">
                            <h3>1:30</h3>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="row left-main-col-sun-times pt-md-3 text-center">

                    <div class="col-md-3 sub-sadiq-section">
                        <p class="sub-sadiq-title">SUB-SADIQ</p><br>
                        <h4 class="sub-sadiq-time">4:04</h4>
                    </div>

                    <div class="col-md-3 sunrise-section">
                        <p class="sunrise-tittle">SUNRISE</p> <br>
                        <h4 class="sunrise-time">5:43</h4>
                    </div>

                    <div class="col-md-3 zawaal-section">
                        <p class="zawaal-title">ZAWAAL</p><br>
                        <h4 class="zawaal-time">1:06</h4>
                    </div>

                    <div class="col-md-3 sunset-section">
                        <p class="sunset-title">SUNSET</p><br>
                        <h4 class="sunset-time">8:27</h4>
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