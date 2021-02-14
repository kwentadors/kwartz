<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

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
            'transaction_date'  => $this->transaction_date,
            'amount'            => $this->amount,
            'description'       => $this->null,
            'status'            => $this->status,
            '_links'            => $this->getLinks()
        ];
    }

    private function getLinks() {
        return [
            [
                'rel'       =>'self',
                'href'      => route('transactions.show', ['transaction' => $this->id], false),
                'method'    => 'GET'
            ],
        ];
    }
}
