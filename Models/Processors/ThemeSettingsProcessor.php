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
        $this->data = array_map( 'sanitize_text_field', $data);
    }

    public function process()
    {
        $hideTableBorder = $this->data['hideTableBorder'];
        delete_option('hideTableBorder');
        add_option('hideTableBorder', $hideTableBorder);

        $tableBackground = $this->data['tableBackground'];
        delete_option('tableBackground');
        add_option('tableBackground', $tableBackground);

        $tableHeading = $this->data['tableHeading'];
        delete_option('tableHeading');
        add_option('tableHeading', $tableHeading);

        $tableHeadingFont = $this->data['tableHeadingFont'];
        delete_option('tableHeadingFont');
        add_option('tableHeadingFont', $tableHeadingFont);

        $evenRow = $this->data['evenRow'];
        delete_option('evenRow');
        add_option('evenRow', $evenRow);

        $fontColor = $this->data['fontColor'];
        delete_option('fontColor');
        add_option('fontColor', $fontColor);

        $highlight = $this->data['highlight'];
        delete_option('highlight');
        add_option('highlight', $highlight);

        $notificationBackground = $this->data['notificationBackground'];
        delete_option('notificationBackground');
        add_option('notificationBackground', $notificationBackground);

        $notificationFont = $this->data['notificationFont'];
        delete_option('notificationFont');
        add_option('notificationFont', $notificationFont);

        $prayerName = $this->data['prayerName'];
        delete_option('prayerName');
        add_option('prayerName', $prayerName);

        $prayerNameFont = $this->data['prayerNameFont'];
        delete_option('prayerNameFont');
        add_option('prayerNameFont', $prayerNameFont);

        $digitalScreenRed = $this->data['digitalScreenRed'];
        delete_option('digitalScreenRed');
        add_option('digitalScreenRed', $digitalScreenRed);

        $digitalScreenLightRed = $this->data['digitalScreenLightRed'];
        delete_option('digitalScreenLightRed');
        add_option('digitalScreenLightRed', $digitalScreenLightRed);

        $digitalScreenGreen = $this->data['digitalScreenGreen'];
        delete_option('digitalScreenGreen');
        add_option('digitalScreenGreen', $digitalScreenGreen);

        $digitalScreenPrayerName = $this->data['digitalScreenPrayerName'];
        delete_option('digitalScreenPrayerName');
        add_option('digitalScreenPrayerName', $digitalScreenPrayerName);
    }
}