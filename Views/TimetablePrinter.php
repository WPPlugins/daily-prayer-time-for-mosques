<?php

require_once(__DIR__ . '/../Models/HijriDate.php');

class TimetablePrinter
{
    protected $prayerLocal = array(
        "fajr" => "Fajr",
        "sunrise" => "Sunrise",
        "zuhr" => "Zuhr",
        "asr" => "Asr",
        "maghrib" => "Maghrib",
        "isha" => "Isha"
    );

    protected $headersLocal = array(
        "prayer" => "Prayer",
        "begins" => "Begins",
        "iqamah" => "Iqamah",
        "standard" => "Standard",
        "hanafi" => "Hanafi",
        "fast_begins" => "Fast Begins",
        "fast_ends" => "Fast Ends"
    );

    protected $numbersLocal = array(
        0 => '0',
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
        9 => '9'
    );

    protected $monthsLocal = array(
        'january' => 'January',
        'february' => 'February',
        'march' => 'March',
        'april' => 'April',
        'may' => 'May',
        'june' => 'June',
        'july' => 'July',
        'august' => 'August',
        'september' => 'September',
        'october' => 'October',
        'november' => 'November',
        'december' => 'December'
    );


    /** @var array  */
    protected $timesLocal = array(
        'date' => 'Date',
        'day' => 'Day',
        'minute' => 'Minutes',
        'hours' => 'Hours'
    );

    /** @var string */
    protected $tableClass;

    /** @var array  */
    protected $localPrayerNames;

    /** @var array  */
    protected $localHeaders;

    /** @var array  */
    protected $localNumbers;

    /** @var  array */
    protected $localTimes;

    /** @var  HijriDate */
    protected $hijriDate;

    /** @var string  */
    protected $hijridateString;

    /**
     * TimetablePrinter constructor.
     */
    public function __construct()
    {
        $this->localPrayerNames = $this->getLocalPrayerNames();
        $this->localHeaders = $this->getLocalHeaders();
        $this->localNumbers = $this->getLocalNumbers();
        $this->localTimes = $this->getLocalTimes();

        $this->hijriDate = new HijriDate();
        $this->hijridateString = $this->hijriDate->getDate(date("d"), date("m"), date("Y"), true);
    }

    public function getTableClass()
    {
        return get_option('hideTableBorder');
    }

    public function getLocalPrayerNames()
    {
        $prayers_local = get_option('prayersLocal');

        return empty($prayers_local) ? $this->prayerLocal : $prayers_local;
    }

    public function getLocalHeaders()
    {
        $headers_local = get_option('headersLocal');

        return empty($headers_local) ? $this->headersLocal : $headers_local;
    }

    public function getLocalMonths()
    {
        $monthsLocal = get_option('monthsLocal');

        if ( empty( $monthsLocal )) {
            $monthsLocal = $this->monthsLocal;
        }

        if ( get_option("ramadan-chbox") ) {
            $monthsLocal['ramadan'] = 'Ramadan';
        } else {
            unset( $monthsLocal['ramadan'] );
        }

        return $monthsLocal;
    }

    public function getLocalNumbers()
    {
        $numbers_local = get_option('numbersLocal');

        return empty($numbers_local) ? $this->numbersLocal : $numbers_local;
    }


    public function getLocalTimes()
    {
        $times = get_option('timesLocal');

        return empty($times) ? $this->timesLocal : $times;
    }

    /**
     * @param  string $mysqlDate
     * @param  string $format
     * @return string
     */
    protected function formatDate($mysqlDate, $format=null)
    {
        $phpDate = strtotime($mysqlDate);
        $date =  date( get_option('date_format'), $phpDate );
        if ($format) {
            $date = date($format, $phpDate);
        }
        // var_dump($date);

        return $date;
    }

