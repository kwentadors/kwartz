<?php

namespace Database\Factories;

use App\Models\FinancialAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EntryType;

class FinancialAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FinancialAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $accountCodeCounter = 1000;
        $accountCodeCounter += 10;
        return [
            'name'          => 'Cash',
            'account_code'  => $accountCodeCounter,
            'entry_type'    => EntryType::DEBIT,
        ];
    }
}
