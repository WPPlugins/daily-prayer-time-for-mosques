<?php

class ThemeSettingsProcessor
{
    /** @var array */
    private $data;

    /**
     * @param array $data
     */
    function __construct(array $data)
    {
        $this->data = $data;
    }

    public function process()
    {
        $hideTableBorder = $_POST['hideTableBorder'];
        delete_option('hideTableBorder');
        add_option('hideTableBorder', $hideTableBorder);

        $tableBackground = $_POST['tableBackground'];
        delete_option('tableBackground');
        add_option('tableBackground', $tableBackground);

        $tableHeading = $_POST['tableHeading'];
        delete_option('tableHeading');
        add_option('tableHeading', $tableHeading);

        $evenRow = $_POST['evenRow'];
        delete_option('evenRow');
        add_option('evenRow', $evenRow);

        $fontColor = $_POST['fontColor'];
        delete_option('fontColor');
        add_option('fontColor', $fontColor);

        $highlight = $_POST['highlight'];
        delete_option('highlight');
        add_option('highlight', $highlight);

        $notificationBackground = $_POST['notificationBackground'];
        delete_option('notificationBackground');
        add_option('notificationBackground', $notificationBackground);
    }
}