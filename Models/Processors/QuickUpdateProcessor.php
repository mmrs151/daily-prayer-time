<?php
if (!class_exists('DPTQuickUpdateProcessor')) {
    class DPTQuickUpdateProcessor
    {
        /**
         * @var array
         */
        private $data;
    
        /**
         * @param array $data
         */
        public function __construct(array $data)
        {
            $this->data = $data;
        }
    
        public function process()
        {
            $db = new DatabaseConnection();
            $this->validate($this->data);
            $db->updateRow($this->data);
        }
    
        /**
         * validate user input of prayer time
         */
        private function validate(array $rows)
        {
            foreach($rows as $row) {
                foreach($row as $name => $time) {
                    if ($name == 'd_date') continue;
                    if ( ! $this->isValidateTimeFormat($time) ) {
                        return false;
                    }
                }
            }
            return true;
        }
    
        /**
         * @param string $time
         * @return bool
         */
        private function isValidateTimeFormat($time)
        {
            $time = trim($time);
            $pattern1 = "/^([0-9]|[01][0-9]|2[0-3]):[0-5][0-9]$/"; // HH:MM or H:MM
            $pattern2 = "/^([0-9]|[01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/"; // HH:MM or H:MM
    
            if ( preg_match($pattern1, $time, $matches) || preg_match($pattern2, $time, $matches) ) {
                return true;
            }
    
            return false;
        }
    }
}
