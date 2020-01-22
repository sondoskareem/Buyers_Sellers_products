<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()
        ->with('product.categories')
        ->get()
        ->pluck('product.categories')
        ->collapse()
        ->unique('id')
        ->values();
        return $this->showAll($categories);

    }
}
