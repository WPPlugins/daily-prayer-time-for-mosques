<?php

class HijriProcessor
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function process()
    {
        $hijri = $_POST['hijri-chbox'];
        delete_option('hijri-chbox');
        add_option('hijri-chbox', $hijri);

        $hijriAdjust = $_POST['hijri-adjust'];
        delete_option('hijri-adjust');
        add_option('hijri-adjust', $hijriAdjust);
    }
}
