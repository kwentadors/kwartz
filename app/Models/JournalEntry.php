<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;


    // protected constructor, creation must be invoked from transaction
    protected function __construct() {}

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    public function account() {
        return $this->belongsTo(FinancialAccount::class);
    }

    public static function newCreditEntry(FinancialAccount $account, float $amount) {
        return self::newEntry($account, $amount, EntryType::CREDIT);
    }

    public static function newDebitEntry(FinancialAccount $account, float $amount) {
        return self::newEntry($account, $amount, EntryType::DEBIT);
    }

    private static function newEntry(FinancialAccount $account, float $amount, string $type) {
        $entry = new JournalEntry();
        $entry->account()->associate($account);
        $entry->amount = $amount;
        $entry->type = $type;

        return $entry;
    }
}
