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
            [ 'id' => 1, 'name' => 'Cash', 'entry_type' => EntryType::DEBIT ],
            [ 'id' => 2, 'name' => 'Accounts Receivable', 'entry_type' => EntryType::DEBIT ],
            [ 'id' => 3, 'name' => 'Accounts Payable', 'entry_type' => EntryType::CREDIT ],
            [ 'id' => 4, 'name' => 'Owner\'s Capital', 'entry_type' => EntryType::CREDIT ],
            [ 'id' => 5, 'name' => 'Expense - Utility', 'entry_type' => EntryType::CREDIT ],
        ];

        foreach ($accounts as $account) {
            DB::table('financial_accounts')->insert([
                'name' => $account['name'],
                'entry_type' => $account['entry_type']
            ]);
        }

    }
}
