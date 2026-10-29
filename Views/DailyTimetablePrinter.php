<?php
require_once( 'TimetablePrinter.php' );
require_once(__DIR__ . '/../Models/DPTHelper.php');

class DailyTimetablePrinter extends TimetablePrinter
{
    /** @var DPTHelper */
    protected $dptHelper;

    public function __construct()
    {
        parent::__construct();
        $this->localPrayerNames = $this->getLocalPrayerNames(false, true);
        $this->dptHelper = new DPTHelper();
    }

    public function horizontalTimeDiv($row)
    {
        ob_start();
        include 'horizontal-div.php';
        return ob_get_clean();
    }

    /**
     * @param $row
     * @return string
     */
    public function printHorizontalTime($row)
    {
        $table = $this->printHorizontalTableTop( $row );

        $table .= '
            <tr class="dpt-horizontal-names">
                <th class="tableHeading prayerName" scope="col">' . $this->localHeaders['prayer'] . '</th>'
                  . $this->printTableHeading($row) .
            '</tr>
            <tr class="dpt-horizontal-begins">
                <th class="tableHeading" scope="row">' .$this->localHeaders['begins']. '</th>'
                  .$this->printAzanTime($row).
            '</tr>
            <tr class="dpt-horizontal-iqamah"><th class="tableHeading" scope="row">' .$this->localHeaders['iqamah']. '</th>'
                  .$this->printJamahTime($row, false).
            '</tr>';

            $nextPrayer = $this->getNextPrayer( $row );

        // Determine whether to show Jumuah row: only when jumuah times are configured
        // and current time is before the last configured Jumuah time (on Fridays).
        $jumuahOptions = array_filter([ get_option('jumuah1'), get_option('jumuah2'), get_option('jumuah3') ]);
        $showJumuah = !empty($row['showJumuah']);
        $isFriday = $this->todayIsFriday();
        if (! $showJumuah && ! empty($jumuahOptions) && $isFriday) {
            $nowTs = strtotime( user_current_time('H:i') );
            $lastJumuahTs = max( array_map('strtotime', $jumuahOptions) );
            if ($nowTs < $lastJumuahTs) {
                $showJumuah = true;
            }
        }

        $shouldHighlightJumuah = ($nextPrayer == 'jumuah') || ($isFriday && $showJumuah);
        if ( $shouldHighlightJumuah || $showJumuah ) {
            $jumuahClass = $shouldHighlightJumuah ? 'highlight' : '';
            $table .= '<tr class="' . $jumuahClass . '">
                            <th class="tableHeading">' . stripslashes($this->getLocalHeaders()['jumuah']) . '</th>
                            <td colspan="6" class="jamah">' . $this->getJumuahTimesArray() . '</td>
                        </tr>';
        }

        $table .= '</table></div></div>';

        return $table;
    }

    public function horizontalTimeJamahOnly($row)
    {
        $table = $this->printHorizontalTableTop( $row );

        $table .= '
            <tr class="dpt-horizontal-names"><th scope="col">' .$this->localHeaders['prayer']. '</th>'
                  . $this->printTableHeading($row) .
            '</tr>
            <tr class="dpt-horizontal-iqamah">
                <th scope="row">'.$this->localHeaders['iqamah'].'</th>'
                  .$this->printJamahTime($row).
            '</tr>
        </table></div></div>';

        return $table;
    }

    /**
     * @param $row
     *
     * @return string
     */
    public function horizontalTimeAzanOnly($row)
    {
        $table = $this->printHorizontalTableTop( $row, true );

        $table .=   '
            <tr class="dpt-horizontal-names"><th scope="col">' .$this->localHeaders['prayer']. '</th>'
                    . $this->printTableHeading($row) .
            '</tr>
            <tr class="dpt-horizontal-begins">
                <th scope="row">'.$this->localHeaders['begins'].'</th>'
                    .$this->printAzanTime($row).
            '</tr>
        </table></div></div>';

        return $table;
    }

    /**
     * @param $row
     * @return string
     */
    public function printVerticalTime($row)
    {
        $table = $this->printVerticalTableTop( $row , true);

        $table .=
            '<tr class="dpt-vertical-headings">
                <th class="tableHeading" scope="col">' .$this->localHeaders['prayer']. '</th>
                <th class="tableHeading" scope="col">' .$this->localHeaders['begins']. '</th>
                <th class="tableHeading" scope="col">' .$this->localHeaders['iqamah']. '</th>
            </tr>'
            . $this->printVerticalRow( $row, 'both' ) .
            '</table></div>';

        return $table;
    }

