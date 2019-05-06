<?php
require_once( 'TimetablePrinter.php' );

class DailyTimetablePrinter extends TimetablePrinter
{

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
                <th class="tableHeading">' .$this->localHeaders['prayer']. '</th>'
                  . $this->printTableHeading($row) .
            '</tr>
            <tr>
                <th class="tableHeading">' .$this->localHeaders['begins']. '</th>'
                  .$this->printAzanTime($row).
            '</tr>
            <tr><th class="tableHeading">' .$this->localHeaders['iqamah']. '</th>'
                  .$this->printJamahTime($row, false).
            '</tr>
        </table>';

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
            $nextIqamah = $isAzanOnly == true ? '' : $this->getNextIqamahTime( $row );
        }

        if (get_option('ramadan-chbox') && ! $row['hideRamadan']) {
            $ramadan = '
                <tr class="">
                    <td colspan="3" class="highlight">'. $this->localHeaders['fast_begins'].': '.$this->formatDateForPrayer($row['fajr_begins'], true).'</td>
                    <td></td>
                    <td colspan="3" class="highlight">'. $this->localHeaders['fast_ends'].': '.$this->formatDateForPrayer($row['maghrib_begins']).'</td>
                </tr>';
        }

        if(isset($row['announcement']) && ! empty( $row['announcement'] )) {
            $announcement = "<tr><th colspan='7' style='text-align:center' class='notificationBackground'>".$row['announcement']. "</th></tr>";
        }
        $table = "";
        $table .=
            '<table class="customStyles dptUserStyles dptTimetable ' .$this->getTableClass().'"> '.$announcement.'
            <tr>
             <th colspan="7" style="text-align:center">'
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
            $ths .= "<th class='tableHeading" . $this->tableClass . " ". $class."'>".$prayerName."</th>";
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
        $nextPrayer = ucfirst( $this->getNextPrayer( $row ) );
        foreach ($this->getAzanTime( $row ) as $key => $azan) {
            $class = $nextPrayer == $key ? 'class=highlight' : '';
            $rowspan = ucfirst($key) == 'Sunrise' ? "rowspan='2'" : '';
            $tds .= "<td ". $rowspan ." ".$class.">".$this->formatDateForPrayer( $azan )."</th>";
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
            unset( $jamahTimes['Sunrise'] );
        }
        $tds = '';
        $nextPrayer = ucfirst( $this->getNextPrayer( $row ) );
        foreach ($jamahTimes as $key => $azan) {
            $class = $nextPrayer == $key ? 'class=highlight' : 'class=jamah';
            $tds .= "<td ".$class.">".$this->formatDateForPrayer( $azan )."</th>";
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
            if ($key == 'sunrise' && $display == 'both') {
                $trs .= '<td colspan="2" ' . $class . '>'.$this->formatDateForPrayer($row[$jamah]).'</td>';
            } elseif ($display == 'azan') {
                $trs .='<td class="begins '.$class.'">'.$this->formatDateForPrayer($row[$begins]).'</td>
                </tr>';
            } elseif ($display == 'iqamah') {
                $trs .='<td class="begins '.$class.'">'.$this->formatDateForPrayer($row[$jamah]).'</td>
                </tr>';
            } else {
                $trs .='<td class="begins '.$class.'">'.$this->formatDateForPrayer($row[$begins]).'</td>
                    <td class="jamah '.$highlightForJamah.'">'.$this->formatDateForPrayer($row[$jamah]).'</td>
                </tr>';
            }
        }

        return $trs;
    }

    public function displayNextPrayer($row, $nextFajr)
    {
        $nextPrayer = $this->getNextPrayer( $row );
        $nextIqamah = $this->getNextIqamahTimeDiff($row);
        $key = ($nextPrayer == 'sunrise') ? $nextPrayer : strtolower($nextPrayer.'_jamah');
        $iqamah = ($nextPrayer == 'sunrise') ? '' : $this->localHeaders['iqamah'];
        $nextPrayerName = $row[$key];

        if ( is_null($nextPrayer) ) {
            $nextPrayerName = $nextFajr;
        }
        if ($nextIqamah) {
            $timeLeftText = $this->getLocalizedNumber( $nextIqamah ) .':00';
            $minLeftText = '<span class="timeLeft '.$this->getIqamahClass( $nextIqamah ).'">'. $this->localTimes["minute"] .'</span>';
            if ($nextIqamah > 60) {
                $hours = $nextIqamah / 60;
                $hours = (int)$hours;
                $mins = $nextIqamah % 60;
                $mins = (int)$mins;
                $timeLeftText = $this->getLocalizedNumber( $hours ) .' '.$this->localTimes["hours"] .' '. $this->getLocalizedNumber( $mins );
            }
        }

        $nextPrayerString = "";

        $nextPrayerString .=' 
        <div class="scNextPrayer">
            <span class="dptScNextPrayer">
                '. $this->getHeading($row, $nextPrayer, $iqamah) .'
                <h2 class="dptScTime">
                '. $this->getNextPrayerTime($nextPrayerName, $row)
                .'</h2>
            </span>
            ';
        if (! $this->isJumahDisplay($row)) {
            $nextPrayerString .= '<span class="green">
                <span class="timeLeftCountDown timeLeft '.$this->getIqamahClass( $nextIqamah ).'">'.  $timeLeftText .' </span>
                ' . $minLeftText . '
            </span>
        </div>';
        }

        return $nextPrayerString;
    }

    private function getHeading($dbRow, $nextPrayer, $iqamah)
    {
        if ( is_null($nextPrayer)) {
            return $this->localPrayerNames['fajr'].' '. $iqamah;
        }
        if ( $this->isJumahDisplay($dbRow) ) {
            return $this->localPrayerNames['zuhr'] = $this->getLocalHeaders()['jumuah'];
        }

        return $this->localPrayerNames[$nextPrayer] .' '. $iqamah;
    }

    private function getNextPrayerTime($nextPrayerName, $dbRow)
    {
        if ( $this->isJumahDisplay($dbRow) ) {
            return get_option('jumuah');
        }
        return $this->formatDateForPrayer($nextPrayerName);
    }

    public function isJumahDisplay($dbRow)
    {
        if ( date('D') == 'Fri' &&
            current_time('timestamp') > strtotime($dbRow['sunrise']) &&
            current_time('timestamp') <= strtotime($dbRow['zuhr_jamah']) + 60
        ) {
            return true;
        }

        return false;
    }

}
