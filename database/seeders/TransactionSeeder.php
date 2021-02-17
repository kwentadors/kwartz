<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use DateTime;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transactions')->insert([
            'transaction_date' => new DateTime("2021-02-13"),
            'amount' => 4300,
            'status' => 'OPEN',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        $journalEntries = [
            [ 'amount' => 2500, 'type' => 'CR', 'account_id' => 1, 'transaction_id' => 1 ],
            [ 'amount' => 1800, 'type' => 'CR', 'account_id' => 3, 'transaction_id' => 1 ],
            [ 'amount' => 4300, 'type' => 'DR', 'account_id' => 5, 'transaction_id' => 1 ]
        ];

        foreach ($journalEntries as $entry) {
            DB::table('journal_entries')->insert(array_merge([
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ], $entry));
        }

    }
}
