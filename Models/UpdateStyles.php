<?php
class UpdateStyles
{
    /** @var string */
    private $handle;

    function __construct($handle)
    {
        $this->handle = $handle;

        $this->setScript();
        $this->setStyles();
    }

    private function setScript()
    {
        add_action( 'admin_enqueue_scripts', 'dpt_add_color_picker' );
        function dpt_add_color_picker( $hook ) {

            if( is_admin() ) {

                // Add the color picker css file
                wp_enqueue_style( 'wp-color-picker' );

                // Include our custom jQuery file with WordPress Color Picker dependency
                wp_enqueue_script(
                    'custom-script-handle',
                    plugins_url( '/../Assets/js/wp-color-picker.js', __FILE__ ),
                    array( 'wp-color-picker' ),
                    '4.0.0'
                );
            }
        }
    }

    private function setStyles()
    {
        $css = '';
        $tableBackground = get_option('tableBackground');
        if (! empty($tableBackground)) {
            $css = "        
                .x-board-modern #time-table-section {
                    background-color: $tableBackground 
                }
                .x-board-modern .date-english-arabic {
                    background-color: $tableBackground 
                }
                .green {
                    color: $tableBackground 
                }
                .x-board-modern .mosque-name h2 {
                    color: $tableBackground 
                }
                .x-board-modern .clock {
                    color: $tableBackground 
                }
                ";
        }

        $tableHeading = get_option('tableHeading');
        if (! empty($tableHeading)) {
            $css .= "
                table.customStyles th.tableHeading{
                    background:" . get_option('tableHeading') . ";" .
                    "color:" . get_option( 'tableHeadingFont' )
                ."}";
        }

        $notificationBackground = get_option('notificationBackground');
        if (! empty($notificationBackground)) {
            $css .= "
                table.customStyles th.notificationBackground{
                    background:" . get_option('notificationBackground') . ";" .
                    "color: " . get_option( 'notificationFont' )
                ."}" .
                ".notificationBackground{
                    background:" . get_option('notificationBackground') . ";" .
                    "color: " . get_option( 'notificationFont' ) ."}";
        }

        $notificationFont = get_option('notificationFont');
        if (! empty($notificationFont)) {
            $css .= "
                table.customStyles th.notificationFont{ " .            
                    "color: " . get_option( 'notificationFont' )
                ."}" .
                ".notificationFont{ " .
                    "color: " . get_option( 'notificationFont' ) ."}";
        }

        $evenRow = get_option('evenRow');
        if (! empty($evenRow)) {
            $css .= "
                table.customStyles tr:nth-child(even) {
                    background:" . get_option('evenRow')
                ."}";
        }

        $fontColor = get_option('fontColor');
        if (! empty($fontColor)) {
            $css .= "
                table.customStyles {
                    color:" . get_option('fontColor')
                ."}
                
                ";
        }

        $prayerName = get_option('prayerName');
        if (! empty($prayerName)) {
            $css .= "
                table.customStyles th.prayerName{
                    background:" . get_option('prayerName')  . 
                "}";
        }

        $prayerNameFont = get_option('prayerNameFont');
        if (! empty($prayerNameFont)) {
            $css .= "
                table.customStyles th.prayerName{
                    color: " . get_option( 'prayerNameFont' )
                ."}

                .x-board-modern #time-table-section h4{
                    color: " . get_option( 'prayerNameFont' ) .
                "}";
        }

        $highlight = get_option('highlight') ?? 'red';
        $highlightFont = get_option('highlightFont') ?? 'white';

        $css .= "
            table.customStyles tr.highlight, th.highlight, td.highlight{
                font-weight: bold;
                background:" . $highlight ."!important;" .";
                color : " . $highlightFont ."!important;"
            ."}
            span.nextPrayer{
                font-weight: bold;
                color:" . $highlight  ."
            }
            .x-board tr.nextPrayer td{
                background-color: " . $highlight ." !important;" ."
                color: " . $highlightFont ." !important;" ."                  
            }

            .x-board-modern h4.nextPrayer, p.nextPrayer {
                background: " . $highlight ."!important;" ."
                color: " . $highlightFont ."!important;" ."
            }

            .d-masjid-e-usman  .nextPrayer h3, .nextPrayer .title, tr.nextPrayer, td span.nextPrayer {
                background: " . $highlight ."!important;" ."
                color: " . $highlightFont ."!important;" ."
            }
            .d-masjid-e-usman  .left-main-col-sun-times h4, .left-main-col-sun-times p {
                color: " . $highlight ."!important;" ."
            }
            ";

        $digitalScreenRed = get_option('digitalScreenRed');
        if (! empty($digitalScreenRed)) {
            $css .= "
            .x-board .bg-red {
                    background:" . get_option('digitalScreenRed') ."!important"
                ."}";
        }

        $digitalScreenLightRed = get_option('digitalScreenLightRed');
        if (! empty($digitalScreenLightRed)) {
            $css .= "
            .x-board .l-red {
                    background:" . get_option('digitalScreenLightRed') ."!important"
                ."}";
        }

        $digitalScreenGreen = get_option('digitalScreenGreen');
        if (! empty($digitalScreenGreen)) {
            $css .= "
            .x-board .bg-green {
                    background:" . get_option('digitalScreenGreen') ."!important"
                ."}";
        }

        $digitalScreenPrayerName = get_option('digitalScreenPrayerName');
        if (! empty($digitalScreenPrayerName)) {
            $css .= "
            .x-board td.prayerName {
                    background:" . get_option('digitalScreenPrayerName') ."!important"
                ."}";
        }
        wp_add_inline_style( $this->handle, $css );
    }
}