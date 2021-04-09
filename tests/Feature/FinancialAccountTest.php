<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\FinancialAccount;

class FinancialAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_empty()
    {
        $response = $this->get('/api/v1/accounts');

        $response
            ->assertOk()
            ->assertJson([
                'data'  => []
            ]);
    }

    public function test_index_nonempty()
    {
        $accounts = FinancialAccount::factory()
                        ->count(2)->create();

        $response = $this->get('/api/v1/accounts');

        $response
            ->assertOk()
            ->assertJson([
                'data'  => [
                    [
                        'id'        => $accounts[0]->id,
                        'name'      => $accounts[0]->name,
                        'balance'   => $accounts[0]->balance()
                    ],
                    [
                        'id'        => $accounts[1]->id,
                        'name'      => $accounts[1]->name,
                        'balance'   => $accounts[1]->balance()
                    ]
                ]
            ]);
    }

    public function test_show()
    {
        $account = FinancialAccount::factory()->create();
        $response = $this->get("/api/v1/accounts/{$account->id}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'id'        => $account->id,
                'name'      => $account->name,
                'balance'   => $account->balance()
            ])
            ->assertJson([
                '_links' => [
                    [
                        'rel'       => 'self',
                        'href'      => "/api/v1/accounts/{$account->id}",
                        'method'    => 'GET'
                    ],
                    [
                        'rel'       => 'list_transactions',
                        'href'      => "/api/v1/accounts/{$account->id}/transactions",
                        'method'    => 'GET'
                    ],
                ]
            ]);
    }
}
