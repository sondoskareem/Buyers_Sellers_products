<?php

namespace App\Http\Controllers\Buyer;
use App\Buyer;
use App\User;
use App\Seller;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    public function index()
    {
       
        $buyers = Buyer::has('transactions')->get();
        return $this->showAll($buyers);
    }

    public function show(Buyer $buyer)
    {
        return $this->showOne($buyer);
    }

   
}
