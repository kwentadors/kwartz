<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Utils\DateUtils;
use App\Domain\Utils\NumberFormatUtils;

class IncomeExpenseResource extends JsonResource
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
            'income' => $this->assembleIncomeList(),
            'expense' => $this->assembleExpenseList()
        ];
    }

    private function assembleIncomeList()
    {
        return array_map(function ($entry) {
            return [
                'key'   => $this->serializeEntryKey($entry->getKey()),
                'value' => NumberFormatUtils::formatNumber($entry->getIncome())
            ];
        }, $this->getMonthlyEntries());
    }

    private function assembleExpenseList()
    {
        return array_map(function ($entry) {
            return [
                'key'   => $this->serializeEntryKey($entry->getKey()),
                'value' => NumberFormatUtils::formatNumber($entry->getExpense())
            ];
        }, $this->getMonthlyEntries());
    }

    private function serializeEntryKey($key)
    {
        return [
            'month' => DateUtils::monthName($key->getMonth()),
            'year'  => $key->getYear()
        ];
    }
}
