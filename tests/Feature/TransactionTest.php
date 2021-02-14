<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Transaction;
use App\Models\EntryType;
use App\Models\JournalEntry;
use App\Http\Resources\TransactionResource;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_transactions_index_empty()
    {
        $response = $this->get('/api/v1/transactions');

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => []
            ]);
    }

    public function test_transactions_index_nonempty()
    {
        $transactions = Transaction::factory()
                ->hasDebitEntries(1, function(array $attributes, Transaction $trx) {
                    return [
                        'type'      => EntryType::DEBIT,
                        'amount'    => $trx->amount
                    ];
                })
                ->hasCreditEntries(1, function(array $attributes, Transaction $trx) {
                    return [
                        'type'      => EntryType::CREDIT,
                        'amount'    => $trx->amount
                    ];
                })
                ->count(2)
                ->create();

        $response = $this->get('/api/v1/transactions');

        $response
            ->assertStatus(200)
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
            'amount'    => $entry->amount
        ];
    }
}