    /**
     * @param  array $row
     * @return string
     */
    public function verticalTimeJamahOnly($row)
    {
        $table = $this->printVerticalTableTop( $row );

        $table .=
            '<tr class="dpt-vertical-headings">
                <th class="tableHeading" scope="col">' .$this->localHeaders['prayer']. '</th>
                <th class="tableHeading" scope="col">' .$this->localHeaders['iqamah']. '</th>
            </tr>'
            .$this->printVerticalRow( $row, 'iqamah' ) .
            '</table></div>';

        return $table;
    }

    /**
     * @param  array $row
     * @return string
     */
    public function verticalTimeAzanOnly($row)
    {
        $table = $this->printVerticalTableTop( $row, false, true );

        $table .=
            '<tr class="dpt-vertical-headings">
                <th class="tableHeading" scope="col">' .$this->localHeaders['prayer']. '</th>
                <th class="tableHeading" scope="col">' .$this->localHeaders['begins']. '</th>
            </tr>'
            .$this->printVerticalRow( $row, 'azan' ) .
            '</table></div>';

        return $table;
    }

    /**
     * @param $row
     *
     * @param bool $isAzanOnly
     *
     * @return string
     */
    private function printHorizontalTableTop($row, $isAzanOnly=false)
    {
        $announcement = '';
        $nextIqamah = '';
        if (! $row['hideTimeRemaining']) {
            $nextIqamah = $isAzanOnly == true ? '' : $this->getNextIqamahTime($row);
        }
        $colspan = 8;
        $ramadanTds = '<td></td>';

        $ramadan = '';
        if ($this->isRamadan() && ! $row['hideRamadan']) {
            $ramadan = '
                <tr class="">
                    <td colspan="3" class="highlight">'. $this->localHeaders['fast_begins'].': '.$this->formatDateForPrayer($row['fajr_begins'], true).'</td>
                    '. $ramadanTds . '
                    <td colspan="3" class="highlight">'. $this->localHeaders['fast_ends'].': '.$this->formatDateForPrayer($row['maghrib_begins']).'</td>
                </tr>';
        }

        if(isset($row['announcement']) && ! empty( $row['announcement'] )) {
            $announcement = "<tr><th colspan='".$colspan."' style='text-align:center' class='notificationBackground notificationFont'>".$row['announcement']. "</th></tr>";
        }

        $table = "";
        $date = date_i18n(get_option('date_format'));
        $hijriDate = $this->getHijriDate(date('d'), date('m'), date('Y'), $row);
        $title = trim((string) $row['widgetTitle']);
        $table .=
            '<div class="dpt-horizontal-shell alignwide">
                <div class="dpt-horizontal-overview">
                    <div class="dpt-horizontal-date">
                        <span class="dpt-horizontal-kicker">' . esc_html($title !== '' ? $title : __('Prayer times', 'daily-prayer-time')) . '</span>
                        <span class="dpt-horizontal-date-main">' . esc_html($date) . '</span>'
                        . ($hijriDate ? '<span class="dpt-horizontal-hijri">' . esc_html($hijriDate) . '</span>' : '') . '
                    </div>'
                    . ($nextIqamah !== '' ? '<div class="dpt-horizontal-next">' . $nextIqamah . '</div>' : '') . '
                </div>
                <div class="dpt-horizontal-table-scroll">
                    <table class="customStyles dptUserStyles dptTimetable dpt-horizontal-table ' . $this->getTableClass() . '" aria-label="' . esc_attr__('Daily prayer times', 'daily-prayer-time') . '"> '
                    . $announcement . $ramadan;

        return $table;
    }

    public function displayRamadanTime($row)
    {
      return '  <table class="customStyles dptUserStyles">
                <tr style="text-align:center">
                    <td colspan="3" class="fasting highlight">'. $this->localHeaders['fast_begins'].': '.$this->formatDateForPrayer($row['fajr_begins'], true).'</td>
                    <td style="border:0px;"></td>
                    <td colspan="3" class="fasting highlight">'. $this->localHeaders['fast_ends'].': '.$this->formatDateForPrayer($row['maghrib_begins']).'</td>
                </tr></table>';
    }

