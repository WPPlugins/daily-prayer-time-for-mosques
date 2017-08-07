<?php

class Validator
{
    /** @var array  */
    private $validData = array();

    /** @var array  */
    private $headers = array();

    /**
     * @param $file
     * @return bool
     */
    public function isValidNumberOfRows($file)
    {
        $count = count($file);
        $count -= 1; // Remove header count

        if ($count == 0 || $count > 366 ) {
            echo "<h3 class='ui-state-error dptCenter'>Invalid number of rows. Found $count days</h3>";
            return false;
        }

        return true;
    }

    /**
     * @param array $data [rows, containing date and each prayer times]
     * @return bool
     */
    public function isValidData(array $data)
    {
        if (! $this->hasEmptyLines($data)) {
            return false;
        }

        if (! $this->is24Hours($data)) {
            return false;
        }

        $num = count($data);

        for ($c=0; $c < $num - 1; $c++) {
            if ($c == 0) {
                $date = $this->getDateAfterValidation($data[$c]);
                if(! $date) {
                    echo "<h3 class='ui-state-error dptCenter'>Invalid Date format ($data[$c]). Please use one of the following:
                                <ul class='green'>
                                    <li> YYYY-MM-DD <i class='smallFont'>(2023-10-20)</i></li>
                                    <li> YY-MM-DD <i class='smallFont'>(23-10-20)</i></li>
                                    <li> MM/DD/YYYY <i class='smallFont'>(10/20/2021)</i></li>
                                    <li> DD-MM-YYYY <i class='smallFont'>(20-10-2023)</i></li>
                                    <li> DD.MM.YYYY <i class='smallFont'>(13.10.2034)</i></li>
                                </ul>
                        </h3>";
                    return false;
                } else {
                    $data[$c] = $date;
                }
            } else {
                if(! $this->isValidateTimeFormat($data[$c])) {
                    echo "<h3 class='ui-state-error dptCenter'>Invalid Time format in " . $data[$c] . " for " . $this->headers[$c] .
                        " on ". $data[0] .", valid time format is <span class='green'>HH:MM[24 hours]</span> </h3>";
                    print_r('<pre>');
                    print_r(array_combine($this->headers, $data));
                    print_r('</pre>');
                    return false;
                }
            }
        }
        $this->setValidData($data);

        return true;
    }

    /**
     * @return array
     */
    public function getValidData()
    {
        $data = array_combine($this->headers, $this->validData);

        return $data;
    }

    /**
     * @param array $header
     */
    public function setHeaders(array $header)
    {
        $this->headers = $header;
    }

    /**
     * @param string $date
     * @return string|bool
     */
    private function getDateAfterValidation($date)
    {
        $date = trim($date);
        if (preg_match("/^(\d{4})-(\d{1,2})-(\d{1,2})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return $date;
            }
        } elseif (preg_match("/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/", $date, $matches)) {
            if (checkdate($matches[1], $matches[2], $matches[3])) {
                $mysqlDate = date('Y-m-d', strtotime($date));
                return $mysqlDate;
            }
        } elseif (preg_match("/^(\d{1,2})-(\d{1,2})-(\d{4})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[1], $matches[3])) {
                $mysqlDate = date('Y-m-d', strtotime($date));
                return $mysqlDate;
            }
        } elseif (preg_match("/^(\d{1,2}).(\d{1,2}).(\d{4})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[1], $matches[3])) {
                $mysqlDate = date('Y-m-d', strtotime($date));
                return $mysqlDate;
            }
        } elseif (preg_match("/^(\d{1,2})-(\d{1,2})-(\d{2})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[1], $matches[3])) {
                $mysqlDate = date('Y-m-d', strtotime($date));
                return $mysqlDate;
            }
        }

        return false;
    }

    /**
     * @param string $time
     * @return bool
     */
    private function isValidateTimeFormat($time)
    {
        $time = trim($time);
        $pattern = "/^([0-9]|[01][0-9]|2[0-3]):[0-5][0-9]$/"; // HH:MM or H:MM

        if (! preg_match($pattern, $time, $matches)) {
            return false;
        }

        return true;
    }

    /**
     * @param  string $row
     * @return bool
     */
    private function is24Hours($row)
    {
        $afternoonPrayers = array($row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12]);
        foreach($afternoonPrayers as $time) {
            $firstPart = explode(':', $time);
            if ($firstPart[0] !== '00' && (int)$firstPart[0] < 12) {
                echo "<h3 class='dptCenter ui-state-error'>$time is not in 24 hour time format for date:  $row[0]
                        </br> You must follow 24 hours time format, valid time format is <span class='green'>HH:MM</span></h3>";
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $data
     */
    private function setValidData(array $data)
    {
        $this->validData = $data;
    }

    private function hasEmptyLines(array $data)
    {
        $data = trim($data[0]);
        if (empty($data)) {
            echo "<h3 class='ui-state-error dptCenter'>Please remove the empty lines between rows</h3>";
            return false;
        }

        return true;
    }
}
