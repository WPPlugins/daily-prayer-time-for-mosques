<p class="green">
    <span class="red">! important !</span> Please
    <a href="plugins.php"> re-activate</a>
    the plugin if your data is not imported
</p>

<?php
echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
echo '<h1>Helps and Tips</h1>';
?>
<li><a target="_blank" href="http://praytimes.org/code/v2/js/examples/yearly.htm"> Click here</a> for auto-generated prayer start time for your location</li>
<li><a target="_blank" href="http://localhost/wp/wp-content/plugins/daily-prayer-time-for-mosques/Assets/sample.csv"> Click here</a> for CSV sample and update your Iqamah time</li>

<p>
    <h2><u>Features</u></h2>
    <ol>
        <li>Display prayer start and jamah time</li>
        <li>Display prayer time either vertical or horizontal widget.</li>
        <li>Display 'Jamah time' only if you chose.</li>
        <li>Chose from three different themes</li>
        <li>Chose Asr salah start method</li>
        <li>Display monthly and yearly timetable using shortcode [timetable] from any page or post</li>
        <li>Display Khutbah time announcement on Friday</li>
        <li>Display Iqamah time only for monthly timetable using shortcode</li>
        <li>Upload any number of days, weeks, months or a full year.</li>
        <li>Change monthly timetable heading, month name in your local language.</li>
        <li>Ramadan calendar for daily and monthly display</li>
        <li>Highlight coming prayer</li>
        <li>Display time left for next iqamah for 60 mins</li>
        <li>Notifications on daily widgets</li>
    </ol>
</p>
<p>
    <h2><u><a name="shortcodes"></a>Shortcodes:</u></h2>
    <ol>
        <li>[monthlytable]</li>
        <li>[monthlytable_daily]</li>
        <li>[monthlytable_daily_horizontal]</li>
        <li>[daily_next_prayer] <i>(no shortcode options)</i></li>
        <li>[display_ramadan_time] <i>(no shortcode options)</i></li>
    </ol>

    <h2>Shortcode Options:</h2>
    <ol>
        <li>asr=hanafi</li>
        <li>display=iqamah_only/azan_only</li>
        <li>hide_time_remaining=true</li>
        <li>hide_ramadan=true</li>
        <li>announcement="Any text" day=everyday/saturday/sunday/monday/tuesday/wednesday/thursday/friday</li>
        <li>heading="any text"</li>
        <li>display_hijri_date=true</li>
        <li>use_div_layout=true (you can easily update php/html/css using this mode)</li>
    </ol>
</p>
<p>
    <h2>shortcodes examples</h2>
    <ol>

        <li><b>[daily_next_prayer]</b> - Display only next prayer on post or page</li>
        <li><b>[monthlytable]</b> - Display Yearly and Monthly prayer time with ajax month selector</li>
        <li><b>[monthlytable display=iqamah_only]</b> - Display Iqamah only for Yearly and Monthly prayer time with ajax month selector</li>
        <li><b>[monthlytable display=azan_only]</b> - Display monthly time table heading in any language, default is 'Monthly Time Table for'</li>
        <li><b>[monthlytable heading="Månedlige Tidsplan"]</b> - Display monthly time table heading in any language, default is 'Monthly Time Table for'</li>
        <li><b>[monthlytable heading="Månedlige Tidsplan" display=azan_only]</b> - Please notice the use of " " while using multiple words in a shortcode option</li>
        <li><b>[monthlytable_daily]</b> - Display daily timetable vertically</li>
        <li><b>[monthlytable_daily asr=hanafi]</b> - Display daily timetable vertically with Hanafi Asr start method</li>
        <li><b>[monthlytable_daily_horizontal]</b> - Display daily timetable horizontally</li>
        <li><b>[monthlytable_daily_horizontal display=iqamah_only]</b> - Display daily azan only timetable horizontally</li>
        <li><b>[monthlytable_daily_horizontal asr=hanafi]</b> - Display daily timetable horizontally with Hanafi Asr start method</li>
        <li><b>[monthlytable_daily_horizontal asr=hanafi display=azan_only]</b> - Display daily iqamah only timetable horizontally with Hanafi Asr start method</li>
        <li><b>[monthlytable_daily asr=hanafi announcement="First Khutbah: 1:15. Second Khutbah: 1:45" day=friday]</b> - Display announcement on your given day or everyday</li>
    </ol>
        <a href="https://wordpress.org/plugins/daily-prayer-time-for-mosques/screenshots/" target="_new">Please check the screen shots</a>
</p>
<p>
    <h2><u>How to update ramadan timetable</u></h2>
    Simply put '1' for the last column(is_ramadan) in the sample csv for the days belongs to ramadan before upload
</p>
<?php
echo '</div>';