    /**
     * @param $row
     *
     * @return string
     */
    private function printTableHeading($row)
    {
        $ths = '';
        $nextPrayer = $this->getNextPrayer( $row );

        $localPrayerNames = $this->localPrayerNames;
        
        // Handle ishraq - merge into sunrise row, show only sunrise
        $ishraqMins = get_option('ishraq');
        if ($ishraqMins && $ishraqMins != '0' && isset($localPrayerNames['ishraq'])) {
            if ($this->dptHelper->isIshraqTimeNext($row)) {
                $localPrayerNames['sunrise'] = $localPrayerNames['ishraq'];
            }
        }
        if (isset($localPrayerNames['ishraq'])) {
            unset($localPrayerNames['ishraq']);
        }
        
        // Handle zawal - show zawal instead of sunrise if zawal time is next
        if (get_option('zawal') && $this->dptHelper->isZawalTimeNext($row)) {
            $localPrayerNames['sunrise'] = $localPrayerNames['zawal'] ?? 'Zawal';
        }
        
        // Remove zawal - never show as separate prayer name
        if (isset($localPrayerNames['zawal'])) {
            unset($localPrayerNames['zawal']);
        }
        
        foreach ($localPrayerNames as $key=>$prayerName) {
            $shouldHighlight = false;
            if ($key == 'sunrise' && $nextPrayer == 'ishraq') {
                $shouldHighlight = true;
            } elseif ($key != 'sunrise' && $nextPrayer == $key) {
                $shouldHighlight = true;
            }
            // On Friday, don't highlight Zuhr (Jumuah row is highlighted instead)
            if ($this->todayIsFriday() && $key == 'zuhr') {
                $shouldHighlight = false;
            }
            $class = $shouldHighlight ? 'highlight' : '';
            $ths .= "<th class='tableHeading prayerName" . $this->tableClass . " ". $class."'>".$prayerName."</th>";
        }

        return $ths;
    }

    private function toggleSunriseZawal($row, $prayerNames)
    {
        unset($prayerNames['zawal']);
        return $prayerNames;
    }

    /**
     * @param $row
     *
     * @return string
     */
    private function printAzanTime($row)
    {
        $tds = '';
        $nextPrayer = $this->getNextPrayer( $row );
        $azanTimings = $this->getAzanTime( $row );

        // Handle ishraq - merge into sunrise row (check FIRST)
        $ishraqMins = get_option('ishraq');
        if ($ishraqMins && $ishraqMins != '0' && $this->dptHelper->isIshraqTimeNext($row)) {
            $azanTimings['sunrise'] = $this->dptHelper->getIshraqTime($row['sunrise']);
        } elseif (get_option('zawal') && $this->dptHelper->isZawalTimeNext($row)) {
            // Handle zawal - show zawal time instead of sunrise if zawal time is next
            $azanTimings['sunrise'] = $this->dptHelper->getZawalTime($row['zuhr_begins']);
        }

        foreach ($azanTimings as $key => $azan) {

            $shouldHighlight = false;
            if ($key == 'sunrise' && $nextPrayer == 'ishraq') {
                $shouldHighlight = true;
            } elseif ($key != 'sunrise' && $nextPrayer == $key) {
                $shouldHighlight = true;
            }
            // On Friday, don't highlight Zuhr (Jumuah row is highlighted instead)
            if ($this->todayIsFriday() && $key == 'zuhr') {
                $shouldHighlight = false;
            }

            $class = $shouldHighlight ? "class='highlight'" : '';
            $rowspan = ($key == 'sunrise') ? "rowspan='2'" : '';
            $tds .= "<td ". $rowspan ." ".$class.">".$this->getFormattedDateForPrayer( $azan, $key)."</td>";
        }

        return $tds;
    }

