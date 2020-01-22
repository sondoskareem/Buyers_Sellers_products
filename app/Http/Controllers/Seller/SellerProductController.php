<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use App\Transformers\ProductTransformer;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SellerProductController extends ApiController
{

    public function __construct(){
        $this->middleware('auth:api');
        $this->middleware('transform.input:' . ProductTransformer::class)->only(['store' , 'update']);
    } 
    public function index(Seller $seller)
    {
        $products = $seller->products()
        ->get();
        return $this->showAll($products);
    }
    public function store(Request $request ,User $seller){

        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image',
        ];

        $this->validate($request , $rules);

        $data = $request->all();
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = $request->image->store('');
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);
        return $this->showOne($product);
    }

    public function update(Request $request ,Seller $seller , Product $product)
    {
        $rules = [
            'status' => 'in:' . Product::UNAVAILABLE_PRODUCT . ',' . Product::AVAILABLE_PRODUCT ,
            'quantity' => 'required|integer|min:1',
            'description' => 'required',
            'image' => 'required|image',
        ];

        $this->validate($request , $rules);
        $this->checkSeller($seller ,$product );

        //only => to ignore null or unexcepected inputs
        $product->fill($request->only([
            'name',
            'image',
            'quantity',
            'description'
        ]));

        if($request->has('status')){
            $product->status = $request->status;
            if($product->isAvailable() && $product->categories()->count() == 0){
                return $this->errorResponse('An active product must have at least one category' , 409);
            }
        }
        if($request->hasFile('image')){
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }

        if($product->isClean()){
            return $this->errorResponse('You need to inter different values' , 422);
        }
        $product->save();
        return $this->showOne($product);
    }

    public function destroy(Seller $seller , Product $product)
    {
        $this->checkSeller($seller ,$product );
        Storage::delete($product->image);//not ideal
        $product->delete();
        return $this->showOne($product);

    }

    protected function checkSeller(Seller $seller , Product $product){
        if($seller->id != $product->seller_id){
            throw new HttpExpception(422,'The specified seller is not the actuall owner of the product');
        }
    }
}