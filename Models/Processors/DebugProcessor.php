<?php
if ( ! class_exists('DPTDebugProcessor')) {
    class DPTDebugProcessor
    {
        /**
         * @var array
         */
        private $data;

        /** @var string */
        private $filePath = '';
    
        /** @var Resource */
        private static $fp = null;

        /**
         * @param array $data
         */
        public function __construct(array $data=null) {
            $this->data = $data;
            $this->filePath = plugin_dir_path(__FILE__) . '../../Assets/debug.csv';
            if ($this->fp == null) {
                $this->fp = fopen($this->filePath, 'a');
            }
        }
    
        public function process()
        {
            $activateDebug = sanitize_text_field($this->data['debugLog']);
            delete_option('debugActivated');
            add_option('debugActivated', $activateDebug);
        }

        public function log($data)
        {
            $data = date("Y-m-d H:i:s ") . $data . PHP_EOL;
            fwrite($this->fp, $data);
        }

        public function getFilePath()
        {
            return $this->filePath;
        }

        public function resetLog()
        {
            $this->fp = fopen($this->filePath, 'w');
            fwrite($this->fp, 'starting Log: ' . date("Y-m-d H:i:s ") . "\n");
        }
    }
}