<?php

namespace App\Domain\ValueObjects;

class IncomeExpenseReportEntry
{

    /**
     * @var float
     */
    private $income;

    /**
     * @var float
     */
    private $expense;

    /**
     * @var IncomeExpenseReportEntryKey
     */
    private $key;


    public function __construct(IncomeExpenseReportEntryKey $key)
    {
        $this->key = $key;
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

    /**
     * Get the value of key
     *
     * @return  IncomeExpenseReportEntryKey
     */
    public function getKey()
    {
        return $this->key;
    }
}
