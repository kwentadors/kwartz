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
        $accounts = array_merge($this->getDebitAccounts(), $this->getCreditAccounts());

        foreach ($accounts as $account) {
            DB::table('financial_accounts')->insert([
                'name' => $account['name'],
                'account_code' => $account['account_code'],
                'entry_type' => $account['entry_type']
            ]);
        }
    }

    private function getDebitAccounts() {
        return array_map(function($details) {
            return [
                'name'  => $details[0],
                'account_code'  => $details[1],
                'entry_type' => EntryType::DEBIT
            ];
        }, [
            ['Savings - BDO', '1010'],
            ['Savings - Unionbank', '1021'],
            ['Savings - Metrobank', '1022'],
            ['Savings - CIMB', '1023'],
            ['Travel Fund - Unionbank', '1024'],
            ['Savings - BPI', '1025'],
            ['Checkings - Unionbank', '1031'],
            ['Stock Market - COL', '1041'],
            ['Stock Market - First Metro Sec', '1044'],
            ['P2P - SeedIn', '1042'],
            ['MP2 Investment', '1043'],

            ['Accounts Receivable', '1210'],

            ['Miscellaneous Assets', '1399'],

            ['Condo - Mivela T2U903', '1501'],

            ['Rent Fund - Unionbank', '2010'],

            // EXPENSE
            ['Expense - Rent', '5010'],
            ['Expense - Electricity', '5020'],
            ['Expense - Water', '5030'],
            ['Expense - Internet', '5040'],
            ['Expense - Personal', '5050'],
            ['Expense - Household', '5060'],
            ['Expense - Netflix', '5070'],
            ['Expense - FWD Insurance', '5080'],
            ['Expense - Bank Charges', '5090'],
            ['Expense - Learning Materials', '5098'],
            ['Expense - Miscellaneous', '5099'],
        ]);
    }

    private function getCreditAccounts() {
        return array_map(function($details) {
            return [
                'name'  => $details[0],
                'account_code'  => $details[1],
                'entry_type' => EntryType::CREDIT
            ];
        }, [
            ['Income - Arcanys', '4010'],
            ['Income - APT', '4011'],
            ['Income - Stock Market', '4012'],
            ['Income - Eve P2P', '4013'],
            ['Incentives - Arcanys', '4021'],
            ['Incentives - Bank', '4022'],
            ['Incentives - Miscellaneous', '4099'],

            ['Owner\'s Equity', '6000']
        ]);
    }
}
