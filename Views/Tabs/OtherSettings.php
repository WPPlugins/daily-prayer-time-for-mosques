<h3>Other settings</h3>
<form name="otherSettings" method="post">
    <table class="dptAdmin">
        <tr>
            <td>Activate Ramadan timetable</td>
            <td><input type="checkbox" name="ramadan-chbox" value="ramadan" <?php if(get_option("ramadan-chbox") === 'ramadan'){ echo 'checked'; } ?>></td>
        </tr>
        <tr>
            <td>Set Asr start time for monthly calendar</td>
            <td>
                <select name="asrSelect">
                    <option value="both" <?php if(get_option("asrSelect") === 'both'){ echo 'selected="selected"'; } ?>>Both</option>
                    <option value="hanafi" <?php if(get_option("asrSelect") === 'hanafi'){ echo 'selected="selected"'; } ?>>Hanafi</option>
                    <option value="standard" <?php if(get_option("asrSelect") === 'standard'){ echo 'selected="selected"'; } ?>>Maliki/Shafi'i/Hanbali</option>
                </select>
            </td>
        </tr>
    </table>
    <?php submit_button('Save changes', 'primary', 'otherSettings'); ?>
</form>
