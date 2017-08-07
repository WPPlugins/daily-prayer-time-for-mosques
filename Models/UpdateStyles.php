<?php
class UpdateStyles
{
    /** @var string */
    private $handle;

    function __construct($handle)
    {
        $this->handle = $handle;

        $this->setScript();
        $this->setStyles();
    }

    private function setScript()
    {
        add_action( 'admin_enqueue_scripts', 'dpt_add_color_picker' );
        function dpt_add_color_picker( $hook ) {

            if( is_admin() ) {

                // Add the color picker css file
                wp_enqueue_style( 'wp-color-picker' );

                // Include our custom jQuery file with WordPress Color Picker dependency
                wp_enqueue_script(
                    'custom-script-handle',
                    plugins_url( '/../Assets/js/wp-color-picker.js', __FILE__ ),
                    array( 'wp-color-picker' ),
                    '4.0.0'
                );
            }
        }
    }

    private function setStyles()
    {
        $tableBackground = get_option('tableBackground');
        if (! empty($tableBackground)) {
            $css = "
                table.customStyles {
                    background-color: ". $tableBackground
                ."}";
        }

        $tableHeading = get_option('tableHeading');
        if (! empty($tableHeading)) {
            $css .= "
                table.customStyles th.tableHeading{
                    background:" . get_option('tableHeading')
                ."}";
        }

        $notificationBackground = get_option('notificationBackground');
        if (! empty($notificationBackground)) {
            $css .= "
                table.customStyles th.notificationBackground{
                    background:" . get_option('notificationBackground')
                ."}";
        }

        $evenRow = get_option('evenRow');
        if (! empty($evenRow)) {
            $css .= "
                table.customStyles tr:nth-child(even) {
                    background:" . get_option('evenRow')
                ."}";
        }

        $fontColor = get_option('fontColor');
        if (! empty($fontColor)) {
            $css .= "
                table.customStyles {
                    color:" . get_option('fontColor')
                ."}";
        }

        $highlight = get_option('highlight');
        if (! empty($highlight)) {
            $css .= "
                table.customStyles tr.highlight, th.highlight, td.highlight{
                    font-weight: bold;
                    background:" . get_option('highlight') ."!important"
                ."}";
        }

        wp_add_inline_style( $this->handle, $css );
    }
}