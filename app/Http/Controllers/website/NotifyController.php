<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\admin\BaseController;
use App\Models\Order\Order;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\Auth;

class NotifyController extends BaseController
{
    public function notify(){

        $user = Auth::user();
        $tickets = Ticket::where(['user_id'=>$user->id , 'view' =>0])->get();
        $orders = Order::where(['user_id'=>$user->id , 'view' =>0])->get();
        $tickets = $tickets->count();
        $orders = $orders->count();

        return response()->json($this->prepareResponse(['orders'=>$orders,'ticket'=>$tickets],''));



    }
}
