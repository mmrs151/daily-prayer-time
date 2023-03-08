<?php
if ( !class_exists('DPTHijriProcessor')) {
    class DPTHijriProcessor
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
            $hijri = sanitize_text_field($this->data['hijri-chbox']);
            delete_option('hijri-chbox');
            add_option('hijri-chbox', $hijri);

            $hijriUmmulQura = sanitize_text_field($this->data['hijri-ummul-qura']);
            delete_option('hijri-ummul-qura');
            add_option('hijri-ummul-qura', $hijriUmmulQura);

            $hijriArabic = sanitize_text_field($this->data['hijri-arabic-chbox']);
            delete_option('hijri-arabic-chbox');
            add_option('hijri-arabic-chbox', $hijriArabic);
    
            $hijriAdjust = sanitize_text_field($this->data['hijri-adjust']);
            delete_option('hijri-adjust');
            add_option('hijri-adjust', $hijriAdjust);

            $isRamadan = $this->data['ramadan_chbox'] ?? '';
            $isRamadan = sanitize_text_field($isRamadan);
            delete_option('ramadan_chbox');
            add_option('ramadan_chbox', $isRamadan);
            
            $taraweehDim = sanitize_text_field($this->data['taraweehDim'] ?? '');
            delete_option('taraweehDim');
            add_option('taraweehDim', $taraweehDim);

            $imsaq = $this->data['imsaq'] ?? '';
            $imsaq = sanitize_text_field($imsaq);
            delete_option('imsaq');
            add_option('imsaq', $imsaq);
        }
    }
    
}
