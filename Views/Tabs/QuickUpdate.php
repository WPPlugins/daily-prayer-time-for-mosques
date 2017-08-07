<?php

require_once(__DIR__ .'/../../Models/db.php');

$monthName = date('F');

$db = new DatabaseConnection();
$ramadan = get_option('ramadan-chbox');
if (! empty($ramadan)) {
    $data = $db->getPrayerTimeForRamadan();
    $monthName = 'Ramadan';
} else {
    $data = $db->getPrayerTimeForMonth(date('m'));
}
echo "<h3>Update Iqamah time for ". $monthName .", ". date('Y')  ."</h3>";

$prayerNames = $timetable->getLocalPrayerNames();

if ( empty($data)) {
    echo '<h4 class="green">Please Upload Timetable to use this page</h4>';
} else {

    echo "
        <div>
        <form name='quickUpdate' method='post'>
        <table class='quickUpdate'>
            <tr>
                <th>DATE</th>
                <th>DAY</th>
                <th>". $prayerNames['fajr'] ."</th>
                <th>". $prayerNames['zuhr'] ."</th>
                <th>". $prayerNames['asr'] ."</th>
                <th>". $prayerNames['maghrib'] ."</th>
                <th>". $prayerNames['isha'] ."</th>
            </tr>
    ";

    foreach ($data as $key => $value) {
        $date = $value['d_date'];
        $weekday = date("D", strtotime($date));
        echo "
            <tr>
                <td><b>". date("M d", strtotime($date)) ."</b></td>
                <td class=" . $weekday . "><b>". $weekday ."</b></td>

                <input type='hidden' name='thisMonth[".$key."][d_date]' value=". $date ." >
                <td><input type='time' name='thisMonth[".$key."][fajr_jamah]' value=". date('H:i', strtotime($value['fajr_jamah'])) ." ></td>
                <td><input type='time' name='thisMonth[".$key."][zuhr_jamah]' value=". date('H:i', strtotime($value['zuhr_jamah'])) ." ></td>
                <td><input type='time' name='thisMonth[".$key."][asr_jamah]' value=". date('H:i', strtotime($value['asr_jamah'])) ."></td>
                <td><input type='time' name='thisMonth[".$key."][maghrib_jamah]' value=". date('H:i', strtotime($value['maghrib_jamah'])) ." ></td>
                <td><input type='time' name='thisMonth[".$key."][isha_jamah]' value=". date('H:i', strtotime($value['isha_jamah'])) ." ></td>
            </tr>
        ";
    }
    echo "
            <tr>
                <td colspan='7' class='submit'>
                    <input type='submit' name='quickUpdate' id='quickUpdate' class='button button-primary' value='Update changes'>
                </td>
            </tr>
        </table>
        </form>
        </div>
    ";
}
