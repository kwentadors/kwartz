<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index() {
        return new TransactionCollection(Transaction::paginate());
    }

    public function show($id) {
        return new TransactionResource(Transaction::findOrFail($id));
    }
}
