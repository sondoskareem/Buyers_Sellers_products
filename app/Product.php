<?php

namespace App;
use App\Transformers\ProductTransformer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    public $transformer = ProductTransformer::class;

    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';
    use SoftDeletes;
    protected $date = ['deleted_at'];
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id'
    ];
    public function isAvailable(){
        return $this->status == Product::AVAILABLE_PRODUCT;
    }
    public function seller(){
        return $this->belongsTo(Seller::class);
    }
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
    public function categories(){
        return $this->belongsToMany(Category::class);
    }
}
