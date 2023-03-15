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
            $jumuah1 = sanitize_text_field($this->data['jumuah1'] ?? '');
            delete_option('jumuah1');
            add_option('jumuah1', $jumuah1);

            $jumuah2 = sanitize_text_field($this->data['jumuah2'] ?? '');
            delete_option('jumuah2');
            add_option('jumuah2', $jumuah2);

            $jumuah3 = sanitize_text_field($this->data['jumuah3'] ?? '');
            delete_option('jumuah3');
            add_option('jumuah3', $jumuah3);

            $khutbahDim = sanitize_text_field($this->data['khutbahDim'] ?? '');
            delete_option('khutbahDim');
            add_option('khutbahDim', $khutbahDim);
        
            if (! empty($this->data['asrSelect'])) {
                $asrSelect = sanitize_text_field($this->data['asrSelect']);
                delete_option('asrSelect');
                add_option('asrSelect', $asrSelect);
            }
    
            $jamahChanges = $this->data['jamah_changes'] ?? '';
            $jamahChanges = sanitize_text_field($jamahChanges);
            delete_option('jamah_changes');
            add_option('jamah_changes', $jamahChanges);

            $tomorrowTime = $this->data['tomorrow_time'] ?? '';
            $tomorrowTime = sanitize_text_field($tomorrowTime);
            delete_option('tomorrow_time');
            add_option('tomorrow_time', $tomorrowTime);
        }
    }
}
