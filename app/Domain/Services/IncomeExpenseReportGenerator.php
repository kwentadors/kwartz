<?php

namespace App\Domain\Services;

use App\Domain\ValueObjects\IncomeExpenseReport;
use App\Domain\ValueObjects\IncomeExpenseReportEntry;
use App\Domain\ValueObjects\IncomeExpenseReportEntryKey;

class IncomeExpenseReportGenerator
{
    public function generateReport()
    {
        $report = new IncomeExpenseReport();

        $julyEntry = (new IncomeExpenseReportEntry(
            new IncomeExpenseReportEntryKey(7, 2021)
        ))
            ->setIncome(24220.11)
            ->setExpense(18219.52);

        $juneEntry = (new IncomeExpenseReportEntry(
            new IncomeExpenseReportEntryKey(6, 2021)
        ))
            ->setExpense(17656.87);

        $report->addMonthlyEntry($julyEntry);
        $report->addMonthlyEntry($juneEntry);

        return $report;
    }
}
