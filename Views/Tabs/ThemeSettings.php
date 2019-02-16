<h3>Theme settings</h3>
<form name="themeSettings" method="post">
    <table class='dptAdmin'>
        <tr>
            <td>Use borderless widget</td>
            <td><input type="checkbox"
                        name="hideTableBorder"
                        value="dptNoBorder" <?php if(get_option("hideTableBorder") === 'dptNoBorder'){ echo 'checked'; } ?>>
            </td>
        </tr>
        <tr>
            <td>Table background</td>
            <td><input type="text" name="tableBackground" value="<?php echo get_option('tableBackground') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Font color</td>
            <td><input type="text" name="fontColor" value="<?php echo get_option('fontColor') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Table heading</td>
            <td><input type="text" name="tableHeading" value="<?php echo get_option('tableHeading') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Table heading font</td>
            <td><input type="text" name="tableHeadingFont" value="<?php echo get_option('tableHeadingFont') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Prayer name background</td>
            <td><input type="text" name="prayerName" value="<?php echo get_option('prayerName') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Prayer name font</td>
            <td><input type="text" name="prayerNameFont" value="<?php echo get_option('prayerNameFont') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Alternate row</td>
            <td><input type="text" name="evenRow" value="<?php echo get_option('evenRow') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Highlight</td>
            <td><input type="text" name="highlight" value="<?php echo get_option('highlight') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Notification background</td>
            <td><input type="text" name="notificationBackground" value="<?php echo get_option('notificationBackground') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Notification Font</td>
            <td><input type="text" name="notificationFont" value="<?php echo get_option('notificationFont') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Digital Screen red</td>
            <td><input type="text" name="digitalScreenRed" value="<?php echo get_option('digitalScreenRed') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Digital Screen light red</td>
            <td><input type="text" name="digitalScreenLightRed" value="<?php echo get_option('digitalScreenLightRed') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Digital Screen green</td>
            <td><input type="text" name="digitalScreenGreen" value="<?php echo get_option('digitalScreenGreen') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Digital Screen Prayer name</td>
            <td><input type="text" name="digitalScreenPrayerName" value="<?php echo get_option('digitalScreenPrayerName') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type='submit' name='themeSettings' id='themeSettings' class='button button-primary' value='Update UI'>
            </td>
        </tr>
    </table>
</form>