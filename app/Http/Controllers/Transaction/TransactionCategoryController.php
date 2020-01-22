<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionCategoryController extends ApiController
{
    public function __construct(){ 
        $this->middleware('auth:api');
    }
    public function index(Transaction $transaction)
    {
       $categories = $transaction->product->categories;
       return $this->showAll($categories);

    }


  
}
