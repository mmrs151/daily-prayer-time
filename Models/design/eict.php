<?php 

$html = '';
?>
<div class="container-fluid x-board-modern">

    <!--TOP SECTION-->
    <?php echo $this->getHiddenVariables(); ?>
    <div id="top-section">
        <div class="row middle-logo-time">
            <div class="col-md-3 col-sm-3 col-3 logo-column">
                <div class="logo">
                   <?php echo $this->getLogoUrl(); ?>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-6 mosque-name text-center">
                <h2>Edgware Islamic Cultural Trust</h2>
            </div>
            <div class="col-md-3 col-sm-3 col-3 time-column">
                <div class="time">
                    <div class="clock align-middle">
                        <ul class="clock">
                            <li id="hours"></li>
                            <li id="pointx">:</li>
                            <li id="min"></li>
                            <li id="pointx">:</li>
                            <li id="ampm"></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
        <div class="row date-english-arabic">
            <div class="col-md-6 col-sm-6 col-6 english-date text-center px-0">
                <p><?php echo date_i18n( 'l ' . get_option( 'date_format' ) ) ?></p>
            </div>
            <div class="col-md-6 col-sm-6 col-6 arabic-date text-center px-0">
                <p><?php echo $this->getHijriDate(date("d"), date("m"), date("Y"), $this->getRow()); ?></p>
            </div>
        </div>
    </div>


    <!--BANNER SECTION-->

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
    <div id="banner-section">
        <div class="row">
            <div class="<?php echo $leftClass ?> banner-text text-center">
                <div class="next-prayer-time">
                    <?php 
                        $html .= $this->getFirstSlide();
                        echo $html;
                    ?>
                </div>
            </div>
            <?php if (!empty($rightClass)) : ?>
            <div class="col-md-8 col-sm-8 col-8 banner-img text-center px-0 height-100">
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
            <?php endif ?>
        </div>
    </div>

    <!--TIMETABLE SECTION-->

    <div id="time-table-section" class="row text-center">
            <div class="row pt-4">
                <div class="col-md-2 col-sm-6 fajr-prayer">
                <span class="iconify-inline icon" data-icon="lucide:sunrise"></span>
                    <h4 class='<?php echo $this->getNextPrayerClass('fajr', $this->row, true) ?>'><?php echo $this->getLocalPrayerNames()['fajr'] ?></h4>
                    <div class="prayer-start">
                        <p class='<?php echo $this->getNextPrayerClass('fajr', $this->row, true) ?>'><?php echo do_shortcode("[fajr_start]") ?></p>
                    </div>
                    <div class="prayer-jamaat">
                        <p class='<?php echo $this->getNextPrayerClass('fajr', $this->row, true) ?>'><?php echo do_shortcode("[fajr_prayer]") ?></p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 sunrise">
                <span class="iconify-inline icon" data-icon="bi:sunrise-fill"></span>
                    <h4 class='<?php echo $this->getNextPrayerClass('sunrise', $this->row) ?>'><?php echo $this->getLocalPrayerNames()['sunrise'] ?></h4>
                    <div class="prayer-jamaat">
                        <p class='<?php echo $this->getNextPrayerClass('sunrise', $this->row) ?>'><?php echo do_shortcode("[sunrise]") ?></p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 zuhr-prayer">
                <span class="iconify-inline icon" data-icon="emojione:sun"></span>
                    <h4 class='<?php echo $this->getNextPrayerClass('zuhr', $this->row) ?>'><?php echo $this->getLocalPrayerNames()['zuhr'] ?></h4>
                    <div class="prayer-start">
                        <p class='<?php echo $this->getNextPrayerClass('zuhr', $this->row) ?>'><?php echo do_shortcode("[zuhr_start]") ?></p>
                    </div>
                    <div class="prayer-jamaat">
                        <p class='<?php echo $this->getNextPrayerClass('zuhr', $this->row) ?>'><?php echo do_shortcode("[zuhr_prayer]") ?></p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 asr-prayer">
                <span class="iconify-inline icon" data-icon="bi:sun"></span>
                    <h4 class='<?php echo $this->getNextPrayerClass('asr', $this->row) ?>' ><?php echo $this->getLocalPrayerNames()['asr'] ?></h4>
                    <div class="prayer-start">
                        <p class='<?php echo $this->getNextPrayerClass('asr', $this->row) ?>'><?php echo do_shortcode("[asr_start]") ?></p>
                    </div>
                    <div class="prayer-jamaat">
                        <p class='<?php echo $this->getNextPrayerClass('asr', $this->row) ?>'><?php echo do_shortcode("[asr_prayer]") ?></p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 maghrib-prayer">
                <span class="iconify-inline icon" data-icon="carbon:sunset"></span>
                    <h4 class='<?php echo $this->getNextPrayerClass('maghrib', $this->row) ?>'><?php echo $this->getLocalPrayerNames()['maghrib'] ?></h4>
                    <div class="prayer-start">
                        <p class='<?php echo $this->getNextPrayerClass('maghrib', $this->row) ?>'><?php echo do_shortcode("[maghrib_start]") ?></p>
                    </div>
                    <div class="prayer-jamaat">
                        <p class='<?php echo $this->getNextPrayerClass('maghrib', $this->row) ?>'><?php echo do_shortcode("[maghrib_prayer]") ?></p>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 isha-prayer">
                <span class="iconify-inline icon" data-icon="bi:moon-stars-fill"></span>
                    <h4 class='<?php echo $this->getNextPrayerClass('isha', $this->row) ?>'><?php echo $this->getLocalPrayerNames()['isha'] ?></h4>
                    <div class="prayer-start">
                        <p class='<?php echo $this->getNextPrayerClass('isha', $this->row) ?>'><?php echo do_shortcode("[isha_start]") ?></p>
                    </div>
                    <div class="prayer-jamaat">
                        <p class='<?php echo $this->getNextPrayerClass('isha', $this->row) ?>'><?php echo do_shortcode("[isha_prayer]") ?></p>
                    </div>
                </div>
                <div class="col-md-2 jummah-prayer">
                <span class="iconify-inline icon" data-icon="la:mosque"></span>
                    <h4 class='<?php echo $this->getNextPrayerClass('jumuah', $this->row) ?>'><?php echo $this->getLocalHeaders()['jumuah'] ?></h4>
                    <div class="prayer-jamaat">
                        <p class="mb-0 time1 <?php echo $this->getNextPrayerClass('jumuah', $this->row) ?>"><?php echo $this->getJumuahTimesArray(null, ' '); ?></p>
                    </div>
                </div>
            </div>
    </div>

</div>