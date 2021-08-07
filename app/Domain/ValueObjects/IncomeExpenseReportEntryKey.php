<?php

namespace App\Domain\ValueObjects;

use App\Domain\Utils\DateUtils;

class IncomeExpenseReportEntryKey
{

    /**
     * @var int (JAN=1, FEB=2, ..., DEC=12)
     */
    private $month;

    /**
     * @var int
     */
    private $year;

    public function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year=  $year;
    }

    /**
     * Get (JAN=1, FEB=2, ..., DEC=12)
     *
     * @return  int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Get the value of year
     *
     * @return  int
     */
    public function getYear()
    {
        return $this->year;
    }

    public function __toString()
    {
        return ($this->year).'-'.sprintf("%02d", $this->month);
    }

    public static function from(String $string)
    {
        $tokens = explode('-', $string);
        return new IncomeExpenseReportEntryKey((int)$tokens[1], (int)$tokens[0]);
    }
}
