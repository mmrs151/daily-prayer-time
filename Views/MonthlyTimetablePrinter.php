<?php
require_once( 'TimetablePrinter.php' );

class MonthlyTimetablePrinter extends TimetablePrinter
{
    /**
     * @param $rows
     * @param $options
     *
     * @return string
     */
    public function displayTable($rows, $options)
    {
        $table = $this->printFullTableTop($options);

        $table .= $this->printFullTableRow( $rows, $options, 'both' );

        return $table;
    }

    /**
     * @param $options
     *
     * @return string
     */
    private function printFullTableTop($options)
    {
        if ($options['isRamadan']) {
            $fajrBegins = $this->localHeaders['begins'];
            $maghribBegins = $this->localHeaders['fast_ends'];
            $fajrColspan = 4;
        } else {
            $fajrBegins = $this->localHeaders['begins'];
            $maghribBegins = $this->localHeaders['begins'];
            $fajrColspan = 3;
        }

        $fastingClass = $this->getFastingClass( $options );
        $prayers = $this->getLocalPrayerNames(true);

        $table = "
        <table class='dptTimetable customStyles dptUserStyles'>
         <thead class='prayerName'>
             <th class='prayerName'></th>
             <th class='prayerName'></th>

             <th class='prayerName' colspan='$fajrColspan'>" .$prayers['fajr']. "</th>
             <th class='prayerName' colspan='2'>" .$prayers['zuhr']. "</th>";
        $table .= "<th class='prayerName' colspan=".$this->getAsrMethodColspan().">" .$prayers['asr']. "</th>";
        $table .= "
             <th class='prayerName' colspan='2'>" .$prayers['maghrib']. "</th>
             <th class='prayerName' colspan='2'>" .$prayers['isha']. "</th>
         </thead>
         <thead>
            <th class='tableHeading'>".$this->localTimes['date']."</th>
            <th class='tableHeading'>".$this->localTimes['day']."</th>
            ";

        if ($options['isRamadan']) {
            $table .="<th class='tableHeading ". $fastingClass ."'>" . $this->localHeaders['fast_begins'] . "</th>";
        }

            $table.="
            <th class='tableHeading ". $fastingClass ."'>" . $fajrBegins . "</th>
            <th class='tableHeading'>" .$this->localHeaders['iqamah']. "</th>
            <th class='tableHeading'>" .$prayers['sunrise']. "</th>

            <th class='tableHeading'>" .$this->localHeaders['begins']. "</th>
            <th class='tableHeading'>" .$this->localHeaders['iqamah']. "</th>";

        $table .= $this->getAsrMethodTh();

        $table .= "<th class='tableHeading'>" .$this->localHeaders['iqamah']. "</th>

            <th class='tableHeading ". $fastingClass ."'>" . $maghribBegins . "</th>
            <th class='tableHeading'>" .$this->localHeaders['iqamah']. "</th>

            <th class='tableHeading'>" .$this->localHeaders['begins']. "</th>
            <th class='tableHeading'>" .$this->localHeaders['iqamah']. "</th>
        </thead>";

        return $table;
    }


