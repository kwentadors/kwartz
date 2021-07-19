<?php

namespace App\Domain\Services;

use App\Domain\ValueObjects\IncomeExpenseReport;
use App\Domain\ValueObjects\IncomeExpenseReportEntry;

class IncomeExpenseReportGenerator
{
    public function generateReport()
    {
        $report = new IncomeExpenseReport();

        $julyEntry = (new IncomeExpenseReportEntry())
            ->setMonth(7)
            ->setIncome(24220.11)
            ->setExpense(18219.52);

        $juneEntry = (new IncomeExpenseReportEntry())
            ->setMonth(6)
            ->setExpense(17656.87);

        $report->addMonthlyEntry($julyEntry);
        $report->addMonthlyEntry($juneEntry);

        return $report;
    }
}
