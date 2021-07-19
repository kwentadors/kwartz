<?php

namespace App\Domain\ValueObjects;

class IncomeExpenseReport
{

    /**
     * @var IncomeExpenseReportEntry[]
     */
    private $monthlyEntries = [];


    /**
     * Get the value of monthlyEntries
     */
    public function getMonthlyEntries()
    {
        return $this->monthlyEntries;
    }

    /**
     * Set the value of monthlyEntries
     *
     * @return  self
     */
    public function addMonthlyEntry(IncomeExpenseReportEntry $monthlyEntries)
    {
        $this->monthlyEntries[] = $monthlyEntries;

        return $this;
    }
}