    /**
     * @param array $rows
     * @param $options
     * @param string $display
     *
     * @return string
     */
    private function printFullTableRow(array $rows, $options, $display='both')
    {
        $table = '';
        $asrMethod = get_option('asrSelect');
        $classFasting = $this->getClassFasting( $options );

        foreach($rows as $day) {
            $day['asr_begins'] = ($asrMethod == 'hanafi') ? $day['asr_mithl_2'] : $day['asr_mithl_1'];

            $today = explode('-', $day['d_date']);
            $weekday = date("D", strtotime($day['d_date']));

            $table .= "
             <tr " . $this->getClass($today[1], $today[2]) . ">
                <td>" . date_i18n(get_option( 'date_format' ), strtotime($day['d_date'])). ' '. $this->getHijriDate($today[2], $today[1], $today[0], $day, true) ."</td>
                <td class=" . $weekday . ">" . $weekday . "</td>
";
            if ($options['isRamadan']) {
                $table .="<td ". $classFasting .">" . $this->formatDateForPrayer($day['fajr_begins'], true). "</td>";
            }
                $table .= "
                <td ". $classFasting .">" . $this->formatDateForPrayer($day['fajr_begins']). "</td>
                <td class='jamah'>" . $this->formatDateForPrayer($day['fajr_jamah']). "</td>
                <td>" . $this->formatDateForPrayer($day['sunrise']). "</td>

                <td>" . $this->formatDateForPrayer($day['zuhr_begins']). "</td>
                <td class='jamah'>" . $this->formatDateForPrayer($day['zuhr_jamah']). "</td>";

            if ($asrMethod != 'both') {
                $table .= "<td>" . $this->formatDateForPrayer($day['asr_begins']). "</td>";
            } else {
                $table .= "<td>" . $this->formatDateForPrayer($day['asr_mithl_1']). "</td>
                    <td>" . $this->formatDateForPrayer($day['asr_mithl_2']). "</td>";
            }
            $table .= "
                <td class='jamah'>" . $this->formatDateForPrayer($day['asr_jamah']). "</td>

                <td ". $classFasting .">" . $this->formatDateForPrayer($day['maghrib_begins']). "</td>
                <td class='jamah'>" . $this->formatDateForPrayer($day['maghrib_jamah']). "</td>

                <td>" . $this->formatDateForPrayer($day['isha_begins']). "</td>
                <td class='jamah'>" . $this->formatDateForPrayer($day['isha_jamah']). "</td>
                </tr>";
        }

        return $table;
    }

    /**
     * @param $rows
     * @param $options
     *
     * @return string
     */
    public function displayTableJamahOnly($rows, $options)
    {
        $table = $this->printTableTop( $options );

        $table .= $this->printTableRow( $rows, $options );

        return $table;
    }

    /**
     * @param $rows
     * @param $options
     *
     * @return string
     */
    public function displayTableAzanOnly($rows, $options)
    {
        $table = $this->printTableTop( $options, true );
        $asrMethod = get_option('asrSelect');

        foreach($rows as $day) {
            $day['asr_begins'] = ($asrMethod == 'hanafi') ? $day['asr_mithl_2'] : $day['asr_mithl_1'];

            $today = explode('-', $day['d_date']);
            $weekday = date("D", strtotime($day['d_date']));

            $table .= "
             <tr " . $this->getClass($today[1], $today[2]) . ">
                <td>" . date_i18n(get_option( 'date_format' ), strtotime($day['d_date'])). ' '. $this->getHijriDate($today[2], $today[1], $today[0], $day, true) ."</td>

                <td class=" . $weekday . ">" . $weekday . "</td>"
                      . $this->getFastingTdWithData($options['isRamadan'], $day['fajr_begins'], true, true )."
                <td>" . $this->formatDateForPrayer($day['sunrise']). "</td>
                <td>" . $this->formatDateForPrayer($day['zuhr_begins']). "</td>
                <td>" . $this->formatDateForPrayer($day['asr_begins']). "</td>"
                      . $this->getFastingTdWithData($options['isRamadan'], $day['maghrib_begins'], true )."
                <td>" . $this->formatDateForPrayer($day['isha_begins']). "</td>
                </tr>";
        }

        return $table;
    }