    /**
     * @param $row
     * @param bool $isSunrise
     *
     * @return string
     */
    private function printJamahTime($row, $isSunrise=true)
    {
        $jamahTimes = $this->getJamahTime( $row );
        
        // Handle ishraq - merge into sunrise row (check FIRST)
        $ishraqMins = get_option('ishraq');
        if ($ishraqMins && $ishraqMins != '0' && $this->dptHelper->isIshraqTimeNext($row)) {
            $jamahTimes['sunrise'] = $this->dptHelper->getIshraqTime($row['sunrise']);
        } elseif (get_option('zawal') && $this->dptHelper->isZawalTimeNext($row)) {
            // Handle zawal - show zawal time instead of sunrise if zawal time is next
            $jamahTimes['sunrise'] = $this->dptHelper->getZawalTime($row['zuhr_begins']);
        }

        if (! $isSunrise) {
            unset( $jamahTimes['sunrise'] );
        }

        $tds = '';
        $nextPrayer =  $this->getNextPrayer( $row );
        
        // On Friday, only next prayer (not Zuhr) should be highlighted until Asr
        $onFriday = $this->todayIsFriday();
        
        foreach ($jamahTimes as $key => $azan) {
            $shouldHighlight = false;
            if ($key == 'sunrise' && $nextPrayer == 'ishraq') {
                $shouldHighlight = true;
            } elseif ($key != 'sunrise' && $nextPrayer == $key) {
                $shouldHighlight = true;
            }
            // On Friday, don't highlight Zuhr (Jumuah row is highlighted instead)
            if ($onFriday && $key == 'zuhr') {
                $shouldHighlight = false;
            }
            
            $class = $shouldHighlight ? 'class=highlight' : 'class=jamah';
            $tds .= "<td ".$class.">".$this->getFormattedDateForPrayer( $azan, $key, true )."</td>";
        }
        
        return $tds;
    }

    /**
     * @param $row
     * @param bool $isFullTable
     *
     * @return string
     */
    private function printVerticalTableTop($row, $isFullTable=false, $isAzanOnly=false)
    {
        $nextIqamah = '';
        if (! $row['hideTimeRemaining']) {
            $nextIqamah = $isAzanOnly == true ? '' : $this->getNextIqamahTime($row);
        }

        $colspan = ( $isFullTable == true ) ? 3 : 2;

        $colspanRamadan = $isFullTable == true ? "colspan='2'" : '';


        $ramadan = '';
        if ($this->isRamadan() && ! $row['hideRamadan']) {
            $ramadan = '
            <tr>
             <th class="highlight">' .$this->localHeaders['fast_begins']. '</th>
             <th '.$colspanRamadan.' class="highlight">' .$this->formatDateForPrayer($row['fajr_begins'], true). '</th>
            </tr>
            <tr>
             <th class="highlight">'.$this->localHeaders['fast_ends'].'</th>
             <th '.$colspanRamadan.' class="highlight">'.$this->formatDateForPrayer($row['maghrib_begins']).'</th>
            </tr>
            ';
        }
        $table = "";
        $announcement = '';
        if(isset($row['announcement']) && ! empty( $row['announcement'] )) {
            $announcement = "<tr><th colspan=".$colspan." style='text-align:center' class='notificationBackground notificationFont'>".$row['announcement']. "</th></tr>";
        }

        $date = date_i18n(get_option('date_format'));
        $hijriDate = $this->getHijriDate(date('d'), date('m'), date('Y'), $row);
        $title = trim((string) $row['widgetTitle']);
        $table .=
            '<div class="dpt-vertical-shell">
                <div class="dpt-horizontal-overview">
                    <div class="dpt-horizontal-date">
                        <span class="dpt-horizontal-kicker">' . esc_html($title !== '' ? $title : __('Prayer times', 'daily-prayer-time')) . '</span>
                        <span class="dpt-horizontal-date-main">' . esc_html($date) . '</span>'
                        . ($hijriDate ? '<span class="dpt-horizontal-hijri">' . esc_html($hijriDate) . '</span>' : '') . '
                    </div>'
                    . ($nextIqamah !== '' ? '<div class="dpt-horizontal-next">' . $nextIqamah . '</div>' : '') . '
                </div>
                <table class="dptTimetable dpt-vertical-table ' . $this->getTableClass() . ' customStyles dptUserStyles" aria-label="' . esc_attr__('Daily prayer times', 'daily-prayer-time') . '"> '
                . $announcement . $ramadan;

        return $table;
    }

