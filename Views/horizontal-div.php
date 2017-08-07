<?php
$nextPrayer = ucfirst( $this->getNextPrayer( $row ) );

foreach ($this->localPrayerNames as $name) {
    if ($nextPrayer == $name) {
        $highlight = 'highlight';
    }
}
if (! $row['hideTimeRemaining']) {
    $nextIqamah = $isAzanOnly == true ? '' : $this->getNextIqamahTime( $row );
}
if(isset($row['announcement']) && ! empty( $row['announcement'] )) {
    $announcement = "<tr><th colspan='7' style='text-align:center' class='notificationBackground'>".$row['announcement']. "</th></tr>";
}

?>
<div class="dpt-horizontal-wrapper customStyles">
    <div class="dpt-heading">
        <h3 class="date">
            <?php
                echo $row['widgetTitle'].
                ' ' . date_i18n( get_option( 'date_format' ) );
                 if($row['displayHijriDate']) echo ' - '. $this->hijriDate->getDate(date("d"), date("m"), date("Y"), true)
            ?>
        </h3>
        <?= $nextIqamah ?>
    </div>
    <div class="dpt-wrapper-container">

        <div class="prayer-time prayer-fajr <?php if ($nextPrayer == $this->localPrayerNames['fajr']) echo "highlight"; ?>">

            <h3 id="fajrRamadhan"><?=$this->localPrayerNames['fajr']?></h3>
            <div
                class="prayer-start">
                <?= $this->formatDateForPrayer($row["fajr_begins"]);?>
            </div>
            <div class="prayer-jamaat"><?= $this->formatDateForPrayer($row["fajr_jamah"]);?></div>

        </div> <!-- END of prayer time-->
        <div class="prayer-time prayer-sunrise <?php if ($nextPrayer == $this->localPrayerNames['sunrise']) echo "highlight"; ?>">

            <h3><?=$this->localPrayerNames['sunrise']?></h3>
            <div class="prayer-start"><?= $this->formatDateForPrayer($row["sunrise"]);?></div>
            <div>&nbsp;</div>

        </div> <!-- END of prayer time-->
        <div class="prayer-time prayer-dhuhr <?php if ($nextPrayer == $this->localPrayerNames['zuhr']) echo "highlight"; ?>">

            <h3><?=$this->localPrayerNames['zuhr']?></h3>
            <div class="prayer-start"><?= $this->formatDateForPrayer($row["zuhr_begins"]);?></div>
            <div class="prayer-jamaat"><?= $this->formatDateForPrayer($row["zuhr_jamah"]);?></div>

        </div> <!-- END of prayer time-->
        <div class="prayer-time prayer-asr <?php if ($nextPrayer == $this->localPrayerNames['asr']) echo "highlight"; ?>">

            <h3><?=$this->localPrayerNames['asr']?></h3>
            <div class="prayer-start"><?= $this->formatDateForPrayer($row["asr_begins"]);?></div>
            <div class="prayer-jamaat"><?= $this->formatDateForPrayer($row["asr_jamah"]);?></div>

        </div> <!-- END of prayer time-->
        <div class="prayer-time prayer-maghrib <?php if ($nextPrayer == $this->localPrayerNames['maghrib']) echo "highlight"; ?>">

            <h3 id="maghribRamadhan"><?=$this->localPrayerNames['maghrib']?></h3>
            <div class="prayer-start"><?= $this->formatDateForPrayer($row["maghrib_begins"]);?></div>
            <div class="prayer-jamaat"><?= $this->formatDateForPrayer($row["maghrib_jamah"]);?></div>

        </div> <!-- END of prayer time-->
        <div class="prayer-time prayer-isha <?php if ($nextPrayer == $this->localPrayerNames['isha']) echo "highlight"; ?>">

            <h3><?=$this->localPrayerNames['isha']?></h3>
            <div class="prayer-start"><?= $this->formatDateForPrayer($row["isha_begins"]);?></div>
            <div class="prayer-jamaat"><?= $this->formatDateForPrayer($row["isha_jamah"]);?></div>

        </div> <!-- END of prayer time-->

    </div> <!-- END of wrapper container-->

<?php if(isset($row['announcement']) && ! empty( $row['announcement'] )) {?>
    <div class="dpt-announcement"><h3><?= $row['announcement'] ?></h3></div>
<?php } ?>
</div>

<?php

if (get_option('ramadan-chbox') && ! $row['hideRamadan']) { ?>

<script>

(function(){
    var words = [
        'Fajr',
        'Suhoor',
        ], i = 0;
    setInterval(function(){
        jQuery('#fajrRamadhan').fadeOut(function(){
            jQuery(this).html(words[i=(i+1)%words.length]).fadeIn();
        });
    }, 3000);

})();

(function(){
    var words = [
        'Maghrib',
        'Iftaar',
        ], i = 0;
    setInterval(function(){
        jQuery('#maghribRamadhan').fadeOut(function(){
            jQuery(this).html(words[i=(i+1)%words.length]).fadeIn();
        });
    }, 3000);

})();
</script>

<?php } ?>


