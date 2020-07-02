<?php

class DigitalScreenProcessor
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
        $dsLogo = $this->data['ds-logo'];
        delete_option('ds-logo');
        add_option('ds-logo', $dsLogo);
        
        $slider = $this->data['slider-chbox'];
        delete_option('slider-chbox');
        add_option('slider-chbox', $slider);
    
        $nextPrayerSlide = $this->data['nextPrayerSlide'];
        update_option('nextPrayerSlide', $nextPrayerSlide);
        
        $transitionEffect = $this->data['transitionEffect'];
        update_option('transitionEffect', $transitionEffect);

        $transitionSpeed = $this->data['transitionSpeed'];
        $transitionSpeed = (int)$transitionSpeed * 1000;
        update_option('transitionSpeed', $transitionSpeed);

        $slider1Url = $this->data['slider1Url'];
        update_option('slider1Url', $slider1Url);

        $slider2Url = $this->data['slider2Url'];
        update_option('slider2Url', $slider2Url);

        $slider3Url = $this->data['slider3Url'];
        update_option('slider3Url', $slider3Url);

        $slider4Url = $this->data['slider4Url'];
        update_option('slider4Url', $slider4Url);

        $slider5Url = $this->data['slider5Url'];
        update_option('slider5Url', $slider5Url);

        $slider6Url = $this->data['slider6Url'];
        update_option('slider6Url', $slider6Url);

        $slider7Url = $this->data['slider7Url'];
        update_option('slider7Url', $slider7Url);

        $slider8Url = $this->data['slider8Url'];
        update_option('slider8Url', $slider8Url);

        $slider9Url = $this->data['slider9Url'];
        update_option('slider9Url', $slider9Url);

        $slider10Url = $this->data['slider10Url'];
        update_option('slider10Url', $slider10Url);

        $slider11Url = $this->data['slider11Url'];
        update_option('slider11Url', $slider11Url);

    }
}
