<?php
require_once(__DIR__ . '/DPTHelper.php');

class DigitalScreen extends DailyShortCode
{
    /** @var array */
    protected $row = array();

    /** @var bool */
    private $isPortrait;

    /** @var bool */
    private $isPresentation;

    /** @var int */
    private $screenTimeout = 5;

    /** @var string */
    private $scrollText;

    /** @var string */
    private $scrollSpeed = 20;

    /** @var array */
    private $presentationSlides;

    /** @var string */
    private $blinkText = 'SADAQA INCREASE YOUR WEALTH';

    /** @var string */
    private $blinkUrl;

    /** @var string */
    private $scrollUrl;

    /** @var string */
    private $verticalClass = '';

    /** @var boolean */
    private $disableOvernightDim = false;

    /** @var string */
    private $template;

    /** @var DPTHelper */
    protected $dptHelper;

    public function __construct($attr=array())
    {
        parent::__construct();

        $this->scrollText = esc_html(stripslashes(get_option("ds-scroll-text")));
        $this->scrollSpeed = empty(get_option("ds-scroll-speed")) ? $this->scrollSpeed : get_option("ds-scroll-speed");
        $this->blinkText = esc_html(stripslashes(get_option("ds-blink-text")));
        $this->dptHelper = new DPTHelper();
        $this->setAttributes($attr);
    }

    public function displayDigitalScreen()
    {
        if ($this->template || get_option('template-chbox')) {
            return $this->getTemplate();
        }

        $html = $this->getTopRow();

        if ($this->isPresentation) {
            $html .= $this->getPresentationRow();
        } else {
            $html .= $this->getMiddleRow();
        }

        $html .= $this->getBottomRow();

        return $html;
    }

    private function getTemplate()
    {
        if ($template = get_option('dsTemplate')) {
            $this->template = $template;
        }
        
        include "design/$this->template.php";
        $this->template = null;
    }

    private function getHiddenVariables()
    {
        $localNumbers = $this->getLocalNumbers();
        $localNumbers = array_combine(array_keys($localNumbers), $localNumbers);
        $localNumbersJson = json_encode($localNumbers, JSON_UNESCAPED_UNICODE);

        $timesLocal = json_encode([
                'minute' => $this->getLocalTimes()['minute'] ?? 'minute',
                'minutes' => $this->getLocalTimes()['minutes'],
                'hour' => $this->getLocalTimes()['hour'] ?? 'hour',
                'hours' => $this->getLocalTimes()['hours'],
                'second' => $this->getLocalTimes()['second'],
            ],
            JSON_UNESCAPED_UNICODE);

        $hiddenVariables = '
            <input type="hidden" value="' . $this->canDimOvernight($this->getRow(), $this->disableOvernightDim) . '" id="overnightDim">
            <input type="hidden" value="' . $this->screenTimeout . '" id="screenTimeout">
            
            <input type="hidden" value="' . $this->getKhutbahDim($this->getRow()) . '" id="khutbahDim">
            <input type="hidden" value="' . $this->getTaraweehDim($this->getRow()) . '" id="taraweehDim">
            
            <input type="hidden" value="' . htmlspecialchars(json_encode($this->getRefreshPoints())) . '" id="refreshPoint">
            
            <input type="hidden" value="' . get_option('activateAdhan') . '" id="activateAdhan">
            <input type="hidden" value="' . get_option('activateBeep') . '" id="activateBeep">           
            <input type="hidden" value="' . get_option('quran-chbox') . '" id="quranCheckbox">
            <input type="hidden" value="' . $this->getWpHour() . '" id="clockHour">

            <input type="hidden" value="' . htmlspecialchars(json_encode($this->getFadingMessages())) . '" id="fadingMessages">
            <input type="hidden" value="' . htmlentities($localNumbersJson) . '" id="localizedNumbers">
            <input type="hidden" value="' . htmlentities($timesLocal) . '" id="localizedTimes">    
            <input type="hidden" value="' . htmlspecialchars($this->getFajrAdhanTime(), JSON_UNESCAPED_UNICODE) . '" id="fajrAdhanTime">    
            <input type="hidden" value="' . htmlspecialchars(json_encode($this->getOtherAdhanTimes(), JSON_UNESCAPED_UNICODE)) . '" id="otherAdhanTimes">   
        ';

        if (get_option("activateAdhan") === 'adhan') {
            $hiddenVariables .= '
                <input type="hidden" value="' . get_option('fajrAdhanUrl') . '" id="fajrAdhanUrl">
                <input type="hidden" value="' . get_option('otherAdhanUrl') . '" id="otherAdhanUrl">
            ';
        }

        return $hiddenVariables;
    }

