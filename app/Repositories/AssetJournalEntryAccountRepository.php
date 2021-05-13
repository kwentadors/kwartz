<?php
namespace App\Repositories;

use App\Models\FinancialAccount;
use App\Models\EntryType;
use App\Domain\ValueObjects\AssetsReportAggregate;
use App\Domain\ValueObjects\AssetsReportGroup;
use App\Domain\ValueObjects\AssetsReportGroupEntry;
use Datetime;

class AssetJournalEntryAccountRepository
{
    public function fetchAssetGroups()
    {
        $assetGroups = [
            'Cash equivalents' => [
                'Savings - BDO',
                'Savings - Unionbank',
                'Savings - Metrobank',
                'Savings - CIMB',
                'Travel Fund - Unionbank',
                'Savings - BPI',
                'Checkings - Unionbank',
            ],
            'Paper investments' => [
                'Stock Market - COL',
                'Stock Market - First Metro Sec',
                'P2P - SeedIn',
                'MP2 Investment',
            ],
            'Receivable' => [
                'Accounts Receivable',
            ],
            'Property' => [
                'Miscellaneous Assets',
                'Condo - Mivela T2U903',
            ],
        ];

        $groups = [];
        $groupId = 1;
        foreach ($assetGroups as $name => $entries) {
            $group = new AssetsReportGroup();
            $group->setId($groupId++);
            $group->setName($name);
            $group->addAllEntries($this->buildGroupEntries($entries));

            $groups[] = $group;
        }
        return $groups;
    }

    private function buildGroupEntries($entryNames)
    {
        $accounts = FinancialAccount::whereIn('name', $entryNames)->get()->all();

        return array_map(function ($account) {
            $entry = new AssetsReportGroupEntry();
            $entry->setId($account->id);
            $entry->setName($account->name);
            $entry->setCurrentBalance($this->computeTotalBalance($account));
            $entry->setPreviousBalance($this->computePreviousBalance($account));

            return $entry;
        }, $accounts);
    }

    private function computeTotalBalance($asset)
    {
        return $asset->balance();
    }

    private function computePreviousBalance($asset)
    {
        $journalEntries = collect($asset->journalEntries)
            ->filter(function ($journalEntry, $_) {
                $currentMonthFirstDay = new DateTime(date('Y-m-01'));
                $transactionDate = new DateTime($journalEntry->transaction->transaction_date);
                return $transactionDate < $currentMonthFirstDay;
            });

        $debitAmount = $journalEntries
            ->where('type', EntryType::DEBIT)
            ->sum('amount');

        $creditAmount = $journalEntries
                ->where('type', EntryType::CREDIT)
                ->sum('amount');

        if ($asset->entry_type == EntryType::DEBIT) {
            return $debitAmount - $creditAmount;
        } else {
            return $creditAmount - $debitAmount;
        }
    }
}
