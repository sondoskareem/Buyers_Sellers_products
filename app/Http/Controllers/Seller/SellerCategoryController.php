<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerCategoryController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    public function index(Seller $seller)
    {
        $categories = $seller->products()
        ->whereHas('categories')
        ->with('categories')
        ->get()
        ->pluck('categories')
        ->collapse();
        return $this->showAll($categories);

    }
}
