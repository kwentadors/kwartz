<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\FinancialAccount;

class FinancialAccountTest extends TestCase
{
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
