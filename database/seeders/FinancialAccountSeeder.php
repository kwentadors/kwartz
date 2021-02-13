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
            [ 'id' => 1, 'name' => 'Cash', 'entryType' => EntryType::DEBIT ],
            [ 'id' => 2, 'name' => 'Accounts Receivable', 'entryType' => EntryType::DEBIT ],
            [ 'id' => 3, 'name' => 'Accounts Payable', 'entryType' => EntryType::CREDIT ],
            [ 'id' => 4, 'name' => 'Owner\'s Capital', 'entryType' => EntryType::CREDIT ],
            [ 'id' => 5, 'name' => 'Expense - Utility', 'entryType' => EntryType::CREDIT ],
        ];

        foreach ($accounts as $account) {
            DB::table('financial_accounts')->insert([
                'name' => $account['name'],
                'entryType' => $account['entryType']
            ]);
        }

    }
}
