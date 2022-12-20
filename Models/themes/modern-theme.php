<div class="container-fluid x-board-modern">

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
                <img src="assets/Screenshot from 2022-11-07 22-07-01.png" class="img-fluid" alt="logo">
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-8 time-column">
            <div class="time">
                <h3>11 : 20 : 54</h3>
            </div>
        </div>
    </div>
    <div class="row date-english-arabic">
        <div class="col-md-6 col-sm-6 col-6 english-date text-center px-0">
            <p>Monday, December 5, 2022</p>
        </div>
        <div class="col-md-6 col-sm-6 col-6 arabic-date text-center px-0">
            <p>Jumada I 11, 1444 AH</p>
        </div>
    </div>
</div>


<!--BANNER SECTION-->

<div id="banner-section">
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

<!--TIMETABLE SECTION-->

<div id="time-table-section" class="text-center">
    <div class="row pt-4">
        <div class="col-md-2 col-sm-6 fajr-prayer">
            <iconify-icon icon="lucide:sunrise" class="icon"></iconify-icon>
            <h4>Fajr</h4>
            <div class="prayer-start">
                <p>5:14</p>
            </div>
            <div class="prayer-jamaat">
                <p>6:30</p>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 sunrise">
            <iconify-icon icon="bi:sunrise-fill" class="icon"></iconify-icon>
            <h4>Sunrise</h4>
            <div class="prayer-jamaat">
                <p>7:11</p>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 zuhr-prayer">
            <iconify-icon icon="emojione:sun" class="icon"></iconify-icon>
            <h4>Zuhr</h4>
            <div class="prayer-start">
                <p>11:54</p>
            </div>
            <div class="prayer-jamaat">
                <p>12:30</p>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 asr-prayer">
            <iconify-icon icon="bi:sun" class="icon"></iconify-icon>
            <h4>Asr</h4>
            <div class="prayer-start">
                <p>2:31</p>
            </div>
            <div class="prayer-jamaat">
                <p>3:00</p>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 maghrib-prayer">
            <iconify-icon icon="carbon:sunset" class="icon"></iconify-icon>
            <h4>Maghrib</h4>
            <div class="prayer-start">
                <p>4:26</p>
            </div>
            <div class="prayer-jamaat">
                <p>4:26</p>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 isha-prayer">
            <iconify-icon icon="bi:moon-stars-fill" class="icon"></iconify-icon>
            <h4>Isha</h4>
            <div class="prayer-start">
                <p>5:37</p>
            </div>
            <div class="prayer-jamaat">
                <p>7:30</p>
            </div>
        </div>
        <div class="col-md-2 jummah-prayer">
            <iconify-icon icon="la:mosque" class="icon"></iconify-icon>
            <h4>Jummah</h4>
            <div class="prayer-jamaat">
                <p class="mb-0 time1">12:45</p>
            </div>
        </div>
    </div>
</div>

</div>