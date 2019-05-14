<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\admin\BaseController;
use App\Models\Product\Product;
use App\Models\Source\Source;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends BaseController
{

    public function showPretty($id,$name){

       return  $this->show($id);

    }

   public function show($id){

        $products = Product::where(['source_id'=>$id])->with(['service','category','source'=>function($q){
            $q->with('discount');
        }])->get();
        $source = Source::where('id',$id)->with('discount')->first();

        $products->each(function ($product) use ($products) {

                 $product->with_gender_tax =$product->price + ($product->price * ($product->gender_percent) / 100);
                 $product->with_gender_tax_without_discount =$product->price + ($product->price * ($product->gender_percent) / 100);
             if ($product->source->discount && $product->source->discount->status){
                 $product->with_gender_tax -= ($product->source->discount->discount) /100 * $product->with_gender_tax;
                 $product->old_price = $product->price;
                 $product->price -= ($product->source->discount->discount) /100 * $product->price;
             }

            if ($product->gender && $product->gender !='none'){
                $products->hasGender = true;
            }



        });

        return view('website.pages.order-landing')->with(['products'=>$products,'source'=>$source]);

   }

}
