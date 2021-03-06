<?php

namespace App\Transformers;
use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
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
    public function transform(Buyer $buyer)
    {
        return [
            'identifier' => (int)$buyer->id,
            'name' => (string)$buyer->name,
            'email' => (string)$buyer->email,
            'isVerified' => (int)$buyer->verified,
            'creationDate' => (string)$buyer->created_at,
            'lastChange' => (string)$buyer->updated_at,
            'deletedDate' => isset($buyer->deleted_at) ? (string) $buyer->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'herf' => route('buyers.show' , $buyer->id),
                ],
                [
                    'rel' => 'buyer.category',
                    'herf' => route('buyers.categories.index' , $buyer->id),
                ],
                [
                    'rel' => 'buyer.transactions',
                    'herf' => route('buyers.transactions.index' , $buyer->id),
                ],
                [
                    'rel' => 'seller',
                    'herf' => route('buyers.sellers.index' , $buyer->id),
                ],
                [
                    'rel' => 'product',
                    'herf' => route('buyers.products.index' , $buyer->id),
                ],
               
            ]
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identifier' => 'id',
            'name' => 'name',
            'email' => 'email',
            'isVerified' => 'verified',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at'
        ];
          return isset($attributes[$index]) ? $attributes[$index]  : null;
    }
}
