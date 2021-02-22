<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TransactionCollection;
use App\Models\FinancialAccount;
use App\Models\JournalEntry;
use App\Models\Transaction;

class FinancialAccountTransactionController extends Controller
{
    public function index(FinancialAccount $account) {
        $journalEntries = $account->transactions();

        return new TransactionCollection($journalEntries->paginate());
    }
}
