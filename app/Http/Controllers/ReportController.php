<?php

namespace App\Http\Controllers;

use App\Domain\Services\AssetsReportGenerator;
use Illuminate\Http\Request;
use App\Http\Resources\AssetsReportResource;

class ReportController extends Controller
{
    private $assetsReportGenerator;

    public function __construct(AssetsReportGenerator $assetsReportGenerator) {
        $this->assetsReportGenerator = $assetsReportGenerator;
    }

    public function assetsReport() {
        $report = $this->assetsReportGenerator->generateReport();
        return new AssetsReportResource($report);
    }
}
