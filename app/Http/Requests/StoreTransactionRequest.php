<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Domain\Commands\CreateTransactionCommand;

use DateTime;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: implementation once uuser-context is implemented
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transaction_date'  => 'required|date_format:Y-m-d',
            'amount'            => 'required|numeric|gte:0',
            'description'       => 'nullable',

            'debit.*.account_id'   => 'required|numeric',
            'debit.*.amount'       =>'required|numeric|gte:0|lte:amount',

            'credit.*.account_id'   => 'required|numeric',
            'credit.*.amount'       =>'required|numeric|gte:0|lte:amount'
        ];
    }

    public function toCommand()
    {
        $values = $this->validated();
        $command = (new CreateTransactionCommand)
            ->setTransactionDate(new DateTime($values['transaction_date']))
            ->setAmount($values['amount'])
            ->setDescription($values['description']);

        foreach($values['debit'] as $debitEntry) {
            $command->addDebitEntry($debitEntry['account_id'], $debitEntry['amount']);
        }

        foreach($values['credit'] as $creditEntry) {
            $command->addCreditEntry($creditEntry['account_id'], $creditEntry['amount']);
        }

        return $command;
    }
}
