<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Library\Helper\Helper;
use App\Models\Order\Order;
use App\Models\User\User;
use Illuminate\Http\Request;

class OrdersController extends BaseController
{
    public function index(Request $request)
    {
        $query = Order::with('user');
        $this->addFilterController($query, $request, ['id','status'], ['id','link',"created_at",'price','product_id']);
        $orders = $this->fetchData($query, $request);

        return response()->json($this->prepareResponse($orders , "" , true));
    }

    public function update(StoreOrderRequest $request ,$id)
    {


        $items = $request->only(['status']);
        $order = Order::where("id" , $id)->first();

        foreach ($items as $key => $value)
        {
            $order->{$key} = $items[$key];
        }

        if ($items['status'] == 3){

            $user = User::find($order->user_id);
            $price = $order->price;
            $user->wallet += $price;
            Helper::addTransaction("order" , $order->id , "Deposit" , $order->price , $user->id);
            $user->save();
        }
        $order->view = '0';
        $order->save();


        return response()->json($this->prepareResponse($order->id, "Changes Done"));

    }

    public function pendingOrder(Request $request)
    {
        $query = Order::with('user')->where('status','0');
        $this->addFilterController($query, $request, ['id','status'], ['link',"created_at",'price']);
        $orders = $this->fetchData($query, $request);
        return response()->json($this->prepareResponse($orders , "" , true));
    }
}
