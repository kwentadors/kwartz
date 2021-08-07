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

    public function setMonthlyIncome($key, float $income)
    {
        $entry = $this->findEntry($key);
        if ($entry == null) {
            $entry = new IncomeExpenseReportEntry($key);
            $this->monthlyEntries[] = $entry;
        }
        $entry->setIncome($income);
    }

    public function setMonthlyExpense($key, float $expense)
    {
        $entry = $this->findEntry($key);
        if ($entry == null) {
            $entry = new IncomeExpenseReportEntry($key);
            $this->monthlyEntries[] = $entry;
        }
        $entry->setExpense($expense);
    }

    private function findEntry($entryKey)
    {
        foreach ($this->monthlyEntries as $entry) {
            if ($entry->getKey() == $entryKey) {
                return $entry;
            }
        }

        return null;
    }
}
