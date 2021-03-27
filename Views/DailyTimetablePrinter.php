<?php
require_once( 'TimetablePrinter.php' );

class DailyTimetablePrinter extends TimetablePrinter
{

    public function __construct()
    {
        parent::__construct();
        $this->localPrayerNames = $this->getLocalPrayerNames(false, true);
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
            <tr>
                <th class="tableHeading prayerName">' .$this->localHeaders['prayer']. '</th>'
                  . $this->printTableHeading($row) .
            '</tr>
            <tr>
                <th class="tableHeading">' .$this->localHeaders['begins']. '</th>'
                  .$this->printAzanTime($row).
            '</tr>
            <tr><th class="tableHeading">' .$this->localHeaders['iqamah']. '</th>'
                  .$this->printJamahTime($row, false).
            '</tr>';

        if ( get_option('jumuah') && ! $this->todayIsFriday() ) {
            $table .= '<tr>
                            <th class="tableHeading">' . stripslashes($this->getLocalHeaders()['jumuah']) . '</th>
                            <td colspan="6" class="jamah">' . get_option('jumuah') . '</td>
                        </tr>';
        }

        $table .= '</table>';

        return $table;
    }

    public function horizontalTimeJamahOnly($row)
    {
        $table = $this->printHorizontalTableTop( $row );

        $table .= '
            <tr><th>' .$this->localHeaders['prayer']. '</th>'
                  . $this->printTableHeading($row) .
            '</tr>
            <tr>
                <th>'.$this->localHeaders['iqamah'].'</th>'
                  .$this->printJamahTime($row).
            '</tr>
        </table>';

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
            <tr><th>' .$this->localHeaders['prayer']. '</th>'
                    . $this->printTableHeading($row) .
            '</tr>
            <tr>
                <th>'.$this->localHeaders['begins'].'</th>'
                    .$this->printAzanTime($row).
            '</tr>
        </table>';

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
            '<tr>
                <th class="tableHeading">' .$this->localHeaders['prayer']. '</th>
                <th class="tableHeading">' .$this->localHeaders['begins']. '</th>
                <th class="tableHeading">' .$this->localHeaders['iqamah']. '</th>
            </tr>'
            . $this->printVerticalRow( $row, 'both' ) .
            '</table>';

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
            '<tr>
                <th class="tableHeading">' .$this->localHeaders['prayer']. '</th>
                <th class="tableHeading">' .$this->localHeaders['iqamah']. '</th>
            </tr>'
            .$this->printVerticalRow( $row, 'iqamah' ) .
            '</table>';

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
            '<tr>
                <th class="tableHeading">' .$this->localHeaders['prayer']. '</th>
                <th class="tableHeading">' .$this->localHeaders['begins']. '</th>
            </tr>'
            .$this->printVerticalRow( $row, 'azan' ) .
            '</table>';

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
        if (! $row['hideTimeRemaining']) {
            $nextIqamah = $isAzanOnly == true ? '' : $this->getNextIqamahTime($row);
        }
        $colspan = 7;
        $ramadanTds = '<td></td>';

        if (get_option('ramadan-chbox') && ! $row['hideRamadan']) {
            $ramadan = '
                <tr class="">
                    <td colspan="3" class="highlight">'. $this->localHeaders['fast_begins'].': '.$this->formatDateForPrayer($row['fajr_begins'], true).'</td>
                    '. $ramadanTds . '
                    <td colspan="3" class="highlight">'. $this->localHeaders['fast_ends'].': '.$this->formatDateForPrayer($row['maghrib_begins']).'</td>
                </tr>';
        }

        if(isset($row['announcement']) && ! empty( $row['announcement'] )) {
            $announcement = "<tr><th colspan='".$colspan."' style='text-align:center' class='notificationBackground'>".$row['announcement']. "</th></tr>";
        }

        $table = "";
        $table .=
            '<table class="customStyles dptUserStyles dptTimetable ' .$this->getTableClass().'"> '.$announcement.'
            <tr>
             <th colspan="'. $colspan .'" style="text-align:center">'
                .$row['widgetTitle']. ' ' . date_i18n( get_option( 'date_format' ) ) .' '. $this->getHijriDate(date("d"), date("m"), date("Y"),$row) .'  ' . $nextIqamah .'
             </th>
            </tr>'. $ramadan;

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

        foreach ($this->localPrayerNames as $key=>$prayerName) {
            $class = $nextPrayer == $key ? 'highlight' : '';
            $ths .= "<th class='tableHeading prayerName" . $this->tableClass . " ". $class."'>".$prayerName."</th>";
        }

        return $ths;
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

        foreach ($azanTimings as $key => $azan) {

            $class = $nextPrayer == $key ? 'class=highlight' : '';
            $rowspan = '';
            if ($key == 'sunrise')
            {
                $rowspan = "rowspan='2'";
            }
            $tds .= "<td ". $rowspan ." ".$class.">".$this->getFormattedDateForPrayer( $azan, $key )."</th>";
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
        if (! $isSunrise) {
            unset( $jamahTimes['sunrise'] );
        }

        $tds = '';
        $nextPrayer =  $this->getNextPrayer( $row );
        foreach ($jamahTimes as $key => $azan) {
            $class = $nextPrayer == $key ? 'class=highlight' : 'class=jamah';
            $tds .= "<td ".$class.">".$this->getFormattedDateForPrayer( $azan, $key, true )."</th>";
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
        if (! $row['hideTimeRemaining']) {
            $nextIqamah = $isAzanOnly == true ? '' : $this->getNextIqamahTime($row);
        }

        $colspan = ( $isFullTable == true ) ? 3 : 2;

        $colspanRamadan = $isFullTable == true ? "colspan='2'" : '';


        if (get_option('ramadan-chbox') && ! $row['hideRamadan']) {
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
        if(isset($row['announcement']) && ! empty( $row['announcement'] )) {
            $announcement = "<tr><th colspan=".$colspan." style='text-align:center' class='notificationBackground'>".$row['announcement']. "</th></tr>";
        }

        $table .=
            '<table class="dptTimetable ' .$this->getTableClass().' customStyles dptUserStyles"> '.$announcement.'
            <tr>
             <th colspan='.$colspan.' style="text-align:center">'
                .$row['widgetTitle']. ' '. date_i18n( get_option( 'date_format' ) ) .' '. $this->getHijriDate(date("d"), date("m"), date("Y"), $row).'' . $nextIqamah . '
             </th>
            </tr>'
            .$ramadan;

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

        foreach ($this->localPrayerNames as $key=>$prayerName) {
            $begins =  $key != 'sunrise' ? lcfirst( $key ).'_begins' : 'sunrise';
            $jamah =  $key != 'sunrise' ? lcfirst( $key ).'_jamah' : 'sunrise';

            $class = $nextPrayer == $key ? 'highlight' : '';
            $highlightForJamah = $nextPrayer == $key ? 'highlight' : '';

            $trs .= '<tr>
                    <th class="prayerName ' .$class.'">' . $prayerName . '</th>';
            if ( ($key == 'sunrise') && $display == 'both') {
                $trs .= '<td colspan="2" class="' . $class . '">'.$this->getFormattedDateForPrayer($row[$jamah], $key).'</td>';
            } elseif ($display == 'azan') {
                $trs .='<td class="begins '.$class.'">'.$this->getFormattedDateForPrayer($row[$begins], $key).'</td>
                </tr>';
            } elseif ($display == 'iqamah') {
                $trs .='<td class="begins '.$class.'">'.$this->getFormattedDateForPrayer($row[$jamah], $key).'</td>
                </tr>';
            } else {
                $trs .='<td class="begins '.$class.'">'.$this->getFormattedDateForPrayer($row[$begins], $key).'</td>
                    <td class="jamah '.$highlightForJamah.'">'.$this->getFormattedDateForPrayer($row[$jamah], $key, true).'</td>
                </tr>';
            }
        }
        if ( get_option('jumuah') && ! $this->todayIsFriday() ) {
            $trs .= '<tr>
                            <th class="prayerName"><span>' . stripslashes($this->getLocalHeaders()['jumuah']) . '</span></th>
                            <td colspan="2" class="jamah">' . get_option('jumuah') . '</td>
                        </tr>';
        }

        return $trs;
    }

    private function getFormattedDateForPrayer($time, $prayerName, $isJamatTime=false)
    {
        $jumuahTime = get_option('jumuah');
        if ( ($prayerName === 'zuhr' && $this->todayIsFriday()) && $isJamatTime && $jumuahTime) {
            return $jumuahTime;
        }
        return $this->formatDateForPrayer($time);
    }

    public function displayNextPrayer($row)
    {
        return $this->getNextIqamahTime($row);

    }
}
