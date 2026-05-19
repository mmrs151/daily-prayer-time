<?php
class UpdateStyles
{
    /** @var string */
    private $handle;

    /** @var array */
    private $options;

    public function __construct($handle)
    {
        $this->handle = $handle;
        $this->loadOptions();
        $this->setScript();
        $this->setStyles();
    }

    private function loadOptions()
    {
        $this->options = [
            'tableBackground' => get_option('tableBackground'),
            'tableHeading' => get_option('tableHeading'),
            'tableHeadingFont' => get_option('tableHeadingFont'),
            'notificationBackground' => get_option('notificationBackground'),
            'notificationFont' => get_option('notificationFont'),
            'evenRow' => get_option('evenRow'),
            'fontColor' => get_option('fontColor'),
            'prayerName' => get_option('prayerName'),
            'prayerNameFont' => get_option('prayerNameFont'),
            'highlight' => get_option('highlight') ?? 'red',
            'highlightFont' => get_option('highlightFont') ?? 'white',
            'digitalScreenRed' => get_option('digitalScreenRed'),
            'digitalScreenLightRed' => get_option('digitalScreenLightRed'),
            'digitalScreenGreen' => get_option('digitalScreenGreen'),
            'digitalScreenGreenFont' => get_option('digitalScreenGreenFont') ?? 'white',
            'digitalScreenPrayerName' => get_option('digitalScreenPrayerName'),
            'digitalScreenSlideBg' => get_option('digitalScreenSlideBg')
        ];
    }

