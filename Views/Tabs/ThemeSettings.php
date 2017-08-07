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
            <td>Table background colour</td>
            <td><input type="text" name="tableBackground" value="<?php echo get_option('tableBackground') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Table heading</td>
            <td><input type="text" name="tableHeading" value="<?php echo get_option('tableHeading') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Alternate row colour</td>
            <td><input type="text" name="evenRow" value="<?php echo get_option('evenRow') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Highlight colour</td>
            <td><input type="text" name="highlight" value="<?php echo get_option('highlight') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Font color</td>
            <td><input type="text" name="fontColor" value="<?php echo get_option('fontColor') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td>Notification background colour</td>
            <td><input type="text" name="notificationBackground" value="<?php echo get_option('notificationBackground') ?>" class="color-field"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type='submit' name='themeSettings' id='themeSettings' class='button button-primary' value='Update UI'>
            </td>
        </tr>
    </table>
</form>