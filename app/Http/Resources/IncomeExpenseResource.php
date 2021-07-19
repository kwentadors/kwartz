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
            $monthName = DateUtils::monthNameAbbr($entry->getMonth());
            $amount = NumberFormatUtils::formatNumber($entry->getIncome());

            return [$monthName => $amount];
        }, $this->getMonthlyEntries());
    }

    private function assembleExpenseList()
    {
        return array_map(function ($entry) {
            $monthName = DateUtils::monthNameAbbr($entry->getMonth());
            $amount = NumberFormatUtils::formatNumber($entry->getExpense());

            return [$monthName => $amount];
        }, $this->getMonthlyEntries());
    }
}
