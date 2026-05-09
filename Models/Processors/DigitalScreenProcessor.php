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
            update_option('ds-logo',           sanitize_text_field($this->data['ds-logo']));
            update_option('ds-scroll-text',    sanitize_text_field($this->data['ds-scroll-text']));
            update_option('ds-scroll-speed',   sanitize_text_field($this->data['ds-scroll-speed']));
            update_option('ds-blink-text',     sanitize_text_field($this->data['ds-blink-text']));
            update_option('ds-additional-css', trim($this->data['ds-additional-css'] ?? ''));
            update_option('ds-fading-msg',     trim($this->data['ds-fading-msg'] ?? ''));
            update_option('template-chbox',    sanitize_text_field($this->data['template-chbox']));
            update_option('quran-chbox',       sanitize_text_field($this->data['quran-chbox']));
            update_option('slider-chbox',      sanitize_text_field($this->data['slider-chbox']));
            update_option('nextPrayerSlide',   sanitize_text_field($this->data['nextPrayerSlide']));
            update_option('dsTemplate',        sanitize_text_field($this->data['ds-template']));
            update_option('transitionEffect',  sanitize_text_field($this->data['transitionEffect']));
            update_option('transitionSpeed',   (int) sanitize_text_field($this->data['transitionSpeed']) * 1000);

            for ($i = 1; $i <= 13; $i++) {
                $value = sanitize_text_field($this->data["slider$i"] ?? '');
                $url   = sanitize_text_field($this->data["slider{$i}Url"] ?? '');

                if ($value !== '') {
                    update_option("slider$i", $value);
                }
                if ($url !== '') {
                    update_option("slider{$i}Url", $url);
                }
            }
        }
    }
}
