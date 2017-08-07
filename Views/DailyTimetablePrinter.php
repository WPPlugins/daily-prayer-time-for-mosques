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
    public function horizontalTime($row)
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
    public function verticalTime($row)
    {
        $table = $this->printVerticalTableTop( $row , true);

        $table .=
            '<tr>
                <th class="tableHeading">' .$this->localHeaders['prayer']. '</th>
                <th class="tableHeading">' .$this->localHeaders['begins']. '</th>
                <th class="tableHeading">' .$this->localHeaders['iqamah']. '</th>
            </tr>'
            .$this->printVerticalRow( $row, 'both' ).
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
                    <td colspan="3" class="highlight">'. $this->localHeaders['fast_begins'].': '.$this->formatDateForPrayer($row['fajr_begins']).'</td>
                    <td></td>
                    <td colspan="3" class="highlight">'. $this->localHeaders['fast_ends'].': '.$this->formatDateForPrayer($row['maghrib_begins']).'</td>
                </tr>';
        }

        if(isset($row['announcement']) && ! empty( $row['announcement'] )) {
            $announcement = "<tr><th colspan='7' style='text-align:center' class='notificationBackground'>".$row['announcement']. "</th></tr>";
        }
        $table = "";
        $table .=
            '<table class="customStyles dptTimetable ' .$this->getTableClass().'"> '.$announcement.'
            <tr>
             <th colspan="7" style="text-align:center">'
                .$row['widgetTitle']. ' ' . date_i18n( get_option( 'date_format' ) ) .' '. $this->getHijriDate($row) .'  ' . $nextIqamah .'
             </th>
            </tr>'. $ramadan;

        return $table;
    }

    public function displayRamadanTime($row)
    {
      return '  <table class="customStyles">
                <tr style="text-align:center">
                    <td colspan="3" class="fasting highlight">'. $this->localHeaders['fast_begins'].': '.$this->formatDateForPrayer($row['fajr_begins']).'</td>
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
             <th '.$colspanRamadan.' class="highlight">' .$this->formatDateForPrayer($row['fajr_begins']). '</th>
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
            '<table class="dptTimetable ' .$this->getTableClass().' customStyles"> '.$announcement.'
            <tr>
             <th colspan='.$colspan.' style="text-align:center">'
                .$row['widgetTitle']. ' '. date_i18n( get_option( 'date_format' ) ) .' '. $this->getHijriDate($row).'' . $nextIqamah . '
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

            $class = $nextPrayer == $key ? 'class="highlight"' : '';
            $highlightForJamah = $nextPrayer == $key ? 'highlight' : '';

            $trs .= '<tr>
                    <th ' .$class.'>' . $prayerName . '</th>';
            if ($key == 'sunrise' && $display == 'both') {
                $trs .= '<td colspan="2" ' . $class . '>'.$this->formatDateForPrayer($row[$jamah]).'</td>';
            } elseif ($display == 'azan') {
                $trs .='<td '.$class.'>'.$this->formatDateForPrayer($row[$begins]).'</td>
                </tr>';
            } elseif ($display == 'iqamah') {
                $trs .='<td '.$class.'>'.$this->formatDateForPrayer($row[$jamah]).'</td>
                </tr>';
            } else {
                $trs .='<td '.$class.'>'.$this->formatDateForPrayer($row[$begins]).'</td>
                    <td class="jamah '.$highlightForJamah.'">'.$this->formatDateForPrayer($row[$jamah]).'</td>
                </tr>';
            }
        }

        return $trs;
    }

    public function displayNextPrayer($row)
    {
        $nextPrayer = $this->getNextPrayer( $row );
        $nextIqamah = $this->getNextIqamahTimeDiff($row);
        $key = ($nextPrayer == 'sunrise') ? $nextPrayer : strtolower($nextPrayer.'_jamah');
        $iqamah = ($nextPrayer == 'sunrise') ? '' : $this->localHeaders['iqamah'];

        $timeLeftText = $this->getLocalizedNumber( $nextIqamah ) .' '.$this->localTimes["minute"];

        if ($nextIqamah > 60) {
            $hours = $nextIqamah / 60;
            $hours = (int)$hours;
            $mins = $nextIqamah % 60;
            $mins = (int)$mins;
            $timeLeftText = $this->getLocalizedNumber( $hours ) .' '.$this->localTimes["hours"] .' '. $this->getLocalizedNumber( $mins ) .' '.$this->localTimes["minute"];
        }

        if ($nextPrayer) {
        echo $this->getHijriDate($row);
          echo '
              <div class="scNextPrayer">
                  <span class="dptScNextPrayer">
                      '. $this->localPrayerNames[$nextPrayer] .' '. $iqamah
                  .'
                  <h2 class="dptScTime">
                      '. $this->formatDateForPrayer($row[$key])
                  .'</h2></span>
                  <span class="green">
                      <span class="timeLeft '.$this->getIqamahClass( $nextIqamah ).'">'.  $timeLeftText .'</span>
                  </span>
              </div>';
        }

    }

    /**
     * @param $row
     *
     * @return string
     */
    private function getHijriDate($row)
    {
        $hijriDate = $row['displayHijriDate'] == true
            ? '<span class="hijriDate">('. $this->hijriDate->getDate(date("d"), date("m"), date("Y"), true) .')</span>'
            : '';

        return $hijriDate;
    }
}
