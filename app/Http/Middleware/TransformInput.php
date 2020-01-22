<?php

namespace App\Http\Middleware;

use Closure;

class TransformInput
{
   //$request->replace not working , i prefer not to use transformer
    public function handle($request, Closure $next , $transformer)
    {
        $transformedInput = [];
        //all body
        foreach($request->request->all() as $input => $value){
            //get th value of evry input & put it in the array with the index of original input 
            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }//[name => myName , description => anydes]

        //replace the input of the request with the originalAttripute one
        $this->request->replace($transformedInput);
        return $next($request);
    }
}  