    /**
     * @param $options
     * @param bool $isAzanOnly
     *
     * @return string
     */
    private function printTableTop($options, $isAzanOnly=false)
    {
        $fajr = '';
        $maghrib = '';
        $sunrise = '';
        $fajrJamah = '';
        $maghribJamah = '';
        $azanOnlyClass = '';

        $prayers = $this->getLocalPrayerNames(true);
        if ($isAzanOnly) {
            $fajr = $prayers['fajr'];
            $maghrib = $prayers['maghrib'];
            $sunrise =  "<th class='tableHeading'>" .$prayers['sunrise']. "</th>";
            $azanOnlyClass = 'azanOnly';
        } else {
            $fajrJamah = "<th class='tableHeading'>" .$prayers['fajr']. "</th>";
            $maghribJamah = "<th class='tableHeading'>" .$prayers['maghrib']. "</th>";
        }

        if ($options['isRamadan'] && !$isAzanOnly) {
            $fajr = '';
            $maghrib = '';
            $fajrHeading = "<th class='tableHeading fasting ".$azanOnlyClass."'>" .$this->localHeaders['fast_begins']. $fajr . "</th>";
            $maghribHeading = "<th class='tableHeading fasting ".$azanOnlyClass."'>" .$this->localHeaders['fast_ends']. $maghrib . "</th>";
        } elseif ($options['isRamadan'] && $isAzanOnly) {
            $fajrHeading = "<th class='tableHeading fasting ".$azanOnlyClass."'>" .$this->localHeaders['fast_begins'] . "</th>";
            $fajrHeading .= "<th class='tableHeading ".$azanOnlyClass."'>"  . $fajr . "</th>";
            $maghribHeading = "<th class='tableHeading fasting ".$azanOnlyClass."'>" .$this->localHeaders['fast_ends']. '/'. $maghrib . "</th>";
        } elseif ($isAzanOnly) {
            $fajrHeading = "<th class='tableHeading'>" . $fajr . "</th>";
            $maghribHeading = "<th class='tableHeading'>" . $maghrib . "</th>";;
        }

        $table = "
        <table class='dptTimetable customStyles'>
         <tr>
            <th class='tableHeading'>".$this->localTimes['date']."</th>
            <th class='tableHeading'>".$this->localTimes['day']."</th>"
                 . $fajrHeading .""
                 .$fajrJamah.""
                 .$sunrise.
             "<th class='tableHeading'>" .$prayers['zuhr']. "</th>
             <th class='tableHeading'>" .$prayers['asr']. "</th>"
                 . $maghribHeading . ""
                 .$maghribJamah ."".
             "<th class='tableHeading'>" .$prayers['isha']. "</th>
         </tr>";

        return $table;
    }

    /**
     * @param array $rows
     * @param $options
     *
     * @return string
     */
    private function printTableRow(array $rows, $options)
    {
        $table = '';

        foreach($rows as $day) {

            $today = explode('-', $day['d_date']);
            $weekday = date("D", strtotime($day['d_date']));

            $table .= "
             <tr " . $this->getClass($today[1], $today[2]) . ">
                <td>" . date_i18n(get_option( 'date_format' ), strtotime($day['d_date'])). ' '. $this->getHijriDate($today[2], $today[1], $today[0], $day, true) ."</td>

                <td class=" . $weekday . ">" . $weekday . "</td>"
                      . $this->getFastingTdWithData($options['isRamadan'], $day['fajr_begins'], false, true ) . "
                <td>" . $this->formatDateForPrayer($day['fajr_jamah']). "</td>
                <td>" . $this->formatDateForPrayer($day['zuhr_jamah']). "</td>
                <td>" . $this->formatDateForPrayer($day['asr_jamah']). "</td>"
                      . $this->getFastingTdWithData($options['isRamadan'], $day['maghrib_begins'] ) . "
                <td>" . $this->formatDateForPrayer($day['maghrib_jamah']). "</td>
                <td>" . $this->formatDateForPrayer($day['isha_jamah']). "</td>
                </tr>";
        }

        return $table;
    }

    /**
     * @param $options
     *
     * @return string
     */
    private function getFastingClass($options)
    {
        if ( $options['isRamadan'] ) {
            return 'fasting';
        }
        return '';
    }

    /**
     * @param $options
     *
     * @return string
     */
    private function getClassFasting($options)
    {
        if ( $options['isRamadan'] ) {
            return "class='fasting'";
        }
        return '';
    }

    /**
     * @return int
     */
    private function getAsrMethodColspan()
    {
        $asrMethod = get_option('asrSelect');
        if ($asrMethod != 'both') {
            return 2;
        }
        return 3;
    }

    /**
     * @return string
     */
    private function getAsrMethodTh()
    {
        $asrMethod = get_option('asrSelect');

        if ($asrMethod != 'both') {
            return "<th class='tableHeading'>" .$this->localHeaders['begins']. "</th>";
        }
        return "<th class='tableHeading'>" .$this->localHeaders['standard']. "</th><th class='tableHeading'>" .$this->localHeaders['hanafi']. "</th>";
    }
}
