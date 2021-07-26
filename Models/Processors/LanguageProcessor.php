<?php
if ( !class_exists('DPTLanguageProcessor')) {
    class DPTLanguageProcessor
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
            if (! empty($this->data['prayersLocal'])) {
                $prayersLocal = $this->data['prayersLocal'];
                $prayersLocal = array_map( 'sanitize_text_field', $prayersLocal );
                $prayersLocal = array_map( array($this, 'cleanInput'), $prayersLocal );
    
                delete_option('prayersLocal');
                add_option('prayersLocal', $prayersLocal);
            }
    
            if (! empty($this->data['headersLocal'])) {
                $headersLocal = $this->data['headersLocal'];
                $headersLocal = array_map( 'sanitize_text_field', $headersLocal );
                $headersLocal = array_map( array($this, 'cleanInput'), $headersLocal );
    
                delete_option('headersLocal');
                add_option('headersLocal', $headersLocal);
            }
    
            if (! empty($this->data['monthsLocal'])) {
                $monthsLocal = $this->data['monthsLocal'];
                $monthsLocal = array_map( 'sanitize_text_field', $monthsLocal );
                $monthsLocal = array_map( array($this, 'cleanInput'), $monthsLocal );
    
                delete_option('monthsLocal');
                add_option('monthsLocal', $monthsLocal);
            }
    
            if ( ! empty($this->data['numbersLocal'])) {
                $numbersLocal = $this->data['numbersLocal'];
                $numbersLocal = array_map( 'sanitize_text_field', $numbersLocal );
                $numbersLocal = array_map( array($this, 'cleanInput'), $numbersLocal );
    
                delete_option('numbersLocal');
                add_option('numbersLocal', $numbersLocal);
            }
    
            if ( ! empty($this->data['timesLocal'])) {
                $timesLocal = $this->data['timesLocal'];
                $timesLocal = array_map( 'sanitize_text_field', $timesLocal );
                $timesLocal = array_map( array($this, 'cleanInput'), $timesLocal );
                
                delete_option('timesLocal');
                add_option('timesLocal', $timesLocal);
            }
        }
    
        private function cleanInput($localeData) {
            return preg_replace('/[\W] .-/', '', $localeData);
        }
    }
    
}
