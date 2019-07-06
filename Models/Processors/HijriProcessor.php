<?php

class HijriProcessor
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
        $hijri = $this->data['hijri-chbox'];
        delete_option('hijri-chbox');
        add_option('hijri-chbox', $hijri);

        $hijriAdjust = $this->data['hijri-adjust'];
        delete_option('hijri-adjust');
        add_option('hijri-adjust', $hijriAdjust);
    }
}
