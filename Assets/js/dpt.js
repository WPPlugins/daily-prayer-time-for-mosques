DPT = {
    init: function() {
        this.monthlyCalendarChange();
        this.displaySliderOptions();
        this.changeInputBackground();
        this.printDiv();
        this.startTimer();
        this.bs3screenOptions();
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
                    alert(JSON.stringify(errorThrown));
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

    startTimer: function () {
        var presentTime = '';
        if (document.getElementsByClassName('timeLeftCountDown')[0]) {
            presentTime = document.getElementsByClassName('timeLeftCountDown')[0].innerHTML.trim();
            presentTime = presentTime.split(' ')[0];
            var timeArray = presentTime.split(/[:]+/);
            if (timeArray && timeArray.length === 2) {
                var m = timeArray[0];
                var s = DPT.checkSecond((timeArray[1] - 1));
                if(s==59){m=m-1}
                if ( m >= 0) {
                    document.getElementsByClassName('timeLeftCountDown')[0].innerHTML = m + ":" + s;
                    setTimeout(DPT.startTimer, 1000);
                }
                if(m == 0 && s == 0) {
                    window.location.reload();
                }

            }
        }
    },

    checkSecond: function (sec) {
        if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
        if (sec < 0) {sec = "59"};
        return sec;
    },

    bs3screenOptions: function( ) {
            jQuery("#contextual-help-link").click(function () {
                jQuery("#contextual-help-wrap").css("cssText", "display: block !important;");
            });
            jQuery("#show-settings-link").click(function () {
                jQuery("#screen-options-wrap").css("cssText", "display: block !important;");
            });
    }
};
jQuery(document).ready(function() { DPT.init(); });

