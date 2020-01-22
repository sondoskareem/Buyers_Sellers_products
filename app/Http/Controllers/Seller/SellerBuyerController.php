<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerBuyerController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    public function index(Seller $seller)
    {
        $buyer = $seller->products()
        ->whereHas('transactions')
        ->with('transactions.buyer')
        ->get()
        ->pluck('transactions')
        ->collapse()
        ->pluck('buyer');
        return $this->showAll($buyer);
    }

}
