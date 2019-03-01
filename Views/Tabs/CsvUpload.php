<?php
    $adapter = new DatabaseConnection();
    $rows = $adapter->getRows();
    $header = (array_keys($rows[1]));
    $writeFile = plugin_dir_path( __FILE__ ) . '../../Assets/yearly-prayer-time.csv';
    try {
        $f = fopen($writeFile, "w");
        fputcsv($f, $header);
        foreach ($rows as $line) {
            fputcsv($f, array_values($line));
        }
        fclose($f);
        $readFile = plugins_url( '../../Assets/yearly-prayer-time.csv', __FILE__ );
    } catch (\Exception $e) {
        die($e->getMessage());
    }
?>
<h3>CSV Upload</h3>
<form enctype="multipart/form-data" name="csvUpload" method="post" action="">
            <span>
                <input type="file" name="timetable" id="timetable" accept=".csv">
            </span>
    <?php submit_button('Upload prayer time'); ?>
    <div>
        <ol>
            <li><a class="url" href="<?= $readFile ?>"> Download current timetable <i class="fa fa-cloud-download" aria-hidden="true"></i></a> (<i>do not change the column heading</i>)</li>
            <li><a class="url" target="_blank" href="https://www.salahtimes.com/search"> Get prayer start time for your city <i class="fa fa-external-link" aria-hidden="true"></i></a></li>
            <li>Valid date formats are:
                <ul class="red">
                    <li> - Y-M-D</li>
                    <li> - M/D/Y </li>
                    <li> - D-M-Y</li>
                    <li> - D.M.Y</li>
                </ul>
            </li>
            <li>Valid time format is <span class="red">HH:MM</span> [24 hours]</li>
            <li><a class="url" href="admin.php?page=helps-and-tips">Check Helps and Tips for usage <i class="fa fa-cogs" aria-hidden="true"></i></a></li>
        </ol>
    </div>
</form>
