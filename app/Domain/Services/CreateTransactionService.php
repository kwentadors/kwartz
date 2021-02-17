<?php

namespace App\Domain\Services;

use App\Domain\Commands\CreateTransactionCommand;
use App\Models\Transaction;
use App\Models\JournalEntry;

class CreateTransactionService {

    public function create(CreateTransactionCommand $command): Transaction
    {
        $transaction = Transaction::create([
            'transaction_date'  => $command->getTransactionDate(),
            'amount'            => $command->getAmount(),
            'description'       => $command->getDescription(),
        ]);

        foreach($command->getDebitEntries() as $entry) {
            $transaction->debit($entry['account'], $entry['amount']);
        }

        foreach($command->getCreditEntries() as $entry) {
            $transaction->credit($entry['account'], $entry['amount']);
        }

        $transaction->refresh();
        return $transaction;
    }

}