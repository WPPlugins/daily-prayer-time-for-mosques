<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

class DatabaseConnection
{
    /** @var string  */
    private $dbTable = "";

    /** @var string */
    private $tableName = "";


    public function __construct()
    {
        global $wpdb;

        $this->tableName = $wpdb->prefix . "timetable";
        $this->dbTable = "`".DB_NAME ."`." .$this->tableName;

        $this->createTableIfNotExist();
    }

    /**
     * @return array
     */
    public function getPrayerTimeForToday()
    {
        global $wpdb;

        $today = user_current_time( 'Y-m-d' );
        $sql = "SELECT * FROM  $this->dbTable WHERE d_date = '$today' LIMIT 1";
        $result = $wpdb->get_row($sql, ARRAY_A);

        return $result;
    }

    /**
     * @param int $monthNumber
     * @return array
     */
    public function getPrayerTimeForMonth($monthNumber)
    {
        global $wpdb;

        $sql = "SELECT * FROM  $this->dbTable WHERE month(d_date) = $monthNumber AND YEAR(d_date) = YEAR(CURDATE()) ORDER BY d_date ASC";
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
    }

    /**
     * @return array
     */
    public function getPrayerTimeForRamadan()
    {
        global $wpdb;

        $sql = "SELECT * FROM  $this->dbTable WHERE is_ramadan = 1 AND YEAR(d_date) = YEAR(CURDATE()) ORDER BY d_date ASC";
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
    }

    /**
     * @param array $row
     * @return int|bool
     */
    public function insertRow($row)
    {
        global $wpdb;

        $createIfNotUpdate = "
        INSERT INTO " .$this->dbTable. "
            (
            d_date,
            fajr_begins,
            fajr_jamah,
            sunrise,
            zuhr_begins,
            zuhr_jamah,
            asr_mithl_1,
            asr_mithl_2,
            asr_jamah,
            maghrib_begins,
            maghrib_jamah,
            isha_begins,
            isha_jamah,
            is_ramadan
            )
        VALUES
            (
            '" .$row['d_date']. "','" .
            $row['fajr_begins']. "','" .
            $row['fajr_jamah']. "','" .
            $row['sunrise']. "','" .
            $row['zuhr_begins']. "','" .
            $row['zuhr_jamah']. "','" .
            $row['asr_mithl_1']. "','" .
            $row['asr_mithl_2']. "','" .
            $row['asr_jamah']. "','" .
            $row['maghrib_begins']. "','" .
            $row['maghrib_jamah']. "','" .
            $row['isha_begins']. "','" .
            $row['isha_jamah']. "','" .
            $row['is_ramadan']. "'
            )
        ON DUPLICATE KEY UPDATE
            d_date = '"         . $row['d_date'] ."',
            fajr_begins = '"    . $row['fajr_begins'] ."',
            fajr_jamah = '"     . $row['fajr_jamah'] ."',
            sunrise = '"        . $row['sunrise'] ."',
            zuhr_begins = '"    . $row['zuhr_begins'] ."',
            zuhr_jamah = '"     . $row['zuhr_jamah'] ."',
            asr_mithl_1 = '"    . $row['asr_mithl_1'] ."',
            asr_mithl_2 = '"    . $row['asr_mithl_2'] ."',
            asr_jamah = '"      . $row['asr_jamah'] ."',
            maghrib_begins = '" . $row['maghrib_begins'] ."',
            maghrib_jamah = '"  . $row['maghrib_jamah'] ."',
            isha_begins = '"    . $row['isha_begins'] ."',
            isha_jamah = '"     . $row['isha_jamah'] . "',
            is_ramadan = '"     . $row['is_ramadan'] . "';";

        return $wpdb->query($createIfNotUpdate);
    }

    private function createTableIfNotExist()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE " . $this->dbTable. "(
                d_date date NOT NULL,
                fajr_begins time DEFAULT NULL,
                fajr_jamah time DEFAULT NULL,
                sunrise time DEFAULT NULL,
                zuhr_begins time DEFAULT NULL,
                zuhr_jamah time DEFAULT NULL,
                asr_mithl_1 time DEFAULT NULL,
                asr_mithl_2 time DEFAULT NULL,
                asr_jamah time DEFAULT NULL,
                maghrib_begins time DEFAULT NULL,
                maghrib_jamah time DEFAULT NULL,
                isha_begins time DEFAULT NULL,
                isha_jamah time DEFAULT NULL,
                is_ramadan SMALLINT DEFAULT NULL,
                PRIMARY KEY  (d_date)
                ) $charset_collate;";

        $wpdb->get_var("SHOW TABLES LIKE '". $this->tableName . "'");
        if($wpdb->num_rows != 1) {
            dbDelta( $sql );
        }
    }

    public function updateRow($monthData)
    {
        global $wpdb;

        foreach ($monthData as $day) {
            $wpdb->update(
                $this->tableName,
                array(
                    'fajr_jamah' => $day['fajr_jamah'],
                    'zuhr_jamah' => $day['zuhr_jamah'],
                    'asr_jamah' => $day['asr_jamah'],
                    'maghrib_jamah' => $day['maghrib_jamah'],
                    'isha_jamah' => $day['isha_jamah']
                ),
                array('d_date' => $day['d_date'])
            );
        }
    }
}


function user_current_time($format="")
{
    $format = $format ? $format : 'mysql';
    $result = current_time($format);
    if (empty($result)) {
        $result =  date( $format, time() + ( get_option( 'gmt_offset' ) * 60 ) );
    }

    return $result;
}
