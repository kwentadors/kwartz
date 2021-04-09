<?php

namespace Database\Factories;

use App\Models\EntryType;
use App\Models\FinancialAccount;
use App\Models\JournalEntry;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class JournalEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JournalEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount'            => 3499.25,
            'type'              => EntryType::DEBIT,
            'account_id'        => FinancialAccount::factory(),
            'transaction_id'    => Transaction::factory()
        ];
    }
}
