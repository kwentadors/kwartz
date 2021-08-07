<?php

namespace App\Domain\Services;

use App\Domain\ValueObjects\IncomeExpenseReport;
use App\Domain\ValueObjects\IncomeExpenseReportEntryKey;
use App\Repositories\IncomeExpenseAccountRepository;
use App\Domain\Utils\ReportUtils;
use App\Models\JournalEntry;
use App\Models\EntryType;

use DateTime;

class IncomeExpenseReportGenerator
{
    /**
     * @var IncomeExpenseAccountRepository
     */
    private $incomeExpenseAccountRepository;

    const INCOME_ACCOUNT_NAMES = [
        'Income - Arcanys',
        'Income - APT',
        'Income - Stock Market',
        'Income - Eve P2P',
        'Incentives - Arcanys',
        'Incentives - Bank',
        'Incentives - Miscellaneous'
    ];

    const EXPENSE_ACCOUNT_NAMES = [
        'Expense - Rent',
        'Expense - Electricity',
        'Expense - Water',
        'Expense - Internet',
        'Expense - Personal',
        'Expense - Household',
        'Expense - Netflix',
        'Expense - FWD Insurance',
        'Expense - Bank Charges',
        'Expense - Learning Materials',
        'Expense - Miscellaneous'
    ];

    public function __construct(IncomeExpenseAccountRepository $incomeExpenseAccountRepository)
    {
        $this->incomeExpenseAccountRepository = $incomeExpenseAccountRepository;
    }

    public function generateReport()
    {
        $journalEntries = $this->fetchJournalEntries();
        $journalEntries = $this->groupByIncomeExpense($journalEntries);

        $report = new IncomeExpenseReport();
        $expenseEntries = $this->groupByTransactionMonthYear($journalEntries['EXPENSE']);
        array_map(function ($serializedKey, $entries) use ($report) {
            $totalSum = $this->sumUpJournalEntryAmounts($entries);
            $key = IncomeExpenseReportEntryKey::from($serializedKey);
            $report->setMonthlyExpense($key, $totalSum);
        }, array_keys($expenseEntries), $expenseEntries);

        $incomeEntries = $this->groupByTransactionMonthYear($journalEntries['INCOME']);
        array_map(function ($serializedKey, $entries) use ($report) {
            $totalSum = -$this->sumUpJournalEntryAmounts($entries);
            $key = IncomeExpenseReportEntryKey::from($serializedKey);
            $report->setMonthlyIncome($key, $totalSum);
        }, array_keys($incomeEntries), $incomeEntries);

        return $report;
    }

    private function fetchJournalEntries()
    {
        $accountNames =self::INCOME_ACCOUNT_NAMES + self::EXPENSE_ACCOUNT_NAMES;
        $rangeStart = new DateTime('2021-06-07');
        $rangeEnd = new DateTime();
        return $this->incomeExpenseAccountRepository->fetchIncomeRelatedAccounts(
            $accountNames,
            $rangeStart,
            $rangeEnd
        );
    }

    private function groupByIncomeExpense(array $journalEntries)
    {
        $result = ReportUtils::array_group($journalEntries, function (JournalEntry $journalEntry) {
            if (in_array($journalEntry->account->name, self::INCOME_ACCOUNT_NAMES)) {
                return 'INCOME';
            }

            if (in_array($journalEntry->account->name, self::EXPENSE_ACCOUNT_NAMES)) {
                return 'EXPENSE';
            }
        });

        if(!array_key_exists('INCOME', $result)) $result['INCOME'] = [];
        if(!array_key_exists('EXPENSE', $result)) $result['EXPENSE'] = [];

        return $result;
    }

    private function groupByTransactionMonthYear(array $journalEntries)
    {
        return ReportUtils::array_group($journalEntries, function (JournalEntry $entry) {
            $month = $entry->transaction->transaction_date->month;
            $year = $entry->transaction->transaction_date->year;
            return new IncomeExpenseReportEntryKey($month, $year);
        });
    }

    private function sumUpJournalEntryAmounts(array $journalEntries)
    {
        return array_reduce($journalEntries, function ($sum, $entry) {
            if ($entry->transaction->type == EntryType::DEBIT) {
                return $sum + $entry->amount;
            } else {
                return $sum - $entry->amount;
            }
        });
    }
}