    private function getTopRow()
    {
        $timeClass = "col-sm-3 ";
        $dateClass = "col-sm-7 ";
        $height = "height-100";
        $verticalClass = "";
        if (  $this->isPortrait  ) {
            $timeClass = "col-xs-12 vertical-time ";
            $dateClass = "col-xs-12 vertical-date";
            $height = "height-50 ";
            $verticalClass = "vertical";
        }

        if ( get_option('hijri-chbox')) {
            $date = date_i18n( 'D jS  M' );
        } else {
            $date = date_i18n(get_option( 'date_format'));
        }

        $html = '
        <div class="container-fluid x-board">
            ' . $this->getHiddenVariables() . '

            <div class="row top-row dpt-bg">
                <div class="time bg-dark ' . $timeClass . 'col-xs-12 text-center ' . $height . '">
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
                <div class="' . $dateClass . ' col-xs-12 text-center ' . $height . '">
                    <div class="align-middle" id="date-section">
                        <span id="dsDate" class="date-eng h6 ' . $verticalClass . '">' . $date. '
                            <span id="dsHijriDate" class="'. $verticalClass . 'hijri">
                            ' . $this->getHijriDate(date("d"), date("m"), date("Y"), $this->getRow()) . '
                            </span>
                        </span>
                    </div>
                </div>
                <div class="col-sm-2 col-xs-12 text-right align-middle padding-null">';

        $html .= $this->getLogoUrl();
        
        $html .=
            '</div>
            </div>';
        return $html;
    }

    private function getLogoUrl()
    {
        if ($this->isPortrait ) {
            return;
        }

        $isLogo = get_option('ds-logo');
        if ( $isLogo) {
            return '<img class="logo" src="' . $isLogo . '">';
        } else {
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            $image_url = wp_get_attachment_image_url ( $custom_logo_id , 'full' );    
            return '<img class="logo" src="' . $image_url . '">';
        }
    }

