<?php

class ThemeSettingsProcessor
{
    private $data;

    function __construct(array $data)
    {
        $this->data = $data;
        if (is_array($data)) {
            $this->data = array_map( 'sanitize_text_field', $data);
        }
    }

    public function process()
    {
        $this->saveOption('hideTableBorder');
        $this->saveOption('tableBackground');
        $this->saveOption('tableHeading');
        $this->saveOption('tableHeadingFont');
        $this->saveOption('evenRow');
        $this->saveOption('fontColor');
        $this->saveOption('highlight');
        $this->saveOption('highlightFont');
        $this->saveOption('notificationBackground');
        $this->saveOption('notificationFont');
        $this->saveOption('prayerName');
        $this->saveOption('prayerNameFont');
        $this->saveOption('digitalScreenRed');
        $this->saveOption('digitalScreenLightRed');
        $this->saveOption('digitalScreenGreen');
        $this->saveOption('digitalScreenGreenFont');
        $this->saveOption('digitalScreenPrayerName');
        $this->saveOption('digitalScreenSlideBg');
    }
    
    private function saveOption($key)
    {
        $value = $this->data[$key] ?? '';
        
        if (empty($value) || $value === '0') {
            delete_option($key);
        } elseif (preg_match('/^#[0-9A-Fa-f]{6}$/', $value)) {
            update_option($key, $value);
        }
    }
}