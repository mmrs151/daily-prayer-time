<?php

class ThemeSettingsProcessor
{
    /** @var array */
    private $data;

    /**
     * @param array $data
     */
    function __construct(array $data)
    {
        $this->data = $data;
        if (is_array($data)) {
            $this->data = array_map( 'sanitize_text_field', $data);
        }
    }

    public function process()
    {
        update_option('hideTableBorder',        $this->data['hideTableBorder']);
        update_option('tableBackground',        $this->data['tableBackground']);
        update_option('tableHeading',           $this->data['tableHeading']);
        update_option('tableHeadingFont',       $this->data['tableHeadingFont']);
        update_option('evenRow',                $this->data['evenRow']);
        update_option('fontColor',              $this->data['fontColor']);
        update_option('highlight',              $this->data['highlight']);
        update_option('highlightFont',          $this->data['highlightFont']);
        update_option('notificationBackground', $this->data['notificationBackground']);
        update_option('notificationFont',       $this->data['notificationFont']);
        update_option('prayerName',             $this->data['prayerName']);
        update_option('prayerNameFont',         $this->data['prayerNameFont']);
        update_option('digitalScreenRed',       $this->data['digitalScreenRed']);
        update_option('digitalScreenLightRed',  $this->data['digitalScreenLightRed']);
        update_option('digitalScreenGreen',     $this->data['digitalScreenGreen']);
        update_option('digitalScreenGreenFont',$this->data['digitalScreenGreenFont']);
        update_option('digitalScreenPrayerName',$this->data['digitalScreenPrayerName']);
    }
}