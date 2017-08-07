
jQuery(document).ready(function() {

//hover states on the static widgets
    jQuery('#dialog_link, ul#icons li').hover(
        function() { jQuery(this).addClass('ui-state-hover'); },
        function() { jQuery(this).removeClass('ui-state-hover'); }
    );

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

    jQuery("input").on('change', function() {
       jQuery(this).css("background-color","#F6F8CE");
    });
});


function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}


