<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Transaction;
use App\Models\EntryType;
use App\Models\FinancialAccount;
use App\Models\JournalEntry;
use App\Http\Resources\TransactionResource;
use Tests\TestCase;
use DateTime;

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

        $response = $this->getJson('/api/v1/transactions');

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => array_map('self::serializeTransaction', $transactions->all())
            ]);
    }

    public function test_listByMonthYear() {
        $transactionDates = [
            new DateTime('2019-02-02'),
            new DateTime('2020-02-03'),
            new DateTime('2020-02-05'),
            new DateTime('2020-02-23'),
            new DateTime('2020-03-05'),
            new DateTime('2020-05-17'),
            new DateTime('2020-12-05'),
        ];

        $transactions = array_map(function($transactionDate) {
            return Transaction::factory()->create([
                'transaction_date' => $transactionDate
            ]);
        }, $transactionDates);

        $filterYear = 2020;
        $filterMonth = 2;

        $response = $this->get("/api/v1/transactions/$filterYear/$filterMonth");

        $response
            ->assertOk()
            ->assertJson([
                'data' => array_map('self::serializeTransaction',
                    array_slice($transactions, 1, 3))
            ]);
    }

    public function test_transactions_show()
    {
        $transaction = Transaction::factory()
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
                        ->create();

        $response = $this->getJson("/api/v1/transactions/{$transaction->id}");

        $response
            ->assertStatus(200)
            ->assertJson(self::serializeTransaction($transaction));
    }

    public function test_store()
    {
        $transaction_date = new DateTime("2020-01-29");
        $debitAccount = FinancialAccount::factory()->create();
        $creditAccount1 = FinancialAccount::factory()->create();
        $creditAccount2 = FinancialAccount::factory()->create();

        $requestBody = [
            'transaction_date'  => $transaction_date->format('Y-m-d'),
            'amount'            => 4000,
            'description'       => 'Purchased car',
            'debit'             => [
                [
                    'account_id'    => $debitAccount->id,
                    'amount'        => 4000
                ]
            ],
            'credit'            => [
                [
                    'account_id'    => $creditAccount1->id,
                    'amount'        => 3500
                ],
                [
                    'account_id'    => $creditAccount2->id,
                    'amount'        => 500
                ]
            ]
        ];

        $response = $this->postJson('/api/v1/transactions', $requestBody);

        $response
            ->assertCreated()
            ->assertJson([
                'transaction_date'  => $requestBody['transaction_date'],
                'amount'            => $requestBody['amount'],
                'description'       => $requestBody['description']
            ])
            ->assertJsonCount(1, 'debit')
            ->assertJson([
                'debit' => [
                    [
                        'account'   => [
                            'id'    => $debitAccount->id,
                            'name'  => $debitAccount->name
                        ],
                        'amount'    => (string)$requestBody['debit'][0]['amount']
                    ]
                ]
            ])
            ->assertJsonCount(2, 'credit')
            ->assertJson([
                'credit' => [
                    [
                        'account'   => [
                            'id'    => $creditAccount1->id,
                            'name'  => $creditAccount1->name
                        ],
                        'amount'    => (string)$requestBody['credit'][0]['amount']
                    ],
                    [
                        'account'   => [
                            'id'    => $creditAccount2->id,
                            'name'  => $creditAccount2->name
                        ],
                        'amount'    => (string)$requestBody['credit'][1]['amount']
                    ]
                ]
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