    /**
     * @param  string $mysqlDate
     * @return string
     */
    protected function formatDateForPrayer($mysqlDate)
    {
        $wpDate = $this->formatDate($mysqlDate, get_option('time_format'));
        $result = str_split($wpDate);
        $intlDate = '';
        foreach ($result as $number) {
            $intlDate .= $this->localNumbers[$number];
            if (empty($this->localNumbers[$number]) && $number !== '0') {
                $intlDate .= $number;
            }
        }

        return $intlDate;
    }

    /**
     * @param  string $month
     * @param  string $day
     * @return string
     */
    protected function getClass($month, $day)
    {
        if ($day == user_current_time('j') && $month == user_current_time('m')){
            return "class = highlight";
        }
    }

    /**
     * @param  bool $isRamadan
     * @param  array $data
     * @param  bool $azanOnly
     * @return string|null
     */
    protected function getFastingTdWithData($isRamadan, $data, $azanOnly=null)
    {

        if ($isRamadan) {
            return "<td class='fasting'>" . $this->formatDateForPrayer($data). "</td>";
        } elseif ($azanOnly) {
            return "<td>" . $this->formatDateForPrayer($data). "</td>";
        }
        return "";
    }

	/**
     * @param array $row
     *
     * @return string
     */
    protected function getNextIqamahTime(array $row, $countStart=600)
    {

        $diff = $this->getNextIqamahTimeDiff($row);

        if ($diff && $diff < $countStart) {
            return "
                <p class='green ".$this->tableClass."'>
                " .  $this->nextIqamah . "
                    <span class='timeLeft ". $this->getIqamahClass( $diff )."'>" . $this->getLocalizedNumber( $diff ) . "</span> ". $this->localTimes['minute'] ."
                </p>";
        }
    }

    protected function getNextIqamahTimeDiff(array $row)
    {
        $jamahTime = $this->getJamahTime( $row );
        $now = current_time( 'H:i');
        foreach ($jamahTime as $key=>$jamah) {
            $this->nextIqamah = $this->localPrayerNames[lcfirst($key)] . ' ' . $this->localHeaders['iqamah'] . ':';
            if ($jamah >$now ) {
                if ($key == 'Sunrise') {
                    $this->nextIqamah = $this->localPrayerNames[lcfirst($key)] . ':';
                }
                $toTime = strtotime( $jamah );
                $fromTime = strtotime( $now );
                $diff = round(abs($toTime - $fromTime)/60,2);

                return $diff;
            }
        }
    }

	/**
     * @param $row
     *
     * @return string
     */
    protected function getNextPrayer($row)
    {
        $now = current_time( 'H:i');

        $jamahTime = $this->getJamahTime( $row );
        foreach ($jamahTime as $jamah) {
            if ($jamah > $now ) {
                $prayer = array_search( $jamah, $row ); // asr_jamah
                $prayer = explode( '_', $prayer);
                return $prayer[0]; // asr
            }
        }
    }

	/**
     * @param array $row
     *
     * @return array
     */
    protected function getJamahTime(array $row)
    {
        $value = array( $row["fajr_jamah"], $row['sunrise'], $row["zuhr_jamah"], $row["asr_jamah"], $row["maghrib_jamah"], $row["isha_jamah"]);

        return array_combine( $this->prayerLocal, $value );

    }

    /**
     * @param array $row
     *
     * @return array
     */
    protected function getAzanTime(array $row)
    {
        $value = array( $row["fajr_begins"], $row['sunrise'], $row["zuhr_begins"], $row["asr_begins"], $row["maghrib_begins"], $row["isha_begins"]);

        return array_combine( $this->prayerLocal, $value );
    }

    /**
     * @param $numbers
     *
     * @return string
     */
    protected function getLocalizedNumber($numbers)
    {
        $numbers = str_split( $numbers );
        $localNumber = "";
        foreach ($numbers as $number) {
            $localNumber .= $this->localNumbers[$number];
        }

        return $localNumber;
    }

    /**
     * @param $number
     *
     * @return string
     */
    protected function getIqamahClass($number)
    {
        if ( $number > 30 ) {
            return 'green';
        } elseif ( $number > 15 ) {
            return 'orange';
        }

        return 'red';
    }

}
