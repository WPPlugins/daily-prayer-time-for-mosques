<h3>CSV Upload</h3>
<form enctype="multipart/form-data" name="csvUpload" method="post" action="">
            <span>
                <input type="file" name="timetable" id="timetable" accept=".csv">
            </span>
    <?php submit_button('Upload prayer time'); ?>
    <div>
        <ol>
            <li><a href="<?php echo plugins_url( '../../Assets/sample.csv', __FILE__ ) ?>"> Download csv template</a></li>
            <li><a target="_blank" href="http://praytimes.org/code/v2/js/examples/monthly.htm"> You may want to use generated prayer start time for your CSV</a></li>
            <li>Valid date formats are:
                <ul class="red">
                    <li> - Y-M-D</li>
                    <li> - M/D/Y </li>
                    <li> - D-M-Y</li>
                    <li> - D.M.Y</li>
                </ul>
            </li>
            <li>valid time format is <span class="red">HH:MM</span> [24 hours]</li>
            <li>Use Widgets or these <a href="admin.php?page=helps-and-tips#shortcodes">shortcodes</a></li>
        </ol>
    </div>
</form>
