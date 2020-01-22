<?php

namespace App\Transformers;
use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identifier' => (int)$product->id,
            'title' => (string)$product->name,
            'details' => (string)$product->description,
            'stock' => (int)$product->quantity,
            'picture' => url("img/{$product->image}"),
            'situation' => (string)$product->status,
            'seller' => (int)$product->seller_id,
            'creationDate' => (string)$product->created_at,
            'lastChange' => (string)$product->updated_at,
            'deletedDate' => isset($product->deleted_at) ? (string) $product->deleted_at : null,
        
            'links' => [
                [
                    'rel' => 'self',
                    'herf' => route('products.show' , $product->id),
                ],
                [
                    'rel' => 'product.buyers',
                    'herf' => route('products.buyers.index' , $product->id),
                ],
                [
                    'rel' => 'product.categories',
                    'herf' => route('products.categories.index' , $product->id),
                ],
                [
                    'rel' => 'product.transactions',
                    'herf' => route('products.transactions.index' , $product->id),
                ],
                [
                    'rel' => 'seller',
                    'herf' => route('sellers.show' , $product->seller->id),
                ],
            ]
        ];
    }
    public static function originalAttribute($index){
        $attributes = [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
            'stock' => 'quantity',
            'picture' => 'image',
            'situation' => 'status',
            'seller' => 'seller_id',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at'
        ];
          return isset($attributes[$index]) ? $attributes[$index]  : null;
    }
}
