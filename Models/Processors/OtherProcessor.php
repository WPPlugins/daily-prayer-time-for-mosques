<?php

class OtherProcessor
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
    }

    public function process()
    {
        $ramadan = ($_POST['ramadan-chbox']);
        delete_option('ramadan-chbox');
        add_option('ramadan-chbox', $ramadan);

        if (! empty($_POST['asrSelect'])) {
            $asrSelect = ($_POST['asrSelect']);
            delete_option('asrSelect');
            add_option('asrSelect', $asrSelect);
        }
    }
}
