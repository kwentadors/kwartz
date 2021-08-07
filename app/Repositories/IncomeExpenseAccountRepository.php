<?php

namespace App\Repositories;

use App\Models\JournalEntry;
use App\Models\FinancialAccount;
use App\Models\EntryType;

use Illuminate\Support\Facades\DB;
use DateTimeImmutable;

class IncomeExpenseAccountRepository
{
    public function fetchIncomeRelatedAccounts(array $accountNames, DateTimeImmutable $startDate, DateTimeImmutable $endDate)
    {
        $accounts = FinancialAccount::whereIn('name', $accountNames);
        $journalEntries = $this->getJournalEntriesWithinDate($accounts, $startDate, $endDate);

        return $journalEntries;
    }

    private function getJournalEntriesWithinDate($accounts, $startDate, $endDate)
    {
        $results = JournalEntry::select('journal_entries.*')
                        ->with('account')
                        ->join('transactions', 'transaction_id', '=', 'transactions.id')
                        ->whereIn('account_id', array_map(function ($account) {
                            return $account->id;
                        }, $accounts->get()->all()))
                        ->whereBetween('transaction_date', [$startDate, $endDate])
                        ->get()->all();

        return $results;
    }

    private function groupByAccount($journalEntries)
    {
        return array_reduce($journalEntries, function ($result, $entry) {
            $group = $entry->account->id;
            if (!array_key_exists($group, $result)) {
                $acc[$group] = [];
            }
            $result[$group][] = $entry;

            return $result;
        }, []);
    }

    private function sumUpAmount($groupedJournalEntries)
    {
        $result = array_reduce(array_keys($groupedJournalEntries), function ($result, $id) {
            $result[$id] = 0;
            return $result;
        }, []);


        foreach ($groupedJournalEntries as $groupId => $entries) {
            $result[$groupId] = $this->sumUpEntries($entries);
        }

        return $result;
    }

    private function sumUpEntries($entries)
    {
        return array_reduce($entries, function ($sum, $entry) {
            if ($entry->type == EntryType::DEBIT) {
                return $sum +$entry->amount;
            } else {
                return $sum- $entry->amount;
            }
        }, 0);
    }
}
