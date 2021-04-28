<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\EntryType;

class FinancialAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = $this->getDebitAccounts() + $this->getCreditAccounts();

        foreach ($accounts as $account) {
            DB::table('financial_accounts')->insert([
                'name' => $account['name'],
                'entry_type' => $account['entry_type']
            ]);
        }
    }

    private function getDebitAccounts() {
        return array_map(function($accountName) {
            return [
                'name'  => $accountName,
                'entry_type' => EntryType::DEBIT
            ];
        }, [
            'Savings - BDO',
            'Savings - Unionbank',
            'Savings - Metrobank',
            'Savings - CIMB',
            'Travel Fund - Unionbank',
            'Savings - BPI',
            'Checkings - Unionbank',
            'Stock Market - COL',
            'Stock Market - First Metro Sec',
            'P2P - SeedIn',
            'MP2 Investment',

            'Accounts Receivable',

            'Miscellaneous Assets',

            'Condo - Mivela T2U903',

            'Rent Fund - Unionbank',

            // EXPENSE
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
            'Expense - Miscellaneous',
        ]);
    }

    private function getCreditAccounts() {
        return array_map(function($accountName) {
            return [
                'name'  => $accountName,
                'entry_type' => EntryType::CREDIT
            ];
        }, [
            'Income - Arcanys',
            'Income - APT',
            'Income - Stock Market',
            'Income - Eve P2P',
            'Incentives - Arcanys',
            'Incentives - Bank',
            'Incentives - Miscellaneous',

            'Owner\'s Equity'
        ]);
    }
}
