<?php

namespace App\Http\Controllers;

use App\Domain\Services\AssetsReportGenerator;
use App\Domain\Services\IncomeExpenseReportGenerator;
use App\Http\Resources\AssetsReportResource;
use App\Http\Resources\IncomeExpenseResource;

class ReportController extends Controller
{
    private $assetsReportGenerator;
    private $incomeExpenseReportGenerator;

    public function __construct(
        AssetsReportGenerator $assetsReportGenerator,
        IncomeExpenseReportGenerator $incomeExpenseReportGenerator
    ) {
        $this->assetsReportGenerator = $assetsReportGenerator;
        $this->incomeExpenseReportGenerator = $incomeExpenseReportGenerator;
    }

    public function assetsReport()
    {
        $report = $this->assetsReportGenerator->generateReport();
        return new AssetsReportResource($report);
    }

    public function incomeExpenseReport()
    {
        $report = $this->incomeExpenseReportGenerator->generateReport();
        return new IncomeExpenseResource($report);
    }
}
