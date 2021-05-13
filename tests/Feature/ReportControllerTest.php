<?php

namespace Tests\Feature;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Transaction;
use App\Models\EntryType;
use App\Models\FinancialAccount;
use DateTime;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_empty()
    {
        $response = $this->get('/api/v1/reports/assets');

        $response
            ->assertOk()
            ->assertJson([
                'data'  => [
                    'name'  => 'Assets',
                    'groups' => [],
                    'accounts' => [],
                ]
            ]);
    }

    public function test_nonempty()
    {
        $this->generateTransactions();

        $response = $this->get('/api/v1/reports/assets');

        $response
            ->assertOk()
            ->assertJson([
                'data'  => [
                    'name'      => 'Assets',
                    'balance'   => '48000.00'
                ]
            ])
            ->assertJson([
                'data'  => [
                    'groups' => [
                        [
                            'name'  => 'Cash equivalents'
                        ],
                        [
                            'name'  => 'Paper investments'
                        ],
                        [
                            'name'  => 'Receivable'
                        ],
                        [
                            'name'  => 'Property'
                        ]
                    ]
                ]
            ])
            ->assertJson([
                'data'  => [
                    'accounts' => [
                        [
                            'name'      => 'Savings - BDO',
                            'group_id'  => 1,
                            'balance'   => "38000.00",
                            'change'    => "123.53"
                        ],
                        [
                            'name' => 'MP2 Investment',
                            'group_id'  => 2,
                            'balance'   => "10000.00",
                            'change'    => "0.00"
                        ],
                        [
                            'name' => 'Accounts Receivable',
                            'group_id'  => 3,
                            'balance'   => '0.00',
                            'change'    => '-100.00'
                        ],
                    ],
                ]
            ]);
    }

    private function generateTransactions()
    {
        FinancialAccount::factory()
                    ->state(new Sequence(
                        ['id' => 1,'name' => 'Savings - BDO'],
                        ['id' => 2,'name' => 'MP2 Investment'],
                        ['id' => 3,'name' => 'Accounts Receivable'],
                        ['id' => 4,'name' => 'Income'],
                        ['id' => 5,'name' => 'Expense'],
                    ))
                    ->count(5)
                    ->create();

        $transactionsData = [
            [
                'transaction_date'  => new DateTime('2021-02-18'),
                'debit_account_id'  => 1,
                'credit_account_id' => 5,
                'amount'            => 30000,
            ],
            [
                'transaction_date'  => new DateTime('2021-03-05'),
                'debit_account_id'  => 3,
                'credit_account_id' => 1,
                'amount'            => 3000,
            ],
            [
                'transaction_date'  => new DateTime('2021-04-29'),
                'debit_account_id'  => 2,
                'credit_account_id' => 1,
                'amount'            => 10000,
            ],
            [
                'transaction_date'  => new DateTime('2021-05-08'),
                'debit_account_id'  => 1,
                'credit_account_id' => 5,
                'amount'            => 38000,
            ],
            [
                'transaction_date'  => new DateTime('2021-05-10'),
                'debit_account_id'  => 5,
                'credit_account_id' => 1,
                'amount'            => 20000,
            ],
            [
                'transaction_date'  => new DateTime('2021-05-13'),
                'debit_account_id'  => 1,
                'credit_account_id' => 3,
                'amount'            => 3000,
            ],
        ];

        array_map(function ($trxData) {
            return Transaction::factory()
            ->hasDebitEntries(1, function (array $attributes, Transaction $trx) use ($trxData) {
                return [
                    'account_id'    => $trxData['debit_account_id'],
                    'type'          => EntryType::DEBIT,
                    'amount'        => $trxData['amount']
                ];
            })
            ->hasCreditEntries(1, function (array $attributes, Transaction $trx) use ($trxData) {
                return [
                    'account_id'    => $trxData['credit_account_id'],
                    'type'          => EntryType::CREDIT,
                    'amount'        => $trxData['amount']
                ];
            })
            ->create([
                'transaction_date'  => $trxData['transaction_date']
            ]);
        }, $transactionsData);
    }
}
