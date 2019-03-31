<?php
require_once('db.php');
require_once(__DIR__.'/../Views/DailyTimetablePrinter.php');
require_once(__DIR__.'/../Views/TimetablePrinter.php' );


class DailyShortCode extends TimetablePrinter
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

    /** $var db */
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseConnection();
        $this->row = $this->db->getPrayerTimeForToday();
        $this->timetablePrinter = new DailyTimetablePrinter();
        parent::__construct();
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
        $this->timetablePrinter->setVertical(true);

        $row = $this->getRow($attr);

        if ($this->isJamahOnly) {
            return $this->timetablePrinter->verticalTimeJamahOnly($row);
        }

        if ($this->isAzanOnly) {
            return $this->timetablePrinter->verticalTimeAzanOnly($row);
        }

        return $this->timetablePrinter->printVerticalTime($row);
    }

    /**
     * @param  array  $attr
     * @return string
     */
    public function horizontalTime($attr=array())
    {
        $this->timetablePrinter->setHorizontal(true);

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

        return $this->timetablePrinter->printHorizontalTime($row);
    }

        /**
     * @param  string $widgetNotice
     * @return string
     */
    public function getAnnouncement($widgetNotice="", $day)
    {
        $widgetNotice = trim( $widgetNotice );
        $day = trim($day);

        if (empty($widgetNotice)) {
            return "";
        }

        $today = date('l');
        $announcement = "";
        $exploded = explode(PHP_EOL, $widgetNotice);
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
        $nextFajr = $this->db->getFajrJamahForTomorrow();
        $row['displayHijriDate'] = $this->displayHijriDate;
        return $this->timetablePrinter->displayNextPrayer($row, $nextFajr);
    }


    public function scRamadanTime($attr)
    {
        $row = $this->getRow($attr);

        return $this->timetablePrinter->displayRamadanTime($row);
    }

    public function scFajr($attr)
    {
        $result = "<span class='dpt_jamah'>" . $this->formatDateForPrayer($this->row['fajr_jamah']) . "</span>";
        if ( isset($attr['start_time']) ) {
            $result = "<span class='dpt_start'>" . $this->formatDateForPrayer($this->row['fajr_begins']) . "</span>" . $result;
        }
        return $result;
    }

    public function scFajrStart($attr)
    {
            return "<span class='dpt_start'>" . $this->formatDateForPrayer($this->row['fajr_begins']) . "</span>";
    }

    public function scSunrise($attr)
    {
        return "<span class='dpt_sunrise'>" . $this->formatDateForPrayer($this->row['sunrise']) . "</span>";
    }

    public function scZuhr($attr)
    {
        $result = "<span class='dpt_jamah'>" . $this->formatDateForPrayer($this->row['zuhr_jamah']) . "</span>";
        if ( isset($attr['start_time']) ) {
            $result = "<span class='dpt_start'>" . $this->formatDateForPrayer($this->row['zuhr_begins']) . "</span>" . $result;
        }
        return $result;
    }

    public function scZuhrStart($attr)
    {
            return "<span class='dpt_start'>" . $this->formatDateForPrayer($this->row['zuhr_begins']) . "</span>";
    }

    public function scAsr($attr)
    {
        $result = "<span class='dpt_jamah'>" . $this->formatDateForPrayer($this->row['asr_jamah']) . "</span>";
        if ( isset($attr['start_time']) ) {
            $result = "<span class='dpt_start'>" . $this->formatDateForPrayer($this->row['asr_mithl_1']) . "</span>" . $result;
        }
        return $result;
    }

    public function scAsrStart($attr)
    {
            return "<span class='dpt_start'>" . $this->formatDateForPrayer($this->row['asr_mithl_1']) . "</span>";
    }

    public function scMaghrib($attr)
    {
        $result = "<span class='dpt_jamah'>" . $this->formatDateForPrayer($this->row['maghrib_jamah']) . "</span>";
        if ( isset($attr['start_time']) ) {
            $result = "<span class='dpt_start'>" . $this->formatDateForPrayer($this->row['maghrib_begins']) . "</span>" . $result;
        }
        return $result;
    }

    public function scMaghribStart($attr)
    {
            return "<span class='dpt_start'>" . $this->formatDateForPrayer($this->row['maghrib_begins']) . "</span>";
    }

    public function scIsha($attr)
    {
        $result = "<span class='dpt_jamah'>" . $this->formatDateForPrayer($this->row['isha_jamah']) . "</span>";
        if ( isset($attr['start_time']) ) {
            $result = "<span class='dpt_start'>" . $this->formatDateForPrayer($this->row['isha_begins']) . "</span>" . $result;
        }
        return $result;
    }

    public function scIshaStart($attr)
    {
            return "<span class='dpt_start'>" . $this->formatDateForPrayer($this->row['isha_begins']) . "</span>";
    }

    public function scDigitalScreen($attr)
    {
        $html = $this->getTopRow($attr)
                . $this->getMiddleRow()
                .$this->getBottomRow();

        return $html;
    }

    private function getTopRow($attr)
    {
        $html = '
        <div class="container-fluid x-board">
            <div class="row top-row">
                <div class="time col-sm-3 col-xs-12 text-center height-100">
                    <div class="align-middle">
                        <span>' .  date_i18n( 'g:i A' ) . '</span>
                    </div>
                </div>
                <div class="col-sm-7 col-xs-12 text-center height-100">
                    <div class="align-middle">
                        <span class="date-eng h6">' .  date_i18n( 'D jS  M' ) . '</span>
                        <span>' . $this->getHijriDate(date("d"), date("m"), date("Y"), $this->getRow($attr)) . '</span>
                    </div>
                </div>
                <div class="col-sm-2 col-xs-12 text-right align-middle padding-null">';
                $isLogo = get_option('ds-logo');
                if ( $isLogo ) {
                    $html .= '<img class="logo" src="' . $isLogo . '">';
                }
            $html .=
                '</div>
            </div>';
        return $html;
    }
    private function getMiddleRow()
    {
        $leftClass = "col-sm-5 col-xs-12 bg-red height-100 padding-null text-center";
        $rightClass = "col-sm-7 col-xs-12 padding-null text-center bg-green height-100 padding-null";

        $isVertical = get_option('vertical-chbox');
        if ($isVertical) {
            $leftClass = "col-sm-12 bg-red height-100 padding-null text-center";
            $rightClass = "col-sm-12 padding-null text-center bg-green height-100";
        }

        $html =  '
            <div class="row middle-row bg-red">
                <div class="'. $leftClass .'">
                    <table class="table height-100">
                        <thead class="bg-dark">
                            <tr>
                                <td>
                                    <span class="dpt_start">' . strtoupper($this->getLocalHeaders()['prayer']) . '</span>
                                </td>
                                <td>
                                    <span class="dpt_start">' . strtoupper($this->getLocalHeaders()['begins']) . '</span>
                                </td>
                                <td> 
                                    <span class="dpt_jamah">' . strtoupper($this->getLocalHeaders()['iqamah']) . '</span>
                                </td>
                            </tr>
                        </thead>
                        <tr>
                            <td class="prayerName">
                                <span>' . $this->getLocalPrayerNames()['fajr'] . '</span>
                            </td>
                            <td class="l-red">' . do_shortcode("[fajr_start]") . '</td>
                            <td>' . do_shortcode("[fajr_prayer]") . '</td>
                        </tr>
                        <tr>
                            <td class="prayerName"><span>' . $this->getLocalPrayerNames()['sunrise'] . '</span></td>
                            <td class="l-red" colspan="2">' . do_shortcode("[sunrise]") . '</td>
                        </tr>';
        if ( get_option('jumuah') && $this->todayIsFriday()) {
            $html .= '<tr>
                            <td class="prayerName"><span>' . stripslashes($this->getLocalHeaders()['jumuah']) . '</span></td>
                            <td colspan="2" class="l-red">' . get_option('jumuah') . '</td>
                        </tr>';
        } else {
            $html .= '
            <tr>
                <td class="prayerName"><span>' . $this->getLocalPrayerNames()['zuhr'] . '</span></td>
                <td class="l-red">' . do_shortcode("[zuhr_start]") . '</td>
                <td>' . do_shortcode("[zuhr_prayer]") . '</td>
            </tr>
            ';
        }

        $html .= '<tr>
            <td class="prayerName"><span>' . $this->getLocalPrayerNames()['asr'] . '</span></td>
            <td class="l-red">' . do_shortcode("[asr_start]") . '</td>
            <td>' . do_shortcode("[asr_prayer]") . '</td>
        </tr>
        <tr>
            <td class="prayerName"><span>' . $this->getLocalPrayerNames()['maghrib'] . '</span></td>
            <td class="l-red">' . do_shortcode("[maghrib_start]") . '</td>
            <td>' . do_shortcode("[maghrib_prayer]") . '</td>
        </tr>
        <tr>
            <td class="prayerName"><span>' . $this->getLocalPrayerNames()['isha'] . '</span></td>
            <td class="l-red">' . do_shortcode("[isha_start]") . '</td>
            <td>' . do_shortcode("[isha_prayer]") . '</td>
        </tr>';
        if ( get_option('jumuah') && ! $this->todayIsFriday()) {
            $html .= '<tr>
                            <td class="prayerName"><span>' . stripslashes($this->getLocalHeaders()['jumuah']) . '</span></td>
                            <td colspan="2" class="l-red">' . get_option('jumuah') . '</td>
                        </tr>';
        }

        $html .='
            </table>
                </div>
                <div class="'. $rightClass .'">
                    <div id="text-carousel" class="carousel slide height-100" data-ride="carousel">
                        <div class="carousel-inner height-100">
                            <div class="item active height-100">
                            ' . $this->getFirstSlide() . '
                            </div>
                            ' . $this->getOtherSlides() . '
                        </div>
                    </div>
                </div>
            </div>
        ';

        return $html;
    }

    private function getBottomRow()
    {
        return '
                <div class="row bottom-row">
                    <div class="bg-dark col-sm-3 col-xs-12 text-center height-100 align-middle">
                        <div class="align-middle">
                                <span class="blink">' . get_option('blink-text', 'SILENT YOUR MOBILE') . '</span>
                        </div>
                    </div>                
                    <div class="col-sm-9 col-xs-12 height-100">
                        <div class="align-middle">
                            <h3 class="text-primary scrolling">
                                <marquee scrollamount="11">'
            . do_shortcode("[display_iqamah_update]")
            . get_option('scrolling-text') . '
                                </marquee> 
                            </h3> 
                        </div>
                    </div>
                </div>
            </div>
        ';
    }

    private function getFirstSlide()
    {
        $html =  '
            <div class="nextPrayer height-100">
                <div class="align-middle">
                <h3>'. $this->getLocalTimes()['next prayer'] . '</h3>
                    <h2>' . do_shortcode("[daily_next_prayer]") . '</h2>
                </div>
            </div>';

        return $html;
    }

    private function getOtherSlides()
    {
        $isSlide = get_option('slider-chbox');
        if ( $isSlide ) {
            $html = "";
            $slides = array();

            $slides[] = get_option('slider1Url');
            $slides[] = get_option('slider2Url');
            $slides[] = get_option('slider3Url');
            $slides[] = get_option('slider4Url');
            $slides[] = get_option('slider5Url');

            $slides = array_filter($slides);
            foreach ($slides as $slideUrl) {
                $html .= '
                <div class="item">
                    <img class="carousel-slide" src="' . $slideUrl . '">
                </div>
                ';
            }

            return $html;
        }

        return null;
    }

    public function scIqamahUpdate($attr)
    {
        $jamahChanges = $this->db->getJamahChanges(1);
        if (empty($jamahChanges)) { return; }

        $timeRelated = $this->getLocalTimes();
        $print = "<span class='jamahChanges'><span class='tomorrow'>" . $timeRelated['iqamah update'] . "</span>";
        $prayerNames = $this->getLocalPrayerNames();

        foreach($jamahChanges as $key=>$time) {
            if (! empty($key) ){
                $prayer = explode('_', $key);
                if ( $this->tomorrowIsFriday() ) {
                    $prayerNames['zuhr'] = $this->getLocalHeaders()['jumuah'];
                } else {
                    $prayerNames['zuhr'] = $this->prayerLocal['zuhr'];
                }
                $print .= "<span class='x-time-change'>" . $prayerNames[$prayer[0]] . " " .  $this->formatDateForPrayer($time) . "</span>";
            }
        }
        $print .= "</span>";

        return $print;
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

        if ( $row['jamah_changes']) {
            $row['announcement'] .= $this->timetablePrinter->getJamahChange($this->row);
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