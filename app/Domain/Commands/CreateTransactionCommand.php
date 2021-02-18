<?php

namespace App\Domain\Commands;

use DateTime;
use App\Models\FinancialAccount;

class CreateTransactionCommand {

    private $transactionDate;

    private $amount;

    private $description;

    private $debitEntries = [];

    public function getTransactionDate(): DateTime
    {
        return $this->transactionDate;
    }

    public function setTransactionDate(DateTime $transactionDate)
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description) {
        $this->description = $description;

        return $this;
    }

    public function getDebitEntries(): array
    {
        return $this->debitEntries;
    }

    public function addDebitEntry(int $accountId, float $amount) {
        $account = FinancialAccount::find($accountId);
        $this->debitEntries[] = [
            'account'   => $account,
            'amount'    => $amount
        ];

        return $this;
    }

    public function getCreditEntries(): array
    {
        return $this->creditEntries;
    }

    public function addCreditEntry(int $accountId, float $amount) {
        $account = FinancialAccount::find($accountId);
        $this->creditEntries[] = [
            'account'   => $account,
            'amount'    => $amount
        ];

        return $this;
    }
}