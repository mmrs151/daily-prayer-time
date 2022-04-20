<?php
if ( ! class_exists('DPTOtherProcessor')) {
    class DPTOtherProcessor
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
            $jumuah = sanitize_text_field($this->data['jumuah']);
            delete_option('jumuah');
            add_option('jumuah', $jumuah);
    
            $ramadan = sanitize_text_field($this->data['ramadan-chbox']);
            delete_option('ramadan-chbox');
            add_option('ramadan-chbox', $ramadan);
    
            if (! empty($this->data['asrSelect'])) {
                $asrSelect = sanitize_text_field($this->data['asrSelect']);
                delete_option('asrSelect');
                add_option('asrSelect', $asrSelect);
            }
    
            $jamahChanges = $this->data['jamah_changes'] ?? '';
            $jamahChanges = sanitize_text_field($jamahChanges);
            delete_option('jamah_changes');
            add_option('jamah_changes', $jamahChanges);
    
            $imsaq = $this->data['imsaq'] ?? '';
            $imsaq = sanitize_text_field($imsaq);
            delete_option('imsaq');
            add_option('imsaq', $imsaq);

            $tomorrowTime = $this->data['tomorrow_time'] ?? '';
            $tomorrowTime = sanitize_text_field($tomorrowTime);
            delete_option('tomorrow_time');
            add_option('tomorrow_time', $tomorrowTime);
        }
    }
    
}
