<?php
if ( ! class_exists('DPTAdhanProcessor')) {
    class DPTAdhanProcessor
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
            $fajrAdhanUrl = wp_http_validate_url($this->data['fajrAdhanUrl']);
            delete_option('fajrAdhanUrl');
            add_option('fajrAdhanUrl', $fajrAdhanUrl);

            $otherAdhanUrl = wp_http_validate_url($this->data['otherAdhanUrl']);
            delete_option('otherAdhanUrl');
            add_option('otherAdhanUrl', $otherAdhanUrl);

            $fajrAdhanBefore = (int)$this->data['fajrAdhanBefore'];
            delete_option('fajrAdhanBefore');
            add_option('fajrAdhanBefore', $fajrAdhanBefore);

            $zuhrAdhanBefore = (int)$this->data['zuhrAdhanBefore'];
            delete_option('zuhrAdhanBefore');
            add_option('zuhrAdhanBefore', $zuhrAdhanBefore);

            $asrAdhanBefore = (int)$this->data['asrAdhanBefore'];
            delete_option('asrAdhanBefore');
            add_option('asrAdhanBefore', $asrAdhanBefore);

            $ishaAdhanBefore = (int)$this->data['ishaAdhanBefore'];
            delete_option('ishaAdhanBefore');
            add_option('ishaAdhanBefore', $ishaAdhanBefore);
        }
    }
    
}
