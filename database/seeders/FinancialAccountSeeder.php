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
        $accounts = [
            [ 'name' => 'Cash', 'entryType' => EntryType::DEBIT ],
            [ 'name' => 'Accounts Receivable', 'entryType' => EntryType::DEBIT ],
            [ 'name' => 'Accounts Payable', 'entryType' => EntryType::CREDIT ],
            [ 'name' => 'Owner\'s Capital', 'entryType' => EntryType::CREDIT ],
            [ 'name' => 'Expense - Utility', 'entryType' => EntryType::CREDIT ],
        ];

        foreach ($accounts as $account) {
            DB::table('financial_accounts')->insert([
                'name' => $account['name'],
                'entryType' => $account['entryType']
            ]);
        }

    }
}
