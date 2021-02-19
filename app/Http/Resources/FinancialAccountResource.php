<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FinancialAccountResource extends JsonResource
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
            'id'        => $this->id,
            'name'      => $this->name,
            'balance'   => $this->balance(),

            '_links'    => $this->getLinks()
        ];
    }

    private function getLinks()
    {
        return [
            [
                'rel'       =>'self',
                'href'      => route('accounts.show', ['account' => $this->id], false),
                'method'    => 'GET'
            ],
            [
                'rel'       =>'list_transactions',
                // TODO replace with route()
                'href'      => "api/v1/accounts/{$this->id}/transactions",
                'method'    => 'GET'
            ],
        ];
    }
}
