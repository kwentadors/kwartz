<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date', 'amount', 'description'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'OPEN',
    ];

    protected $casts = [
        'transaction_date' => 'datetime:Y-m-d',
    ];

    public function entries() {
        return $this->hasMany(JournalEntry::class);
    }

    public function debitEntries() {
        return $this->entries()
            ->where('type', EntryType::DEBIT);
    }

    public function creditEntries() {
        return $this->entries()
            ->where('type', EntryType::CREDIT);
    }

    public function debit(FinancialAccount $account, float $amount) {
        return $this->entries()->save(JournalEntry::newDebitEntry($account, $amount));
    }

    public function credit(FinancialAccount $account, float $amount) {
        return $this->entries()->save(JournalEntry::newCreditEntry($account, $amount));
    }
}
