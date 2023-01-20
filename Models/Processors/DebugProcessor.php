<?php
if ( ! class_exists('DPTDebugProcessor')) {
    class DPTDebugProcessor
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
            $activateDebug = sanitize_text_field($this->data['debugLog']);
            delete_option('debugSettings');
            add_option('debugSettings', $activateDebug);
        }
    }
    
}
