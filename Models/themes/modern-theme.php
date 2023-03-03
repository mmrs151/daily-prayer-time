<?php 
$html = '';
?>
<div class="x-board-modern">

<div class="container-fluid">

<!--TOP SECTION-->

<div id="top-section">
    <div class="row header-mosque-title">
        <div class="col mosque-name text-center">
            <h2>Leagrave Hall Masjid</h2>
        </div>
    </div>
    <div class="row middle-logo-time">
        <div class="col-md-4 col-sm-4 col-4 logo-column">
            <div class="logo">
                <img src="<?php echo get_option('ds-logo');?>" class="img-fluid" alt="logo">
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-8 time-column">
            <div class="time">
                <h3>
                <div class="clock align-middle">
                  <ul class="clock">
                      <li id="hours"></li>
                      <li id="pointx">:</li>
                      <li id="min"></li>
                      <li id="pointx">:</li>
                      <li id="ampm"></li>
                  </ul>
                </div>
                </h3>
            </div>
        </div>
    </div>
    <div class="row date-english-arabic">
        <div class="col-md-6 col-sm-6 col-6 english-date text-center px-0">
            <p><?php echo date_i18n( 'l ' . get_option( 'date_format' ) ) ?></p>
        </div>
        <div class="col-md-6 col-sm-6 col-6 arabic-date text-center px-0">
            <p><?php echo $this->getHijriDate(date("d"), date("m"), date("Y"), $this->getRow()) ?></p>
        </div>
    </div>
</div>


<!--BANNER SECTION-->

<div id="banner-section">
        <div class="banner-text-img text-center">
            <div class="next-prayer-time">
            <?php 
                $transitionEffect = get_option('transitionEffect');
                $transitionSpeed = get_option('transitionSpeed');
                $html .='
                    <div id="carouselExampleIndicators" class="carousel slide ' . $transitionEffect . ' height-100" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                ' . $this->getFirstSlide() . '
                            </div>
                            ' . $this->getOtherSlides($transitionSpeed) . '
                        </div>';
                        $html .= '
                    </div>
                ';
            echo $html;
            ?>
            </div>
        </div>
</div>

<!--TIMETABLE SECTION-->

<div id="time-table-section" class="text-center">
        <div class="row pt-4">
            <div class="col-md-2 col-sm-6 fajr-prayer">
            <span class="iconify-inline icon" data-icon="lucide:sunrise"></span>
                <h4>Fajr</h4>
                <div class="prayer-start">
                    <p>5:14</p>
                </div>
                <div class="prayer-jamaat">
                    <p>6:30</p>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 sunrise">
            <span class="iconify-inline icon" data-icon="bi:sunrise-fill"></span>
                <h4>Sunrise</h4>
                <div class="prayer-jamaat">
                    <p>7:11</p>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 zuhr-prayer">
            <span class="iconify-inline icon" data-icon="emojione:sun"></span>
                <h4>Zuhr</h4>
                <div class="prayer-start">
                    <p>11:54</p>
                </div>
                <div class="prayer-jamaat">
                    <p>12:30</p>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 asr-prayer">
            <span class="iconify-inline icon" data-icon="bi:sun"></span>
                <h4>Asr</h4>
                <div class="prayer-start">
                    <p>2:31</p>
                </div>
                <div class="prayer-jamaat">
                    <p>3:00</p>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 maghrib-prayer">
            <span class="iconify-inline icon" data-icon="carbon:sunset"></span>
                <h4>Maghrib</h4>
                <div class="prayer-start">
                    <p>4:26</p>
                </div>
                <div class="prayer-jamaat">
                    <p>4:26</p>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 isha-prayer">
            <span class="iconify-inline icon" data-icon="bi:moon-stars-fill"></span>
                <h4>Isha</h4>
                <div class="prayer-start">
                    <p>5:37</p>
                </div>
                <div class="prayer-jamaat">
                    <p>7:30</p>
                </div>
            </div>
            <div class="col-md-2 jummah-prayer">
            <span class="iconify-inline icon" data-icon="la:mosque"></span>
                <h4>Jummah</h4>
                <div class="prayer-jamaat">
                    <p class="mb-0 time1">12:45</p>
                </div>
            </div>
        </div>
</div>

</div>
</div>