    private function getMiddleRow()
    {
        $leftClass = "col-sm-5 col-xs-12 bg-red height-100 padding-null text-center";
        $rightClass = "col-sm-7 col-xs-12 padding-null text-center bg-green height-100 padding-null";
        $verticalClass = "";
        $sunriseOrZawal = $this->dptHelper->getSunriseOrZawal($this->row);

        if ( $this->isPortrait ) {
            $verticalClass = "vertical";
            $leftClass = "col-sm-12 bg-red padding-null text-center height-60";
            $rightClass = "col-sm-12 padding-null text-center bg-green height-40";
        }

        $html =  '
            <div class="row middle-row bg-red">
                <div class="'. $leftClass .'">
                    <table id="dsPrayerTimetable" class="table height-100 '. $verticalClass .'">
                    <thead class="bg-dark">
                    <tr>
                        <th class="dsPrayerName">
                            <span class="dpt_start">' . strtoupper($this->getLocalHeaders()['prayer']) . '</span>
                        </th>
                        <th class="dsBegins">
                            <span class="dpt_start">' . strtoupper($this->getLocalHeaders()['begins']) . '</span>
                        </th>
                        <th class="dsIqamah">
                            <span class="dpt_jamah">' . strtoupper($this->getLocalHeaders()['iqamah']) . '</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr class=' . $this->getNextPrayerClass('fajr', $this->row, true) . '>
                            <td class="prayerName">
                                <span>' . $this->getLocalPrayerNames()['fajr'] . '</span>
                            </td>
                            <td class="l-red">' . do_shortcode("[fajr_start]") . '</td>
                            <td>' . do_shortcode("[fajr_prayer]") . '</td>
                        </tr>
                        <tr class=' . $this->getNextPrayerClass($sunriseOrZawal, $this->row) . '>
                            <td class="prayerName"><span>' . $this->getLocalPrayerNames()[$sunriseOrZawal] . '</span></td>
                            <td class="prayerName sunrise" colspan="2">' . do_shortcode("[$sunriseOrZawal]") . '</td>
                        </tr>';


            $html .= '
            <tr class=' . $this->getNextPrayerClass('zuhr', $this->row) . '>
                <td class="prayerName"><span>' . $this->getLocalPrayerNames()['zuhr'] . '</span></td>
                <td class="l-red">' . do_shortcode("[zuhr_start]") . '</td>
                <td>' . do_shortcode("[zuhr_prayer]") . '</td>
            </tr>
    ';

        $html .=
        '<tr class=' . $this->getNextPrayerClass('asr', $this->row) . '>
            <td class="prayerName"><span>' . $this->getLocalPrayerNames()['asr'] . '</span></td>
            <td class="l-red">' . do_shortcode("[asr_start]") . '</td>
            <td>' . do_shortcode("[asr_prayer]") . '</td>
        </tr>
        <tr class=' . $this->getNextPrayerClass('maghrib', $this->row) . '>
            <td class="prayerName"><span>' . $this->getLocalPrayerNames()['maghrib'] . '</span></td>
            <td class="l-red">' . do_shortcode("[maghrib_start]") . '</td>
            <td>' . do_shortcode("[maghrib_prayer]") . '</td>
        </tr>
        <tr class=' . $this->getNextPrayerClass('isha', $this->row) . '>
            <td class="prayerName"><span>' . $this->getLocalPrayerNames()['isha'] . '</span></td>
            <td class="l-red">' . do_shortcode("[isha_start]") . '</td>
            <td>' . do_shortcode("[isha_prayer]") . '</td>
        </tr>';
            $html .= '
                <tr class=' . $this->getNextPrayerClass('jumuah', $this->row) . '>
                    <td class="prayerName"><span>' . stripslashes($this->getLocalHeaders()['jumuah']) . '</span></td>
                    <td colspan="2" class="prayerName l-red sunrise">                    
                        ' . $this->getJumuahTimesArray($this->isPortrait) . '                        
                    </td>
                </tr>';
        $html .= '
            </tbody>
                    </table>
        </div>'; //left class

        $transitionEffect = get_option('transitionEffect');
        $transitionSpeed = get_option('transitionSpeed');
        $html .='
            <div class="'. $rightClass .'">
                <div id="carouselExampleIndicators" class="carousel slide ' . $transitionEffect . ' height-100" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            ' . $this->getFirstSlide() . '
                        </div>
                        ' . $this->getOtherSlides($transitionSpeed) . '
                    </div>';
                if ( $this->isPortrait ) {
                    $html .= '<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>';
                }
                $html .= '</div>
            </div>
        </div> 
        ';

        return $html;
    }

