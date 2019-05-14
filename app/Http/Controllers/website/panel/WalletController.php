<?php

namespace App\Http\Controllers\website\panel;

use App\Http\Controllers\admin\BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WalletController extends BaseController
{
    public function index(Request $request){

        $user = $this->getUser();

        if ($request->has('status') || $request->has('pay_id')){

            return view('website.panel.wallet')->with(['user'=>$user,'status'=>$request->get('status'),'pay_id'=>$request->get('pay_id')]);
        }


        return view('website.panel.wallet')->with('user',$user);

    }

}
