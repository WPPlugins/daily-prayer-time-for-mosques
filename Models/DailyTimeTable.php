<?php
require_once('db.php');
require_once(__DIR__.'/../Views/DailyTimetablePrinter.php');

class DailyTimeTable
{
    /** @var boolean */
    private $isJamahOnly = false;

    /** @var boolean */
    private $isAzanOnly = false;

    /** @var boolean */
    private $isHanafiAsr = false;

    /** @var array */
    private $row = array();

    /** @var  DailyTimetablePrinter */
    private $timetablePrinter;

    /** @var  string */
    private $title;

    /** @var  bool */
    private $hideRamadan = false;

    /** @var bool  */
    private $hideTimeRemaining = false;

    /** @var bool  */
    private $displayHijriDate = false;


    public function __construct()
    {
        $this->row = $this->getCalendarToday();
        $this->timetablePrinter = new DailyTimetablePrinter();
    }

    public function setAnnouncement($text, $day)
    {
        $this->row['announcement'] =  $this->getAnnouncement( $text, $day);

    }

    public function setJamahOnly()
    {
        $this->isJamahOnly = true;
    }

    public function setAzanOnly()
    {
        $this->isAzanOnly = true;
    }

    public function setHanafiAsr()
    {
        $this->isHanafiAsr = true;
    }

    public function hideRamadan()
    {
        $this->hideRamadan = true;
    }

    public function hideTimeRemaining()
    {
        $this->hideTimeRemaining = true;
    }

    public function displayHijriDate()
    {
        $this->displayHijriDate = true;
    }

    /**
     * @param  array  $attr
     * @return string
     */
    public function verticalTime($attr=array())
    {
        $row = $this->getRow($attr);

        if ($this->isJamahOnly) {
            return $this->timetablePrinter->verticalTimeJamahOnly($row);
        }

        if ($this->isAzanOnly) {
            return $this->timetablePrinter->verticalTimeAzanOnly($row);
        }

        return $this->timetablePrinter->verticalTime($row);
    }

    /**
     * @param  array  $attr
     * @return string
     */
    public function horizontalTime($attr=array())
    {
        $row = $this->getRow($attr);

        if ($this->isJamahOnly) {
            return $this->timetablePrinter->horizontalTimeJamahOnly($row);
        }

        if ($this->isAzanOnly) {
            return $this->timetablePrinter->horizontalTimeAzanOnly($row);
        }

        if (isset($attr['use_div_layout'])) {
            return $this->timetablePrinter->horizontalTimeDiv($row);
        }

        return $this->timetablePrinter->horizontalTime($row);
    }

        /**
     * @param  string $jummah
     * @return string
     */
    public function getAnnouncement($jummah="", $day)
    {
        $jummah = trim( $jummah );
        $day = trim($day);

        if (empty($jummah)) {
            return "";
        }

        $today = date('l');
        $announcement = "";
        $exploded = explode('.', $jummah);
        foreach($exploded as $line) {
            $announcement .= $line . "</br>";
        }
        if ( $today == ucfirst( $day ) || $day == 'everyday' ) {
            return trim($announcement);
        }
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function scNextPrayer($attr)
    {
        $row = $this->getRow($attr);
        $row['displayHijriDate'] = $this->displayHijriDate;
        return $this->timetablePrinter->displayNextPrayer($row);
    }

    public function scRamadanTime($attr)
    {
        $row = $this->getRow($attr);

        return $this->timetablePrinter->displayRamadanTime($row);
    }

    /**
     * return todays prayer time based on day and month
     * @return array
     */
    private function getCalendarToday()
    {
        $db = new DatabaseConnection();
        return $db->getPrayerTimeForToday();
    }

    private function getRow($attr)
    {
        $this->setDisplayForShortCode($attr);

        if (isset($attr['asr'])) {
            $this->setHanafiAsr();
        }

        if (isset($attr['heading'])) {
            $this->setTitle($attr['heading']);
        }

        $row = $this->row;

        if (isset($attr['announcement'])) {
            $day = isset($attr['day']) ? $attr['day'] : 'everyday';
            $row['announcement'] = $this->getAnnouncement($attr['announcement'], $day);
        }

        $row['widgetTitle'] = $this->title;
        $row['asr_begins'] = $this->isHanafiAsr ? $this->row['asr_mithl_2'] : $this->row['asr_mithl_1'];

        $row['hideRamadan'] = $this->hideRamadan;
        $row['hideTimeRemaining'] = $this->hideTimeRemaining;
        $row['displayHijriDate'] = $this->displayHijriDate;

        return $row;
    }

    /**
     * @param array $attr
     */
    private function setDisplayForShortCode($attr)
    {
        if (isset($attr['display'])) {
            if ( $attr['display'] === 'iqamah_only' ) {
                $this->setJamahOnly();
            } elseif ( $attr['display'] === 'azan_only' ) {
                $this->setAzanOnly();
            }
        }

        if (isset($attr['hide_time_remaining'])) {
            $this->hideTimeRemaining();
        }

        if (isset($attr['hide_ramadan'])) {
            $this->hideRamadan();
        }

        $hijriCheckbox = get_option('hijri-chbox');
        if (! empty($hijriCheckbox)) {
            $this->displayHijriDate();
        }
    }
}
