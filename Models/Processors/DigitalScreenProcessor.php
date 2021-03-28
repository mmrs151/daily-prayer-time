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

        $slider1 = $this->data['slider1'];
        update_option('slider1', $slider1);
        $slider1Url = $this->data['slider1Url'];
        update_option('slider1Url', $slider1Url);

        $slider2 = $this->data['slider2'];
        update_option('slider2', $slider2);
        $slider2Url = $this->data['slider2Url'];
        update_option('slider2Url', $slider2Url);

        $slider3 = $this->data['slider3'];
        update_option('slider3', $slider3);
        $slider3Url = $this->data['slider3Url'];
        update_option('slider3Url', $slider3Url);

        $slider4 = $this->data['slider4'];
        update_option('slider4', $slider4);
        $slider4Url = $this->data['slider4Url'];
        update_option('slider4Url', $slider4Url);

        $slider5 = $this->data['slider5'];
        update_option('slider5', $slider5);
        $slider5Url = $this->data['slider5Url'];
        update_option('slider5Url', $slider5Url);

        $slider6 = $this->data['slider6'];
        update_option('slider6', $slider6);
        $slider6Url = $this->data['slider6Url'];
        update_option('slider6Url', $slider6Url);

        $slider7 = $this->data['slider7'];
        update_option('slider7', $slider7);
        $slider7Url = $this->data['slider7Url'];
        update_option('slider7Url', $slider7Url);

        $slider8 = $this->data['slider8'];
        update_option('slider8', $slider8);
        $slider8Url = $this->data['slider8Url'];
        update_option('slider8Url', $slider8Url);

        $slider9 = $this->data['slider9'];
        update_option('slider9', $slider9);
        $slider9Url = $this->data['slider9Url'];
        update_option('slider9Url', $slider9Url);

        $slider10 = $this->data['slider10'];
        update_option('slider10', $slider10);
        $slider10Url = $this->data['slider10Url'];
        update_option('slider10Url', $slider10Url);

        $slider11 = $this->data['slider11'];
        update_option('slider11', $slider11);
        $slider11Url = $this->data['slider11Url'];
        update_option('slider11Url', $slider11Url);
    }
}
