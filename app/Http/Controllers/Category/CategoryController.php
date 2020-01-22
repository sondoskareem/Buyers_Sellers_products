<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Transformers\CategoryTransformer;

class CategoryController extends ApiController
{
    public function __construct(){ 
        $this->middleware('transform.input:' . CategoryTransformer::class)->only(['store' , 'update']);
        $this->middleware('auht:api')->except(['index' , 'show']);
    }
 
    public function index()
    {
       $categories = Category::all();
       return $this->showAll($categories);
    }

   
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
        ];
        $this->validate($request , $rules);
        $newCategory = Category::create($request->all());
        return $this->showOne($newCategory);

    }

    public function show(Category $category)
    {
        return $this->showOne($category);

    }
    
    public function update(Request $request, Category $category)
    {
       $category->fill($request->only([
        'name',
        'description'
       ]));
       if($category->isClean()){
        return $this->errorResponse('You need to input different values' ,  422);
       }
       $category->save();
       return $this->showOne($category);
    }

    
    public function destroy(Category $category)
    {
       $category->delete();
       return $this->showOne($category);

    }
}
