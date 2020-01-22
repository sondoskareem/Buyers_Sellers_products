<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerProductController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    public function index(Buyer $buyer)
    {
        //obtain from the query builder of the relationship Not the relationship it self
        $products = $buyer->transactions()
        ->with('product')
        ->get()
        ->pluck('product');
        return $this->showAll($products);
    }
}
