<?php

class DigitalScreen extends DailyShortCode
{
    /** @var array */
    protected $row = array();

    /** @var bool */
    private $isPortrait;

    /** @var int */
    private $screenTimeout;

    public function __construct($attr=array())
    {
        parent::__construct();

        if ( isset($attr['view']) ) {
            $this->isPortrait = ( strtolower($attr['view']) == 'vertical' );
        }

        if ( isset($attr['dim']) ) {
            $this->screenTimeout = $attr['dim'];
        }
    }

    public function displayDigitalScreen()
    {

        $html = $this->getTopRow()
            . $this->getMiddleRow()
            .$this->getBottomRow();

        return $html;
    }

    private function getTopRow()
    {
        $timeClass = "col-sm-3 ";
        $dateClass = "col-sm-7 ";
        $height = "height-100";
        $verticalClass = "";
        if (  $this->isPortrait  ) {
            $timeClass = "col-xs-12 vertical-time ";
            $dateClass = "col-xs-12 ";
            $height = "height-50 ";
            $verticalClass = "vertical";
        }
        $html = '
        <div class="container-fluid x-board">
            <input type="hidden" value="' . $this->screenTimeout . '" id="screenTimeout">
            <div class="row top-row">
                <div class="time ' . $timeClass . 'col-xs-12 text-center ' . $height . '">
                    <div class="align-middle">
                        <span id="dsClock"></span>
                    </div>
                </div>
                <div class="' . $dateClass . ' col-xs-12 text-center bg-white ' . $height . '">
                    <div class="align-middle">
                        <span id="dsDate" class="date-eng h6 ' . $verticalClass . '"></span>
                        <span id="dsHijriDate" class="'. $verticalClass . 'hijri"></span>
                    </div>
                </div>
                <div class="col-sm-2 col-xs-12 text-right align-middle padding-null">';
        $isLogo = get_option('ds-logo');
        if ( $isLogo && ! $this->isPortrait) {
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
        $verticalClass = "";

        if ( $this->isPortrait ) {
            $verticalClass = "vertical";
            $leftClass = "col-sm-12 bg-red padding-null text-center height-50";
            $rightClass = "col-sm-12 padding-null text-center bg-green height-50";
        }

        $html =  '
            <div class="row middle-row bg-red">
                <div class="'. $leftClass .'">
                    <table id="dsPrayerTimetable" class="table height-100 '. $verticalClass .'">';

        $html .= '
            </table>
        </div>';

        $transitionEffect = get_option('transitionEffect');
        $transitionSpeed = get_option('transitionSpeed');
        $html .='
            <div class="'. $rightClass .'">
                <div id="text-carousel" class="carousel slide ' . $transitionEffect . ' 
                    height-100" data-ride="carousel" data-interval="'. $transitionSpeed .'" data-pause="false">
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
        $verticalClass = "";

        if ( $this->isPortrait ) {
            $verticalClass = "vertical";
        }

        $html = '
                <div class="row bottom-row">
                    <div class="bg-dark col-sm-3 col-xs-12 text-center height-100 align-middle">
                        <div class="align-middle">
                                <span id="dsBlink" class="blink"></span>
                        </div>
                    </div>                
                    <div class="col-sm-9 col-xs-12 height-100">
                        <div class="align-middle">
                            <h3 class="text-primary scrolling">
                                <marquee id="dsIqamahUpdate" scrollamount="11"></marquee> 
                            </h3> 
                        </div>
                    </div>
                </div>
            </div>
        ';

        if ($this->isPortrait) {
            $html = '
                <div class="row bottom-row">             
                    <div class="col-sm-12 col-xs-12 height-30">
                        <div class="align-middle">
                            <h3 class="text-primary scrolling-vertical">
                                <marquee id="dsIqamahUpdate" scrollamount="11"></marquee> 
                            </h3> 
                        </div>
                    </div>
                    <div class="bg-dark col-sm-12 col-xs-12 text-center height-50 align-middle">
                        <div class="align-middle">
                                <span id="dsBlink" class="blink-'.$verticalClass.'"></span>
                        </div>
                    </div>   
                </div>
            </div>
        ';
        }

        return $html;
    }

    private function getFirstSlide()
    {
        $verticalClass = "";
        if ( $this->isPortrait ) {
            $verticalClass = "vertical";
        }

        $html =  '
            <div class="nextPrayer height-100">
                <div class="align-middle">
                <h3 class="'.$verticalClass.'">'. $this->getLocalTimes()['next prayer'] . '</h3>
                    <h2 id="dsNextPrayer" class="dsNextPrayer '.$verticalClass.'"></h2>
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
            $slides[] = get_option('slider6Url');

            $slides = array_filter($slides);
            foreach ($slides as $i => $slideUrl) {
                $html .= '
                <div class="item" >
                    <img class="carousel-slide" src="' . $slideUrl . '">
                </div>               
                ';

                $count = $i + 1;
                if ( $count % 2 == 0 ) {
                    $html .= '
                        <div class="item">
                            ' . $this->getFirstSlide() . '
                        </div>
                    ';
                }
            }

            return $html;
        }

        return null;
    }

}