    /**
     * @param $row
     * @param $display // i.e both, azan, iqamah
     *
     * @return string
     */
    private function printVerticalRow($row, $display)
    {
        $trs = '';
        $nextPrayer = $this->getNextPrayer( $row );
        $isFriday = $this->todayIsFriday();

        $localPrayerNames = $this->localPrayerNames;
        
        // Handle ishraq - ONLY if ishraq is NEXT (not yet passed) - check FIRST
        $ishraqMins = get_option('ishraq');
        if ($ishraqMins && $ishraqMins != '0' && isset($localPrayerNames['ishraq'])) {
            if ($this->dptHelper->isIshraqTimeNext($row)) {
                $localPrayerNames['sunrise'] = $localPrayerNames['ishraq'];
                $row['sunrise'] = $this->dptHelper->getIshraqTime($row['sunrise']);
            }
        }
        if (isset($localPrayerNames['ishraq'])) {
            unset($localPrayerNames['ishraq']);
        }
        
        // Handle zawal - show zawal instead of sunrise if zawal time is next (only if ishraq not next)
        if (!$this->dptHelper->isIshraqTimeNext($row) && get_option('zawal') && $this->dptHelper->isZawalTimeNext($row)) {
            $localPrayerNames['sunrise'] = $localPrayerNames['zawal'] ?? 'Zawal';
            $row['sunrise'] = $this->dptHelper->getZawalTime($row['zuhr_begins']);
        }
        
        // Remove zawal - never show as separate prayer name
        if (isset($localPrayerNames['zawal'])) {
            unset($localPrayerNames['zawal']);
        }

        foreach ($localPrayerNames as $key=>$prayerName) {
            $begins =  $key != 'sunrise' ? lcfirst( $key ).'_begins' : 'sunrise';
            $jamah =  $key != 'sunrise' ? lcfirst( $key ).'_jamah' : 'sunrise';

            $shouldHighlight = false;
            if ($key == 'sunrise' && $nextPrayer == 'ishraq') {
                $shouldHighlight = true;
            } elseif ($key != 'sunrise' && $nextPrayer == $key) {
                $shouldHighlight = true;
            }
            // On Friday, don't highlight Zuhr (Jumuah row is highlighted instead)
            if ($isFriday && $key == 'zuhr') {
                $shouldHighlight = false;
            }
            $class = $shouldHighlight ? 'highlight' : '';
            $highlightForJamah = $shouldHighlight ? 'highlight' : '';

            $trs .= '<tr class="dpt-vertical-prayer-row">
                    <th class="prayerName ' .$class.'" scope="row">' . $prayerName . '</th>';
            if ( ($key == 'sunrise') && $display == 'both') {
                $trs .= '<td colspan="2" class="' . $class . '">'.$this->getFormattedDateForPrayer($row[$jamah], $key).'</td></tr>';
            } elseif ($display == 'azan') {
                $trs .='<td class="begins '.$class.'">'.$this->getFormattedDateForPrayer($row[$begins], $key).'</td>
                </tr>';
            } elseif ($display == 'iqamah') {
                $trs .='<td class="begins '.$class.'">'.$this->getFormattedDateForPrayer($row[$jamah], $key, true).'</td>
                </tr>';
            } else {
                $trs .='<td class="begins '.$class.'">'.$this->getFormattedDateForPrayer($row[$begins], $key).'</td>
                    <td class="jamah '.$highlightForJamah.'">'.$this->getFormattedDateForPrayer($row[$jamah], $key, true).'</td>
                </tr>';
            }
        }

        // Determine whether to show Jumuah row: only when jumuah times are configured
        // and current time is before the last configured Jumuah time (on Fridays).
        $jumuahOptions = array_filter([ get_option('jumuah1'), get_option('jumuah2'), get_option('jumuah3') ]);
        $showJumuah = !empty($row['showJumuah']);
        if (! $showJumuah && ! empty($jumuahOptions) && $isFriday) {
            $nowTs = strtotime( user_current_time('H:i') );
            $lastJumuahTs = max( array_map('strtotime', $jumuahOptions) );
            if ($nowTs < $lastJumuahTs) {
                $showJumuah = true;
            }
        }

        $shouldHighlightJumuah = ($nextPrayer == 'jumuah') || ($isFriday && $showJumuah);
        if ($shouldHighlightJumuah || $showJumuah) {
            $jumuahClass = $shouldHighlightJumuah ? 'highlight' : '';
            $jumuahColspan = $display == 'both' ? 2 : 1;
            $trs .= '<tr>
                            <th class="prayerName ' . $jumuahClass . '" scope="row"><span>' . stripslashes($this->getLocalHeaders()['jumuah']) . '</span></th>
                            <td colspan="' . $jumuahColspan . '" class="jamah ' . $jumuahClass . '">' . $this->getJumuahTimesArray() . '</td>
                        </tr>';
        }

        return $trs;
    }

    private function getFormattedDateForPrayer($time, $prayerName, $isJamatTime=false)
    {
        return $this->formatDateForPrayer($time);
    }

    public function displayNextPrayer($row)
    {
        return $this->getNextIqamahTime($row);

    }
}
