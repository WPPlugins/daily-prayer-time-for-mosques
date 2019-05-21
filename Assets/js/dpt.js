var isTimerOn = false;

DPT = {
    init: function() {

        this.monthlyCalendarChange();
        this.displaySliderOptions();
        this.changeInputBackground();
        this.printDiv();
        this.bs3screenOptions();

        this.startTimer();

        this.dsRefresh('get_ds_clock', '#dsClock');
        this.dsRefresh('get_ds_date', '#dsDate', 65);
        this.dsRefresh('get_ds_hijri_date', '#dsHijriDate', 70);
        this.dsRefresh('get_ds_prayer_timetable', '#dsPrayerTimetable', 75);
        this.dsRefreshNextPrayer();
        this.dsRefresh('get_ds_iqamah_update', '#dsIqamahUpdate', 80);
        this.dsRefresh('get_ds_blink', '#dsBlink', 85);

        this.refreshAt(2, 0, 0);
        this.refreshAt(12, 0, 0);
        this.refreshAt(18, 0, 0);
    },

    monthlyCalendarChange: function () {
        jQuery('#monthAjax').on('change', '#month', function() {
            jQuery.blockUI({
                timeout:   1000,
            });
            var display = jQuery('#display').val();
            var month = this.value;
            jQuery.ajax({
                url: timetable_params.ajaxurl,
                data: {
                    'action':'get_monthly_timetable',
                    'month' : month,
                    'display': display
                },
                success: function(response){
                    jQuery('#monthlyTimetable').html(response);
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        });

        jQuery('#month').trigger('change');
    },

    displaySliderOptions: function () {
        var sliderChbox = jQuery("input#slider-chbox");

        sliderChbox.on('click', function() {
            jQuery(".ds-slides").toggle("slow");
        });

        if (! sliderChbox.is(':checked')) {
            jQuery(".ds-slides").hide();
        }
    },

    changeInputBackground: function () {
        jQuery("input").on('change', function() {
            jQuery(this).css("background-color","#F6F8CE");
        });
    },

    printDiv: function (divName) {
        if (divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    },

    bs3screenOptions: function( ) {
        jQuery("#contextual-help-link").click(function () {
            jQuery("#contextual-help-wrap").css("cssText", "display: block !important;");
        });
        jQuery("#show-settings-link").click(function () {
            jQuery("#screen-options-wrap").css("cssText", "display: block !important;");
        });
    },

    dsRefresh: function (method, element, seconds=60) {
        if (! jQuery('.x-board')[0]) {
            return;
        }
        jQuery.ajax({
            url: timetable_params.ajaxurl,
            data: {
                'action':method,
            },
            success: function(response){
                setTimeout(DPT.dsRefresh, (1000 * parseInt(seconds)), method, element );
                jQuery(element).html(response);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    },

    dsRefreshNextPrayer: function () {
        if (! jQuery('.x-board')[0]) {
            return;
        }
        jQuery.ajax({
            url: timetable_params.ajaxurl,
            data: {
                'action':'get_ds_next_prayer',
            },
            success: function(response){
                setTimeout(DPT.dsRefreshNextPrayer, (1000 * 60));
                jQuery('.dsNextPrayer').html(response);
                if (! isTimerOn ) {
                    DPT.startTimer();
                    isTimerOn = true;
                }
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    },

    startTimer: function () {
        var presentTime = '';

        if (document.getElementsByClassName('timeLeftCountDown')[0]) {
            presentTime = document.getElementsByClassName('timeLeftCountDown')[0].innerHTML.trim();
            presentTime = presentTime.split(' ')[0];

            var timeArray = presentTime.split(/[:]+/);
            if (timeArray && timeArray.length === 2) {

                var m = timeArray[0];
                var s = DPT.checkSecond((timeArray[1] - 1));

                if(s == "59"){ m = m - 1;}

                if ( m >= 0) {
                    var timeLeftCountDownElement = document.getElementsByClassName('timeLeftCountDown');
                    for(var i = 0; i < timeLeftCountDownElement.length; i++) {
                        document.getElementsByClassName('timeLeftCountDown')[i].innerHTML = m + ":" + s;
                    }
                }

                if(m == 0 && s == 0) {
                    DPT.timeoutScreen();
                }
            }
            setTimeout(DPT.startTimer, 1000);
        }
    },

    timeoutScreen: function() {
        var min = jQuery('#screenTimeout').val()
        if (min) {
            jQuery("body").append("<div id='overlay'></div>");
            jQuery('#overlay').animate({
                opacity: 0.9,
            }, (10000), function() {
            });
            // setTimeout(function() { jQuery('#overlay').remove(); }, 1000 * 60 * parseInt(min) );
            setTimeout(function() { window.location.reload() }, 1000 * 60 * parseInt(min) );
        }
    },

    checkSecond: function (sec) {
        if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
        if (sec < 0) {sec = "59"};
        return sec;
    },

    refreshAt: function (hours, minutes, seconds) {
        var now = new Date();
        var then = new Date();
    
        if(now.getHours() > hours ||
           (now.getHours() == hours && now.getMinutes() > minutes) ||
            now.getHours() == hours && now.getMinutes() == minutes && now.getSeconds() >= seconds) {
            then.setDate(now.getDate() + 1);
        }
        then.setHours(hours);
        then.setMinutes(minutes);
        then.setSeconds(seconds);
    
        var timeout = (then.getTime() - now.getTime());
        setTimeout(function() { window.location.reload(true); }, timeout);
    }
};
jQuery(document).ready(function() { DPT.init(); });

