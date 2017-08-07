<?php
require_once(__DIR__.'/../TimetablePrinter.php' );
$timetable = new TimetablePrinter();

?>
<h3>Change Language</h3>
<form name="languageSettings" method="post">
    <table border="1" class="dptAdmin">
        <tr><th colspan="6">Display prayer name in your language</th></tr>
        <tr><th>Fajr</th><th>Sunrise</th><th>Zuhr</th><th>Asr</th><th>Magrib</th><th>Isha</th></tr>
        <tr>
            <?php $prayers = $timetable->getLocalPrayerNames();
            foreach($prayers as $key => $val) { ?>
                <td><input type="text" name="prayersLocal[<?php echo $key;?>]" value="<?php echo $val;?>"/></td>
            <?php } ?>
        </tr>
    </table>
    </br>
    <table border="1" class="dptAdmin">
        <tr><th colspan="3">Translate month name in your own language</th></tr>
        <tr>
            <?php $months = $timetable->getLocalMonths();
            foreach($months as $key => $val) { ?>
        <tr><td class="months"><?php echo ucfirst($key);?></td>
            <td class="months"><input type="text" name="monthsLocal[<?php echo $key;?>]" value="<?php echo $val;?>"/></td>
        </tr>
        <?php } ?>
        </tr>
    </table>
    </br>
    <table border="1" class="dptAdmin">
        <tr><th colspan="7">Other table headings in your language</th></tr>
        <tr>
            <?php $headers = $timetable->getLocalHeaders();
            foreach($headers as $key => $val) { ?>
                <th><?php echo ucfirst($key) ?></th>
            <?php } ?>
        </tr>
        <tr>
            <?php foreach($headers as $key => $val) { ?>
                <td><input class='other' type="text" name="headersLocal[<?php echo $key;?>]" value="<?php echo $val;?>"/></td>
            <?php } ?>
        </tr>
    </table>
    </br>
    <table border="1" class="dptAdmin">
        <tr><th colspan="10">Numbers in your language</th></tr>
        <tr>
            <?php $numbers = $timetable->getLocalNumbers();
            foreach($numbers as $key => $val) { ?>
                <th><?php echo $key ?></th>
            <?php } ?>
        </tr>
        <tr>
            <?php foreach($numbers as $key => $val) { ?>
                <td><input type="text" maxlength="1" size="1" name="numbersLocal[<?php echo $key;?>]" value="<?php echo $val;?>"/></td>
            <?php } ?>
        </tr>
    </table>
    </br>
    <table border="1" class="dptAdmin">
        <tr><th colspan="10">Time related values</th></tr>
        <tr>
            <?php $numbers = $timetable->getLocalTimes();
            foreach($numbers as $key => $val) { ?>
                <th><?php echo ucfirst($key) ?></th>
            <?php } ?>
        </tr>
        <tr>
            <?php foreach($numbers as $key => $val) { ?>
                <td><input type="text" name="timesLocal[<?php echo $key;?>]" value="<?php echo $val;?>"/></td>
            <?php } ?>
        </tr>
    </table>
    <div class="saveButton">
        <?php
        submit_button('Save changes', 'primary', 'languageSettings');
        ?>
    </div>
</form>
