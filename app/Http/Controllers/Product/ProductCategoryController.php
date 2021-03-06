<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Category;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    public function __construct(){ 
        $this->middleware('auth:api')->only(['index' ]);
    }
    public function index(Product $product)
    {
        $categories = $product->categories;
        return  $this->showAll($categories); 
    }

    public function update(Request $request , Product $product ,Category $category ){
        $product->categories()->syncWithoutDetaching([$category->id]);
        return $this->showAll($product->categories);
    }
    public function destroy( Product $product ,Category $category){
       if(!$product->categories()->find($category->id)) {
           return $this->errorResponse('The specified categort is not category of this product' , 404);
       }
       $product->categories()->detach($category->id);
       return $this->showAll($product->categories);
    }

}
