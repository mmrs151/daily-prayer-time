<?php
if ( !class_exists('DPTDigitalScreenProcessor')) {
    class DPTDigitalScreenProcessor
    {
        /**
         * @var array
         */
        private $data;
    
        /**
         * @param array $data
         */
        public function __construct(array $data) {
            $this->data = $data;
        }
    
        public function process()
        {
            $dsLogo = sanitize_text_field($this->data['ds-logo']);
            update_option('ds-logo', $dsLogo);

            $dsScrollText = sanitize_text_field($this->data['ds-scroll-text']);
            update_option('ds-scroll-text', $dsScrollText);

            $dsScrollSpeed = sanitize_text_field($this->data['ds-scroll-speed']);
            update_option('ds-scroll-speed', $dsScrollSpeed);

            $dsBlinkText = sanitize_text_field($this->data['ds-blink-text']);
            update_option('ds-blink-text', $dsBlinkText);

            $dsAdditionalCss = sanitize_text_field($this->data['ds-additional-css']);
            update_option('ds-additional-css', $dsAdditionalCss);

            $dsFadingMsg = sanitize_text_field($this->data['ds-fading-msg']);
            update_option('ds-fading-msg', $dsFadingMsg);

            $quran = sanitize_text_field($this->data['quran-chbox']);
	        update_option('quran-chbox', $quran);

            $slider = sanitize_text_field($this->data['slider-chbox']);
            update_option('slider-chbox', $slider);
        
            $nextPrayerSlide = sanitize_text_field($this->data['nextPrayerSlide']);
            update_option('nextPrayerSlide', $nextPrayerSlide);
            
            $transitionEffect = sanitize_text_field($this->data['transitionEffect']);
            update_option('transitionEffect', $transitionEffect);
    
            $transitionSpeed = sanitize_text_field($this->data['transitionSpeed']);
            $transitionSpeed = (int)$transitionSpeed * 1000;
            update_option('transitionSpeed', $transitionSpeed);
    
            $slider1 = sanitize_text_field($this->data['slider1']);
            update_option('slider1', $slider1);
            $slider1Url = sanitize_text_field($this->data['slider1Url']);
            update_option('slider1Url', $slider1Url);
    
            $slider2 = sanitize_text_field($this->data['slider2']);
            update_option('slider2', $slider2);
            $slider2Url = sanitize_text_field($this->data['slider2Url']);
            update_option('slider2Url', $slider2Url);
    
            $slider3 = sanitize_text_field($this->data['slider3']);
            update_option('slider3', $slider3);
            $slider3Url = sanitize_text_field($this->data['slider3Url']);
            update_option('slider3Url', $slider3Url);
    
            $slider4 = sanitize_text_field($this->data['slider4']);
            update_option('slider4', $slider4);
            $slider4Url = sanitize_text_field($this->data['slider4Url']);
            update_option('slider4Url', $slider4Url);
    
            $slider5 = sanitize_text_field($this->data['slider5']);
            update_option('slider5', $slider5);
            $slider5Url = sanitize_text_field($this->data['slider5Url']);
            update_option('slider5Url', $slider5Url);
    
            $slider6 = sanitize_text_field($this->data['slider6']);
            update_option('slider6', $slider6);
            $slider6Url = sanitize_text_field($this->data['slider6Url']);
            update_option('slider6Url', $slider6Url);
    
            $slider7 = sanitize_text_field($this->data['slider7']);
            update_option('slider7', $slider7);
            $slider7Url = sanitize_text_field($this->data['slider7Url']);
            update_option('slider7Url', $slider7Url);
    
            $slider8 = sanitize_text_field($this->data['slider8']);
            update_option('slider8', $slider8);
            $slider8Url = sanitize_text_field($this->data['slider8Url']);
            update_option('slider8Url', $slider8Url);
    
            $slider9 = sanitize_text_field($this->data['slider9']);
            update_option('slider9', $slider9);
            $slider9Url = sanitize_text_field($this->data['slider9Url']);
            update_option('slider9Url', $slider9Url);
    
            $slider10 = sanitize_text_field($this->data['slider10']);
            update_option('slider10', $slider10);
            $slider10Url = sanitize_text_field($this->data['slider10Url']);
            update_option('slider10Url', $slider10Url);
    
            $slider11 = sanitize_text_field($this->data['slider11']);
            update_option('slider11', $slider11);
            $slider11Url = sanitize_text_field($this->data['slider11Url']);
            update_option('slider11Url', $slider11Url);
        }
    }    
}