    private function getBottomRow()
    {
        $verticalClass = "";

        if ( $this->isPortrait ) {
            $verticalClass = "vertical";
        }

        $html = '
                <div class="row bottom-row">
                    <div class="notificationBackground col-sm-3 col-xs-12 text-center height-100 align-middle">
                        <div class="align-middle">
                                <span id="dsBlink" class="blink">' . $this->getBlink() . '</span>
                        </div>
                    </div>
                    <div class="col-sm-9 col-xs-12 height-100 dpt-bg">
                        <div class="align-middle">
                            <h3 class="text-primary scrolling">
                            <div class="marquee">
                                <span>' . $this->getIqamahUpdate() . '</span>
                            </div>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        ';

        if ($this->isPortrait) {
            $html = '
                <div class="row bottom-row">
                    <div class="col-sm-12 col-xs-12 height-24">
                        <div class="align-middle">
                            <h3 class="text-primary scrolling-vertical">
                            <div class="marquee">
                                <span>' . $this->getIqamahUpdate() . '</span>
                            </div>
                            </h3>
                        </div>
                    </div>
                    <div class="notificationBackground notificationFont col-sm-12 col-xs-12 text-center height-50 align-middle">
                        <div class="align-middle">
                                <span id="dsBlink" class="blink-'.$verticalClass.'">' . $this->getBlink() . '</span>
                        </div>
                    </div>
                </div>
            </div>
        ';
        }

        return $html;
    }


    private function getQuranSlides()
    {
        $transitionEffect = get_option('transitionEffect');
        $transitionSpeed = get_option('transitionSpeed');

        $html ='
            <div class="row middle-row bg-red">
            <div id="carouselExampleIndicators" class="carousel slide ' . $transitionEffect . ' height-100" data-bs-ride="carousel">
                <div class="carousel-inner">
                    ' . $this->getOtherSlides($transitionSpeed) . '
                </div>
            </div>
        </div>
        ';

        return $html;
    }

    private function getPresentationRow()
    {
        $transitionEffect = get_option('transitionEffect');
        $transitionSpeed = get_option('transitionSpeed');

        $html ='
            <div class="row middle-row bg-red height-100">
            <div id="carouselExampleIndicators" class="carousel slide ' . $transitionEffect . ' height-100" data-bs-ride="carousel">
                <div class="carousel-inner height-100">
                    ' . $this->getPresentationSlides($transitionSpeed) . '
                </div>
            </div>
        </div>
        ';

        return $html;
    }

    private function getPresentationSlides($transitionSpeed)
    {
        $this->presentationSlides = $this->getSliderUrls();
        $html = '
                <div class="carousel-item active height-100" data-bs-interval="'. $transitionSpeed .'">
                    <img class="carousel-slide" src="' . array_shift($this->presentationSlides) . '">
                </div>';

        foreach ($this->presentationSlides as $i => $slideUrl) {
            if (empty($slideUrl))  continue;
            $html .= '
                <div class="carousel-item height-100" data-bs-interval="'. $transitionSpeed .'">
                    <img class="carousel-slide " src="' . trim($slideUrl) . '">
                </div>
                ';
        }

        return $html;
    }

    private function getIqamahUpdate()
    {
        $orientation = 'horizontal';
        if ($this->isPortrait) {
            $orientation = 'vertical';
        }

        if ( $this->scrollText ) {
            return '
            <div class="dsScroll">
                <input type="hidden" id="scrollSpeed" value="' . $this->scrollSpeed . '">
                <a class="scroll" target="_new" href="'. $this->scrollUrl .' " >'. $this->scrollText . '</a>
            </div>' . do_shortcode("[display_iqamah_update orientation='" . $orientation . "']");
        } else {
            return '
            <div class="dsScroll">
                <input type="hidden" id="scrollSpeed" value="' . $this->scrollSpeed . '">
            </div>' . do_shortcode("[display_iqamah_update orientation='" . $orientation . "']");
        }
    }

    private function getBlink()
    {
        $orientation = '';
        if ($this->isPortrait) {
            $orientation = 'vertical';
        }
        
        return '<a class="notificationFont blink-' .$orientation.'" target="_new" href="'. $this->blinkUrl .'">'. $this->blinkText .'</a>';
    }

