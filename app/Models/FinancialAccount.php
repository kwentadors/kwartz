<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialAccount extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function transactions()
    {
        return $this->hasMany(JournalEntry::class, 'account_id');
    }

    public function balance(): float
    {
        $debitAmount = $this->transactions()
            ->where('type', EntryType::CREDIT)
            ->sum('amount');

        $creditAmount = $this->transactions()
            ->where('type', EntryType::DEBIT)
            ->sum('amount');

        if($this->entry_type == EntryType::DEBIT) {
            return $debitAmount - $creditAmount;
        } else {
            return $creditAmount - $debitAmount;
        }
    }
}
