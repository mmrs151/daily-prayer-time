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
        private $fp = null;

        /** @var bool */
        private $isDebug = false;

        /**
         * @param array $data
         */
        public function __construct(array $data=null) {
            $this->data = $data;
            // $this->filePath = plugin_dir_path(__FILE__) . '../../Assets/debug.csv';
            $this->filePath = fopen('php://memory', 'rw+');

            if ($this->fp == null) {
                // $this->fp = fopen($this->filePath, 'a');
                $this->fp = $this->filePath;
            }

            $this->isDebug = get_option('debugActivated');
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
            if (!empty($this->isDebug)) {
                fwrite($this->fp, $data);                
            }
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