    private function getFirstSlide()
    {
        $verticalClass = "";
        $sehriClass = "sehri";
        $iftarClass = "iftar";
        if ( $this->isPortrait ) {
            $verticalClass = "vertical";
            $sehriClass = "sehri sehri-vertical";
            $iftarClass = "iftar iftar-vertical";
        }

        if ($this->isRamadan()) {
            $h3 = '
            <div class="dsRamadan">
                <div class="' . $sehriClass . '">' . $this->getLocalHeaders()['fast_begins'] . ': ' . $this->formatDateForPrayer($this->getFajrBegins(), true) . '</div>
                <div class="' . $iftarClass . ' pull-right">' . $this->getLocalHeaders()['fast_ends']. ': ' . do_shortcode("[maghrib_start]") . '</div>
            </div>';
        } else {
            $h3 = '<h3 class="'.$verticalClass.'">'. $this->getLocalTimes()['next prayer'] . '</h3>';
        }

        $html =  '
            <div class="nextPrayer">
                <div class="align-middle-next-prayer">'
                   . $h3 .
                    '<h2 id="dsNextPrayer" class="dsNextPrayer '.$verticalClass.'"></h2>
                </div>
            </div>';

        return $html;
    }

    private function getOtherSlides($transitionSpeed=10)
    {
	    if ( get_option('quran-chbox') ) {
		    return '<div class="carousel-item height-100" data-bs-interval="10000">
	            <div class="nextPrayer">
	                <div class="align-middle-next-prayer">
	                    <h4 id="quranVerse" class="' . $this->verticalClass . '"></h4>
	                </div>
	            </div>
	        </div>';
	    }

        if ( get_option('slider-chbox') ) {
            $html = "";
            $slides = $this->getSliderUrls();

            $slides = array_filter(array_map('trim', $slides));
            foreach ($slides as $i => $slide) {
                $html .= '
                <div class="carousel-item height-100" data-bs-interval="'. $transitionSpeed .'">
                    <a href="' . get_option("slider". ($i+1) . "Url") . '" style="color:' . get_option('fontColor') .'">
                        ' . $this->getImageOrMessage($slide) . '
                    </a>
                </div>
                ';

                $nextPrayerSlide = (int)get_option('nextPrayerSlide');
                if (!$this->isPresentation && $nextPrayerSlide > 0) {
                    $count = $i + 1;
                    if ( $count % $nextPrayerSlide == 0 ) {
                        $html .= '
                        <div class="carousel-item">
                            ' . $this->getFirstSlide() . '
                        </div>
                    ';
                    }
                }
            }

            return $html;
        }

        return null;
    }

    private function getSliderUrls(): array
    {
        $slides = [];
        if ($this->presentationSlides) {
            return $this->presentationSlides;
        }

        foreach (range(1, 11) as $item) {
            $slides[] = get_option('slider' . $item);
        }

        return $slides;
    }

    private function getImageOrMessage($slide)
    {
        if (filter_var($slide, FILTER_VALIDATE_URL) === FALSE) {
            return '
            <div class="nextPrayer">
                <div class="align-middle-next-prayer">
                    <h4 class="sliderMessage">' . stripslashes($slide) . '</h4>
                </div>
            </div>
            ';
        }
        return '<img class="carousel-slide" src="' . $slide . '">';
    }

    private function getRefreshPoints()
    {
	    $result = $this->db->getPrayerTimeForToday();
        $iqamahTimes =  array($result['fajr_jamah'], $result['sunrise'], $result['zuhr_jamah'], $result['asr_jamah'], $result['maghrib_jamah'], $result['isha_jamah']);

        $refreshPoints = array();
        foreach($iqamahTimes as $iqamah) {
            $refreshPoints[] = date( "H:i:s", strtotime( $iqamah . "-15 minutes" ) );
        }

        $minsAfterIsha = 31;
        if($this->isRamadan()) {
            $minsAfterIsha += (int)get_option('taraweehDim');
        }

        $refreshPoints[] = date( "H:i:s", strtotime( end($iqamahTimes) . "+" . $minsAfterIsha . " minutes" ) ); // after 30 min overnight dim is true

        return $refreshPoints;
    }