    private function setScript()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueueColorPicker']);
    }

    public function enqueueColorPicker()
    {
        if (!is_admin()) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script(
            'custom-script-handle',
            plugins_url('/../Assets/js/wp-color-picker.js', __FILE__),
            ['wp-color-picker'],
            '4.0.0'
        );
    }

    private function setStyles()
    {
        $css = '';
        $css .= $this->getTableBackgroundStyles();
        $css .= $this->getTableHeadingStyles();
        $css .= $this->getNotificationStyles();
        $css .= $this->getPrayerNameStyles();
        $css .= $this->getHighlightStyles();
        $css .= $this->getDigitalScreenStyles();

        wp_add_inline_style($this->handle, $this->minifyCss($css));
    }

    private function getTableBackgroundStyles()
    {
        if (empty($this->options['tableBackground'])) {
            return '';
        }

        $color = $this->options['tableBackground'];

        return "
            .x-board-modern #time-table-section,
            .x-board-modern .date-english-arabic,
            .x-board-my-masjid .prayer-table-section,
            .x-board-my-masjid .next-banner {
                background: {$color} !important
            }
            .green,
            .x-board-modern .mosque-name h2,
            .x-board-modern .clock,
            .x-board-my-masjid .highlight-text {
                color: {$color}
            }
            .dpt-horizontal-wrapper.customStyles {
                background-color: {$color} !important
            }
        ";
    }

    private function getTableHeadingStyles()
    {
        if (empty($this->options['tableHeading'])) {
            return '';
        }

        return "
            table.customStyles th.tableHeading {
                background: {$this->options['tableHeading']};
                color: {$this->options['tableHeadingFont']}
            }
        ";
    }

    private function getNotificationStyles()
    {
        $styles = '';

        if (!empty($this->options['notificationBackground'])) {
            $styles .= "
                table.customStyles th.notificationBackground,
                .notificationBackground {
                    background: {$this->options['notificationBackground']};
                    color: {$this->options['notificationFont']}
                }
            ";
        }

        if (!empty($this->options['notificationFont'])) {
            $styles .= "
                table.customStyles th.notificationFont,
                .notificationFont {
                    color: {$this->options['notificationFont']}
                }
            ";
        }

        if (!empty($this->options['evenRow'])) {
            $styles .= "
                table.customStyles tr:nth-child(even) {
                    background: {$this->options['evenRow']}
                }
            ";
        }

        if (!empty($this->options['fontColor'])) {
            $styles .= "
                table.customStyles {
                    color: {$this->options['fontColor']}
                }
            ";
        }

        return $styles;
    }

    private function getPrayerNameStyles()
    {
        $styles = '';

        if (!empty($this->options['prayerName'])) {
            $styles .= "
                table.customStyles th.prayerName {
                    background: {$this->options['prayerName']}
                }
            ";
        }

        if (!empty($this->options['prayerNameFont'])) {
            $styles .= "
                table.customStyles th.prayerName {
                    color: {$this->options['prayerNameFont']}
                }
                .x-board-modern #time-table-section h4 {
                    color: {$this->options['prayerNameFont']}
                }
            ";
        }

        return $styles;
    }

    private function getHighlightStyles()
    {
        $styles = '';
    
        if (!empty($this->options['highlight'])) {
            $highlightBackground = $this->options['highlight'];
            
            $styles .= "
                :root {
                    --dpt-highlight: {$highlightBackground} !important;
                }
                table.customStyles tr.highlight, th.highlight, td.highlight {
                    background: {$highlightBackground} !important;
                }
            ";
        }
    
        if (!empty($this->options['highlightFont'])) {
            $highlightFont = $this->options['highlightFont'];
            $styles .= "
                :root {
                    --dpt-highlight-font: {$highlightFont} !important;
                }
                table.customStyles tr.highlight, th.highlight, td.highlight {
                    color: {$highlightFont} !important;
                }
                div.dptScNextPrayer {
                    color: {$highlightFont};
                }
            ";
        }
    
        return $styles;
    }

    private function getDigitalScreenStyles()
    {
        $styles = '';

        if (!empty($this->options['digitalScreenRed'])) {
            $styles .= "
                .x-board .bg-red {
                    background: {$this->options['digitalScreenRed']} !important
                }
            ";
        }

        if (!empty($this->options['digitalScreenLightRed'])) {
            $styles .= "
                .x-board .l-red {
                    background: {$this->options['digitalScreenLightRed']} !important
                }
            ";
        }

        if (!empty($this->options['digitalScreenGreen'])) {
            $nextPrayerRowBg = $this->options['digitalScreenGreen'];
            $styles .= "
                #dsPrayerTimetable tr.nextPrayer td,
                .x-board tr.nextPrayer td {
                    background: {$nextPrayerRowBg} !important;
                }
            ";
        }
        
        if (!empty($this->options['digitalScreenSlideBg'])) {
            $slideBg = $this->options['digitalScreenSlideBg'];
            $styles .= "
                .x-board .bg-green {
                    background-color: {$slideBg} !important
                }
                .x-board-modern h4.nextPrayer, 
                .x-board-modern p.nextPrayer {
                    background-color: {$slideBg} !important;
                }
                .d-masjid-e-usman .nextPrayer h3,
                .nextPrayer .title,
                .dpt-wrapper-container .prayer-time.highlight {
                    background-color: {$slideBg} !important;
                }
                .d-masjid-e-usman .left-main-col-sun-times h4,
                .left-main-col-sun-times p {
                    background-color: {$slideBg} !important;
                }
            ";
        }
        
        if (!empty($this->options['digitalScreenGreenFont'])) {
            $nextPrayerFont = $this->options['digitalScreenGreenFont'];
            $styles .= "
                .x-board .bg-green td,
                .x-board .bg-green div,
                .x-board .bg-green h2,
                .x-board .bg-green h3,
                .x-board .bg-green h4 {
                    color: {$nextPrayerFont} !important;
                }                
                .x-board-modern h4.nextPrayer, p.nextPrayer {
                    color: {$nextPrayerFont} !important;
                }
                .x-board span.nextPrayer {
                    font-weight: bold;
                    color: {$nextPrayerFont};
                }
                .x-board tr.nextPrayer td {
                    color: {$nextPrayerFont} !important;
                }
            ";
        }
    
        if (!empty($this->options['digitalScreenPrayerName'])) {
            $styles .= "
                .x-board td.prayerName {
                    background: {$this->options['digitalScreenPrayerName']} !important
                }
            ";
        }

        return $styles;
    }

    private function minifyCss($css)
    {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        // Remove space after colons and unnecessary whitespace
        $css = str_replace([': ', "\r\n", "\r", "\n", "\t", '  ', '    '], [':', ' '], $css);
        return trim($css);
    }
}
