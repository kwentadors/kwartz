<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Domain\Services\CreateTransactionService;
use App\Models\Transaction;

class TransactionController extends Controller
{
    private $createTransactionService;

    public function __construct(CreateTransactionService $createTransactionService) {
        $this->createTransactionService = $createTransactionService;
    }

    public function index() {
        return new TransactionCollection(Transaction::paginate());
    }

    public function listByMonthYear(int $year, int $month) {
        $transactions = Transaction::whereYear('transaction_date', '=', $year)
                        ->whereMonth('transaction_date', '=', $month)
                        ->paginate();

        return new TransactionCollection($transactions);
    }

    public function show($id) {
        return new TransactionResource(Transaction::findOrFail($id));
    }

    public function store(StoreTransactionRequest $request) {
        $command = $request->toCommand();
        $transaction = $this->createTransactionService->create($command);

        return new TransactionResource($transaction);
    }
}