    private function getOtherAdhanTimes()   
    {

        $result = $this->db->getPrayerTimeForToday();
        $iqamahTimes =  array($result['zuhr_jamah'], $result['asr_jamah'], $result['maghrib_begins'], $result['isha_jamah']);

        $adhanTimes = array();

        $zuhrAdhanBefore = empty($min = get_option('zuhrAdhanBefore')) ? 15 : $min;
        $adhanTimes[] = date( "H:i:s", strtotime( $iqamahTimes[0] . "-" . $zuhrAdhanBefore . " minutes" ) ); // zuhr

        $asrAdhanBefore = empty($min = get_option('asrAdhanBefore')) ? 15 : $min;
        $adhanTimes[] = date( "H:i:s", strtotime( $iqamahTimes[1] . "-" . $asrAdhanBefore . " minutes" ) ); // asr

        $adhanTimes[] = date( "H:i:s", strtotime( $iqamahTimes[2] . "0 minutes" ) ); // maghrib

        $ishaAdhanBefore = empty($min = get_option('ishaAdhanBefore')) ? 15 : $min;

        $adhanTimes[] = date( "H:i:s", strtotime( $iqamahTimes[3] . "-" . $ishaAdhanBefore . " minutes" ) ); // isha

        return $adhanTimes;
    }

    private function getFajrAdhanTime()
    {
        $result = $this->db->getPrayerTimeForToday();

        if ( $this->isRamadan() ) {
            return date( "H:i:s", strtotime( $result['fajr_begins'] . "0 minutes" ) ); // fajr start
        }

        $fajrAdhanBefore = empty($min = get_option('fajrAdhanBefore')) ? 15 : $min;

        return date( "H:i:s", strtotime( $result['fajr_jamah'] . "-" . $fajrAdhanBefore . " minutes ") ); // fajr iqamah
    }

    private function setAttributes($attr=array())
    {
        if ( isset($attr['view']) ) {
            $this->isPortrait = ( strtolower(esc_attr($attr['view'])) == 'vertical' );
            $this->isPresentation = ( strtolower(esc_attr($attr['view'])) == 'presentation' );

            if ( $this->isPortrait ) {
                $this->verticalClass = "vertical";
            }
        }

        if ( isset($attr['dim']) ) {
            $this->screenTimeout = esc_attr($attr['dim']);
        }

        if ( isset($attr['disable_overnight_dim'])) {
            $this->disableOvernightDim = true;
        }

        if ( isset($attr['deactivate_tomorrow'])) {
            add_option('deactivate_tomorrow', true);
        } else {
            delete_option('deactivate_tomorrow');
        }

        if ( isset($attr['scroll']) ) {
            $this->scrollText = esc_attr($attr['scroll']);
        }

        if ( isset($attr['scroll_link']) ) {
            $this->scrollUrl = esc_attr($attr['scroll_link']);
        }

        if ( isset($attr['blink']) ) {
            $this->blinkText = esc_attr($attr['blink']);
        }

        if ( isset($attr['blink_link']) ) {
            $this->blinkUrl = esc_attr($attr['blink_link']);
        }

        if ( isset($attr['slides']) ) {
            $this->presentationSlides = explode(',', esc_attr($attr['slides']));
        }

        if ( isset($attr['template']) ) {
            $this->template = esc_attr($attr['template']);
        }
    }

    private function getFadingMessages()
    {
        if (empty(get_option('ds-fading-msg'))) {
            return 0;
        }
        $messages = explode('.', get_option('ds-fading-msg'));
        $messages = array_map('stripslashes', $messages);
        $messages = array_filter($messages);
        
        array_push($messages, date_i18n( 'l ' . get_option( 'date_format' )));

        $hijriCheckbox = get_option('hijri-chbox');
        if ( ! empty($hijriCheckbox) ) {
            array_push($messages,
                $this->getHijriDate(date("d"), date("m"), date("Y"), $this->getRow())
            );
        }

        return $messages;
    }

    private function getWpHour()
    {
        $wpTimeFormat = explode(' ', date_i18n( 'l ' . get_option( 'time_format' ) ))[1];
        $clockHour = explode(':', $wpTimeFormat);

        return $clockHour[0];
    }
}
