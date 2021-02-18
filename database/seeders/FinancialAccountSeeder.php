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
            [ 'name' => 'Cash', 'entry_type' => EntryType::DEBIT ],
            [ 'name' => 'Accounts Receivable', 'entry_type' => EntryType::DEBIT ],
            [ 'name' => 'Accounts Payable', 'entry_type' => EntryType::CREDIT ],
            [ 'name' => 'Owner\'s Capital', 'entry_type' => EntryType::CREDIT ],
            [ 'name' => 'Expense - Utility', 'entry_type' => EntryType::CREDIT ],
        ];

        foreach ($accounts as $account) {
            DB::table('financial_accounts')->insert([
                'name' => $account['name'],
                'entry_type' => $account['entry_type']
            ]);
        }

    }
}
