<?php

class DPTAjaxHandler extends DailyShortCode
{

    public function __construct()
    {
        $this->addAjaxActions();
        parent::__construct();
    }

    public function get_ds_clock()
    {
        echo date_i18n( 'g:i A' );
        die();
    }

    public function get_ds_date()
    {
        if ( get_option('hijri-chbox')) {
            $date = date_i18n( 'D jS  M' );
        } else {
            $date = date_i18n( 'l ' . get_option( 'date_format' ) );
        }

        echo $date;
        die();
    }

    public function get_ds_hijri_date()
    {
        echo $this->getHijriDate(date("d"), date("m"), date("Y"), $this->getRow());
        die();
    }

    public function get_ds_next_prayer()
    {
        echo do_shortcode("[daily_next_prayer]");
        die();
    }

    public function get_ds_iqamah_update()
    {
        if ( get_option('scrolling-text')) {
            echo get_option('scrolling-text');
        } else {
            echo do_shortcode("[display_iqamah_update orientation='horizontal']");
        }
        die();
    }

    public function get_ds_blink()
    {
        echo get_option('blink-text', 'SILENT YOUR MOBILE');
        die();
    }

    public function get_ds_prayer_timetable()
    {
        $html = '';

        $html .= '
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
            <tbody>
                <tr>
                    <td class="prayerName">
                        <span>' . $this->getLocalPrayerNames()['fajr'] . '</span>
                    </td>
                    <td class="l-red">' . do_shortcode("[fajr_start]") . '</td>
                    <td>' . do_shortcode("[fajr_prayer]") . '</td>
                </tr>
                <tr>
                    <td class="prayerName"><span>' . $this->getLocalPrayerNames()['sunrise'] . '</span></td>
                    <td class="prayerName" colspan="2">' . do_shortcode("[sunrise]") . '</td>
                </tr>';

        if ( get_option('jumuah') && $this->todayIsFriday() && $this->isJumahDisplay($this->row)) {
            $html .= '<tr>
                            <td class="prayerName"><span>' . stripslashes($this->getLocalHeaders()['jumuah']) . '</span></td>
                            <td colspan="2" class="prayerName">' . get_option('jumuah') . '</td>
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
        if ( get_option('jumuah') && (! $this->todayIsFriday() || ! $this->isJumahDisplay($this->row)) ) {
            $html .= '
                <tr>
                    <td class="prayerName"><span>' . stripslashes($this->getLocalHeaders()['jumuah']) . '</span></td>
                    <td colspan="2" class="prayerName">' . get_option('jumuah') . '</td>
                </tr>
            </tbody>';
        }

        echo $html;
        die();
    }

    private function addAjaxActions()
    {
        $f = new ReflectionClass('DPTAjaxHandler');
        foreach ($f->getMethods() as $m) {
            if ($m->class == 'DPTAjaxHandler') {
                add_action( 'wp_ajax_'.$m->name, array( $this, $m->name) );
                add_action( 'wp_ajax_nopriv_'.$m->name, array( $this, $m->name) );
            }
        }
    }
}