<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\FinancialAccount;
use App\Models\Transaction;
use App\Models\JournalEntry;

class FinancialAccountTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_empty()
    {
        $account = FinancialAccount::factory()->create();
        $response = $this->get("/api/v1/accounts/{$account->id}/transactions");

        $response
            ->assertOk()
            ->assertJson([
                'data' => []
            ]);
    }

    public function test_index_nonempty()
    {
        $account = FinancialAccount::factory()->create();
        $transactions = Transaction::factory()->count(3)->create();
        $journalEntries = array_map(function($transaction) use($account) {
            return JournalEntry::factory()->create([
                'account_id'        => $account,
                'transaction_id'    => $transaction
            ]);
        }, $transactions->all());

        $response = $this->get("/api/v1/accounts/{$account->id}/transactions");

        $response
            ->assertOk()
            ->assertJson([
                'data' => array_map('self::serializeTransaction', $transactions->all())
            ]);
    }

    private static function serializeTransaction(Transaction $trx) {
        return [
            'id'                => $trx->id,
            'transaction_date'  => $trx->transaction_date->format('Y-m-d'),
            'amount'            => $trx->amount,
            'description'       => $trx->description,
            'status'            => $trx->status,
            'debit'             => array_map('self::serializeJournalEntry',
                                    $trx->debitEntries->all()),
            'credit'            => array_map('self::serializeJournalEntry',
                                    $trx->creditEntries->all())
        ];
    }

    private static function serializeJournalEntry(JournalEntry $entry) {
        return [
            'id'        => $entry->id,
            'account'   => [
                'id'    => $entry->account->id,
                'name'  => $entry->account->name
            ],
            'amount'    => (float)$entry->amount
        ];
    }
}
