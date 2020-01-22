<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use App\User;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Transformers\TransactionTransformer;

class ProductBuyerTransactionController extends ApiController
{

    public function __construct(){ 
        $this->middleware('auth:api');
        $this->middleware('transform.input:' . TransactionTransformer::class)->only(['store']);
    }
   
    public function store(Request $request ,Product $product , User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1',
        ];
        $this->validate($request , $rules);

        if($buyer->id == $product->seller_id){
            return $this->errorResponse('The buyer must be differ from the seller' , 409);
        }
        if(!$buyer->isVerified()){
            return $this->errorResponse('The buyer must be verified' , 409);
        }
        if(!$product->isAvailable()){
            return $this->errorResponse('The product not available' , 409);
        }
        if($product->quantity < $request->quantity){
            return $this->errorResponse('The product quantity not enough ' , 409);
        }
        return DB::transaction(function() use ($request , $product , $buyer){
            $product->quantity -= $request->quantity;
            $product->save();
            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);
            return $this->showOne($transaction , 201);
        });
    }

}
