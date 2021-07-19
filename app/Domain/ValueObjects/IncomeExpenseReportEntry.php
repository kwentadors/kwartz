<?php

namespace App\Domain\ValueObjects;

class IncomeExpenseReportEntry
{

    /**
     * @var int (JAN=1, FEB=2, ..., DEC=12)
     */
    private $month;

    /**
     * @var float
     */
    private $income;

    /**
     * @var float
     */
    private $expense;


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
     * Set (JAN=1, FEB=2, ..., DEC=12)
     *
     * @param  int  $month  (JAN=1, FEB=2, ..., DEC=12)
     *
     * @return  self
     */
    public function setMonth(int $month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get the value of income
     *
     * @return  float
     */
    public function getIncome()
    {
        return $this->income;
    }

    /**
     * Set the value of income
     *
     * @param  float  $income
     *
     * @return  self
     */
    public function setIncome(float $income)
    {
        $this->income = $income;

        return $this;
    }

    /**
     * Get the value of expense
     *
     * @return  float
     */
    public function getExpense()
    {
        return $this->expense;
    }

    /**
     * Set the value of expense
     *
     * @param  float  $expense
     *
     * @return  self
     */
    public function setExpense(float $expense)
    {
        $this->expense = $expense;

        return $this;
    }
}
