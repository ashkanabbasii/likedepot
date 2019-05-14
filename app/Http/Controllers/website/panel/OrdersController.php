<?php

namespace App\Http\Controllers\website\panel;

use App\Http\Controllers\admin\BaseController;
use App\Library\Helper\Helper;
use App\Models\Discounts\Discounts;
use App\Models\Order\Order;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersController extends BaseController
{
    public function index(Request $request){

        $user = $this->getUser();
        $query = Order::with(['product' => function ($q) {
            $q->with(["source", "category",'service']);

        }]);

        if ($user) {
            $query->where("user_id", $user->id);

        }
        $order = Order::where('user_id',$user->id)->each(function ($q){
            $q->view = '1';
            $q->save();
        });


        $this->addFilterController($query, $request, [], ["status"]);
        $orders = $this->fetchData($query, $request);
        return view('website.panel.orders')->with(['user'=>$user,'orders'=>$orders]);
    }
    public function store(Request $request)
    {
        $items = $request->only("product_id", "link");

        if ($items["product_id"]) {
            $product = Product::find($items["product_id"]);
            $discount = Discounts::where(['status'=>'1','source_id'=>$product->source->id])->first();
            $user = \Auth::user();

            if ($request->get('hasgender') == 'male' || $request->get('hasgender') == 'female'){

                $product->price =$product->price + ($product->price * ($product->gender_percent) / 100);

            }

            if ($discount){

                $product->price -= ($discount->discount / 100) * $product->price;

            }

            if ($product->price <= $user->wallet) {
                $items["user_id"] = $user->id;
                $items["price"] = $product->price;
                $items["status"] = 0;
                $items["ref_id"] = 1;
                $items["gender"] = $request->get('hasgender') && $request->get('hasgender') !=='false' ? $request->get('hasgender') : 'none';
                $order = Order::create($items);
                $user->wallet -= $product->price;
                $user->save();
                $order->remain = $user->wallet;
                Helper::addTransaction("order" , $order->id , "Withdraw" , $product->price , $user->id);

                return response()->json($this->prepareResponse($order, ""));
            }
            return response()->json($this->prepareResponse(false, "You dont have enough money"));
        }
        return response()->json($this->prepareResponse(false, "Please Select Valid product"));
    }
}
