<?php

namespace App\Domain\Services;

use App\Domain\ValueObjects\IncomeExpenseReport;
use App\Domain\ValueObjects\IncomeExpenseReportEntryKey;
use App\Repositories\IncomeExpenseAccountRepository;
use App\Domain\Utils\ReportUtils;
use App\Models\JournalEntry;
use App\Models\EntryType;
use Illuminate\Support\Carbon;
use DateTimeImmutable;
use Carbon\CarbonImmutable;

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
        $rangeStart = (new CarbonImmutable())->sub(6, 'months');
        $rangeEnd = new CarbonImmutable();
        $journalEntries = $this->fetchJournalEntries($rangeStart, $rangeEnd);
        $journalEntries = $this->groupByIncomeExpense($journalEntries, $rangeStart, $rangeEnd);

        $report = new IncomeExpenseReport();
        $expenseEntries = $this->groupByTransactionMonthYear($journalEntries['EXPENSE'], $rangeStart, $rangeEnd);
        array_map(function ($serializedKey, $entries) use ($report) {
            $totalSum = $this->sumUpJournalEntryAmounts($entries);
            $key = IncomeExpenseReportEntryKey::from($serializedKey);
            $report->setMonthlyExpense($key, $totalSum);
        }, array_keys($expenseEntries), $expenseEntries);

        $incomeEntries = $this->groupByTransactionMonthYear($journalEntries['INCOME'], $rangeStart, $rangeEnd);
        array_map(function ($serializedKey, $entries) use ($report) {
            $totalSum = $this->sumUpJournalEntryAmounts($entries);
            $key = IncomeExpenseReportEntryKey::from($serializedKey);
            $report->setMonthlyIncome($key, $totalSum);
        }, array_keys($incomeEntries), $incomeEntries);

        return $report;
    }

    private function fetchJournalEntries(DateTimeImmutable $rangeStart, DateTimeImmutable $rangeEnd)
    {
        $accountNames = array_merge(self::INCOME_ACCOUNT_NAMES, self::EXPENSE_ACCOUNT_NAMES);
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
        }, ['INCOME', 'EXPENSE']);

        return $result;
    }

    private function groupByTransactionMonthYear(array $journalEntries, DateTimeImmutable $rangeStart, DateTimeImmutable $rangeEnd)
    {
        $expectedMonths = $this->getExpectedTransactionMonthYearGroups($rangeStart, $rangeEnd);
        return ReportUtils::array_group($journalEntries, function (JournalEntry $entry) {
            $month = $entry->transaction->transaction_date->month;
            $year = $entry->transaction->transaction_date->year;
            return new IncomeExpenseReportEntryKey($month, $year);
        }, $expectedMonths);
    }

    private function getExpectedTransactionMonthYearGroups(DateTimeImmutable $rangeStart, DateTimeImmutable $rangeEnd)
    {
        $expectedMonths = [];
        $iterator = new Carbon($rangeStart);
        while ($iterator->month != $rangeEnd->month || $iterator->month != $rangeEnd->month) {
            $iterator->add(1, 'month');
            $expectedMonths[] = (string)(new IncomeExpenseReportEntryKey($iterator->month, $iterator->year));
        }

        return $expectedMonths;
    }

    private function sumUpJournalEntryAmounts(array $journalEntries)
    {
        return array_reduce($journalEntries, function ($sum, $entry) {
            if ($entry->transaction->type ==  $entry->account->type) {
                return $sum + $entry->amount;
            } else {
                return $sum - $entry->amount;
            }
        }, 0.0);
    }
}
