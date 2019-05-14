<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\admin\BaseController;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Library\Helper\Helper;
use App\Models\Order\Order;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class OrdersController extends BaseController
{
    public function index(Request $request)
    {

        $query = Order::with(['product' => function ($q) {
            $q->with(["source", "category"]);

        }])->where('status', '!=', '3');

        $user = \Auth::user();
        if ($user) {
            $query->where("user_id", $user->id);

        }


        $this->addFilterController($query, $request, [], ["status"]);
        $orders = $this->fetchData($query, $request);
        $orders->each(function ($q) {
            $q->product->source->hasImg = Helper::hasSourceImage($q->product->source->id);
        });


        return response()->json($this->prepareResponse($orders, "", true));

    }


    public function show($id){
        $order = Order::find($id);
        $order->view = '1';
        $order->save();
        return response()->json($this->prepareResponse(true, ""));

    }
    public function store(StoreOrderRequest $request)
    {
        $items = $request->only("product_id", "link");
        if ($items["product_id"]) {
            $product = Product::find($items["product_id"]);
            $user = \Auth::user();
            if ($product->price <= $user->wallet) {
                $items["user_id"] = $user->id;
                $items["price"] = $product->price;
                $items["status"] = 0;
                $items["ref_id"] = 1;
                $order = Order::create($items);

                $user->wallet -= $product->price;
                $user->save();

                Helper::addTransaction("order" , $order->id , "Withdraw" , $product->price , $user->id);

                return response()->json($this->prepareResponse($order, ""));
            }
            return response()->json($this->prepareResponse(false, "You dont have enough money"));
        }
        return response()->json($this->prepareResponse(false, "Please Select Valid product"));
    }

    public function canBuyProduct(Request $request)
    {
        $product_id = $request->get("product_id");
        $product = Product::find($product_id);

        if ($product) {
            $user = \Auth::user();
            if ($product->price <= $user->wallet) {
                return response()->json($this->prepareResponse(true, ""));
            }

            return response()->json($this->prepareResponse(false, "You dont have enough money"));

        } else {
            return response()->json($this->prepareResponse(false, "Please Select Valid product"));
        }

    }

    public function cancelOrder(Request $request)
    {

        $user = \Auth::user();
        $order = Order::where(['user_id' => $user->id, 'id' => $request->get('id')])->first();
        $order->status = 3;
        $price = $order->price;
        $user->wallet += $price;
        Helper::addTransaction("order" , $order->id , "Deposit" , $order->price , $user->id);
        $order->save();
        $user->save();
        return response()->json($this->prepareResponse($order, "You Canceled Your Order"));

    }

}
