<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialAccount extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'account_id');
    }

    public function transactions()
    {
        $transaction_ids = collect($this->journalEntries)
                            ->map(function($journalEntry, $_) {
                                return $journalEntry->transaction_id;
                            });

        return Transaction::whereIn('id', $transaction_ids);
    }

    public function balance(): float
    {
        $debitAmount = $this->journalEntries()
            ->where('type', EntryType::DEBIT)
            ->sum('amount');

        $creditAmount = $this->journalEntries()
            ->where('type', EntryType::CREDIT)
            ->sum('amount');

        if($this->entry_type == EntryType::DEBIT) {
            return $debitAmount - $creditAmount;
        } else {
            return $creditAmount - $debitAmount;
        }
    }
}
