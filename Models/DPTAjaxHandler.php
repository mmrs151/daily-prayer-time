<?php

class DPTAjaxHandler extends DailyShortCode
{
    public function __construct()
    {
        $this->addAjaxActions();
        parent::__construct();
    }

    public function get_ds_next_prayer()
    {
        echo do_shortcode("[daily_next_prayer]");
        die();
    }

    private function addAjaxActions()
    {
        $f = new ReflectionClass('DPTAjaxHandler');
        foreach ($f->getMethods() as $m) {
            if ($m->class == 'DPTAjaxHandler') {
                add_action( 'wp_ajax_'.$m->name, array( $this, $m->name) );
                add_action( 'wp_ajax_nopriv_'.$m->name, array( $this, $m->name) );
            }
        }
    }
}