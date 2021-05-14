<?php

namespace App\Domain\Services;

use App\Domain\ValueObjects\AssetsReport;
use App\Repositories\AssetJournalEntryAccountRepository;

class AssetsReportGenerator {

    public function __construct(AssetJournalEntryAccountRepository $assetJournalEntryAccountRepository) {
        $this->assetJournalEntryAccountRepository = $assetJournalEntryAccountRepository;
    }

    public function generateReport() {
        $assetGroups = $this->assetJournalEntryAccountRepository->fetchAssetGroups();

        $report =  new AssetsReport();
        $report->setGroups($assetGroups);

        return $report;
    }
}
