<?php

use App\User;
use App\Category;
use App\Product;
use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $userQuentity = 1000;
        $categoryQuentity = 30;
        $productQuentity = 1000;
        $transactionQuentity = 1000;

        factory(User::class , $userQuentity)->create();
        factory(Category::class , $categoryQuentity)->create();
        factory(Product::class , $productQuentity)->create()->each(
            function($product){
                $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product->categories()->attach($categories);
            }
        );
        factory(Transaction::class , $transactionQuentity)->create();

    }
}
