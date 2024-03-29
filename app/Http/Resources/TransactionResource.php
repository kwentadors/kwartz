<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

use App\Domain\Utils\DateUtils;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'transaction_date'  => $this->transaction_date->format('Y-m-d'),
            'amount'            => (float) $this->amount,
            'description'       => $this->description,
            'status'            => $this->status,
            'debit'             => array_map(
                'self::journalEntryResource',
                $this->debitEntries->all()
            ),
            'credit'            => array_map(
                'self::journalEntryResource',
                $this->creditEntries->all()
            ),
            '_links'            => $this->getLinks()
        ];
    }

    private static function journalEntryResource($entry)
    {
        return [
            'id'        => $entry->id,
            'account'   => [
                'id'    => $entry->account->id,
                'name'  =>$entry->account->name
            ],
            'amount'    => (float) $entry->amount,
        ];
    }

    private function getLinks()
    {
        return [
            [
                'rel'       =>'self',
                'href'      => route('transactions.show', ['transaction' => $this->id], false),
                'method'    => 'GET'
            ],
        ];
    }
}
