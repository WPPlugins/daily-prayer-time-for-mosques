<?php
/*
Plugin Name: Daily Prayer Time
Version: 4.2.6
Plugin URI: https://wordpress.org/plugins/daily-prayer-time-for-mosques/
Description: Display yearly, monthly and daily prayer time, ramadan time vertically or horizontally, in any language
Author: mmrs151, Hjeewa
Author URI: http://mmrs151.wordpress.com
*/
require_once('Models/DailyTimeTable.php');
require_once('Models/MonthlyShortCode.php');
require_once('Models/UpdateStyles.php');

class DailyPrayerTime extends WP_Widget
{
    public function __construct()
    {
        $widget_details = array(
            'className' => 'DailyPrayerTime',
            'description' => 'Show daily prayer time vertically or horizontally'
        );
        $this->add_stylesheet();
        $this->add_scripts();

        parent::__construct('DailyPrayerTime', 'Daily Prayer Time', $widget_details);
    }

    public function form($instance)
    {
        include 'Views/dpaWidgetForm.php';
        ?>

        <div class='mfc-text'>

        </div>

        <?php

        echo $args['after_widget'];
        echo "<a href='http://www.uwt.org/' target='_blank'>Support The Ummah</a></br></br>";
    }

    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        include 'Models/dptWidget.php';

        echo $args['after_widget'];
    }

    private function add_stylesheet() {
        wp_register_style( 'timetable-style', plugins_url('Assets/css/styles.css', __FILE__) );
        wp_enqueue_style( 'timetable-style' );

        wp_register_style( 'verge-style', plugins_url('Assets/css/vergestyles.css', __FILE__) );
        wp_enqueue_style( 'verge-style' );

        wp_register_style( 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css' );
        wp_enqueue_style( 'jquery-ui' );

        new UpdateStyles('timetable-style');
    }

    private function add_scripts()
    {
        wp_enqueue_script("jquery-ui",'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array('jquery'), '1.8.8');
        wp_enqueue_script("jquery-cookie",'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js', array('jquery'), '1.4.1');
        wp_enqueue_script("jquery-blockUI",'https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js', array('jquery'), '2.7.0');
        wp_enqueue_script('dpt-admin', plugins_url( '/Assets/js/dpt-admin.js', __FILE__ ), array( 'jquery' ), '4.0.0');
    }

}

add_action('widgets_init', 'init_dpt_widget');
function init_dpt_widget()
{
    register_widget('DailyPrayerTime');
}
############################# END OF WIDGET ############################################

#============================= SHORTCODE =================================================
$monthlyShortCode = new MonthlyShortCode();
add_shortcode( 'monthlytable', array($monthlyShortCode, 'printMonthlyTimeTable') );

$dailyShortCode = new DailyTimeTable();
add_shortcode( 'monthlytable_daily', array($dailyShortCode, 'verticalTime') );
add_shortcode( 'monthlytable_daily_horizontal', array($dailyShortCode, 'horizontalTime') );
add_shortcode( 'daily_next_prayer', array($dailyShortCode, 'scNextPrayer') );
add_shortcode( 'display_ramadan_time', array($dailyShortCode, 'scRamadanTime') );

#============================= MENU PAGES =========================================== #
add_action( 'admin_menu', "prayer_settings");
function prayer_settings()
{
    add_menu_page(
        'Daily Prayer Time',
        'Prayer time',
        'manage_options',
        'daily-prayer-time-for-mosques/widget-admin.php',
        '',
        plugins_url( 'Assets/images/icon.png', __FILE__ )
    );
    add_submenu_page('daily-prayer-time-for-mosques/widget-admin.php', 'Settings', 'Settings', 'manage_options', 'daily-prayer-time-for-mosques/widget-admin.php');
    add_submenu_page('daily-prayer-time-for-mosques/widget-admin.php', 'Helps and Tips', 'Helps and Tips', 'manage_options', 'helps-and-tips', 'helps_and_tips');

    function helps_and_tips()
    {
        include('Views/HelpsAndTips.php');
    }
}

#============================ DEACTIVATION =========================================== #
register_deactivation_hook( __FILE__, 'pluginUninstall' );
function pluginUninstall() {


    global $wpdb;
    $table = $wpdb->prefix."timetable";

    delete_option('asrSelect');
    delete_option('prayersLocal');
    delete_option('headersLocal');
    delete_option('numbersLocal');
    delete_option('monthsLocal');
    delete_option('ramadan-chbox');
    delete_option('timesLocal');
    delete_option('hijri-chbox');
    delete_option('hijri-adjust');
    delete_option('tableBackground');
    delete_option('tableHeading');
    delete_option('evenRow');
    delete_option('fontColor');
    delete_option('highlight');

    $wpdb->query("DROP TABLE IF EXISTS $table");
}
