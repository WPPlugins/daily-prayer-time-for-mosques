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
        $ramadan = ($this->data['ramadan-chbox']);
        delete_option('ramadan-chbox');
        add_option('ramadan-chbox', $ramadan);

        if (! empty($this->data['asrSelect'])) {
            $asrSelect = ($this->data['asrSelect']);
            delete_option('asrSelect');
            add_option('asrSelect', $asrSelect);
        }

        $jamahChanges = ($this->data['jamah_changes']);
        delete_option('jamah_changes');
        add_option('jamah_changes', $jamahChanges);
    }
}
