<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FinancialAccount;
use App\Http\Resources\FinancialAccountResource;

class FinancialAccountController extends Controller
{
    public function show($id)
    {
        return new FinancialAccountResource(FinancialAccount::findOrFail($id));
    }
}
