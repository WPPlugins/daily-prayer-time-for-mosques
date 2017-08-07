<style>
    .todayDiv {
        padding: 10px 0px 10px 0px;
    }
    .todaySpan {
        border: 1px dotted;
        padding: 5px;
        font-weight: bold;
    }
</style>
<?php
$hd = new HijriDate();
$date = $hd->getDate(date("d"), date("m"), date("Y"), true);
?>
<h3>Hijri date settings</h3>
<div class="todayDiv">
    <span class="todaySpan">Today is <?= $date?><span>
</div>
<form name="hijriSettings" method="post">
    <table class="dptAdmin">
        <tr>
            <td>Adjust day:</td>
            <td>
                <input type="number" name="hijri-adjust" min="-2" max="2" value="<?=get_option('hijri-adjust')?>">
            </td>
        </tr>
        <tr>
            <td>Display Hijri date:</td>
            <td>
                <input type="checkbox" name="hijri-chbox" value="hijri" <?php if(get_option("hijri-chbox") === 'hijri'){ echo 'checked'; } ?>>
            </td>
        </tr>
    </table>
    <?php submit_button('Save changes', 'primary', 'hijriSettings'); ?>
</form>
