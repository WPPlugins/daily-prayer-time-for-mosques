<?php

class LanguageProcessor
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data) {
        $this->data = $data;
        error_log(print_r($this->data, 1));
    }

    public function proocess()
    {
        if (! empty($_POST['prayersLocal'])) {
            $prayersLocal = $_POST['prayersLocal'];
            delete_option('prayersLocal');
            add_option('prayersLocal', $prayersLocal);
        }

        if (! empty($_POST['headersLocal'])) {
            $headersLocal = $_POST['headersLocal'];
            delete_option('headersLocal');
            add_option('headersLocal', $headersLocal);
        }

        if (! empty($_POST['monthsLocal'])) {
            $monthsLocal = $_POST['monthsLocal'];
            delete_option('monthsLocal');
            add_option('monthsLocal', $monthsLocal);
        }

        if ( ! empty($_POST['numbersLocal'])) {
            $numbersLocal = $_POST['numbersLocal'];
            delete_option('numbersLocal');
            add_option('numbersLocal', $numbersLocal);
        }

        if ( ! empty($_POST['timesLocal'])) {
            $timesLocal = $_POST['timesLocal'];
            delete_option('timesLocal');
            add_option('timesLocal', $timesLocal);
        }
    }
}
