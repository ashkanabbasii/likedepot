<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\admin\BaseController;
use App\Library\Helper\Helper;
use Illuminate\Http\Request;

class TransactionController extends BaseController
{

    public function addWallet(Request $request)
    {
        $items = $request->only(["amount" , "ref_id" , "type"]);

        $user = \Auth::user();

        if($user)
        {
            $user->wallet = $user->wallet + intval($items["amount"]);
            $user->save();

            Helper::addTransaction($items["type"] , $items['ref_id'] , 'Deposit' , $items["amount"] , $user->id);

            return response()->json($this->prepareResponse(true , "your wallet charged ."));

        }
        return response()->json($this->prepareResponse(false , "user not found"));

    }


}
