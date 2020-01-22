<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionSellerController extends ApiController
{
    public function __construct(){ 
        $this->middleware('auth:api');
    }
    public function index(Transaction $transaction)
    {
        $seller =  $transaction->product->seller;
        return $this->showOne($seller);

    }

}
