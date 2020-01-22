<?php

namespace App;
use App\User;
use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;

use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{
    //to use scope with evry query
    public $transformer = BuyerTransformer::class;

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
