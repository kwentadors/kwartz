<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FinancialAccount;
use App\Http\Resources\FinancialAccountResource;
use App\Http\Resources\FinancialAccountCollection;

class FinancialAccountController extends Controller
{

    public function index() {
        return new FinancialAccountCollection(FinancialAccount::paginate(100));
    }

    public function show($id)
    {
        return new FinancialAccountResource(FinancialAccount::findOrFail($id));
    }
}
