<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerTransactionController extends ApiController
{
    public function __construct(){ 
        $this->middleware('auth:api');
    }
    public function index(Seller $seller)
    {
        $transactions = $seller->products()
        ->whereHas('transactions')
        ->with('transactions')
        ->get()
        ->pluck('transactions')
        ->collapse();
        return $this->showAll($transactions);

    }
}
