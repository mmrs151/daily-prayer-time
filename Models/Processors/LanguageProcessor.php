<?php

class LanguageProcessor
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
            delete_option('prayersLocal');
            add_option('prayersLocal', $prayersLocal);
        }

        if (! empty($this->data['headersLocal'])) {
            $headersLocal = $this->data['headersLocal'];
            delete_option('headersLocal');
            add_option('headersLocal', $headersLocal);
        }

        if (! empty($this->data['monthsLocal'])) {
            $monthsLocal = $this->data['monthsLocal'];
            delete_option('monthsLocal');
            add_option('monthsLocal', $monthsLocal);
        }

        if ( ! empty($this->data['numbersLocal'])) {
            $numbersLocal = $this->data['numbersLocal'];
            delete_option('numbersLocal');
            add_option('numbersLocal', $numbersLocal);
        }

        if ( ! empty($this->data['timesLocal'])) {
            $timesLocal = $this->data['timesLocal'];
            delete_option('timesLocal');
            add_option('timesLocal', $timesLocal);
        }
    }
}
