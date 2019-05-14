<?php

namespace App\Http\Controllers\admin;

use App\Models\Transaction\Transaction;
use Illuminate\Http\Request;

class TransactionController extends BaseController
{
    public function index(Request $request){


        $query = Transaction::whereRaw('1 = 1')->with('user');
        $this->addFilterController($query, $request, ['id','ref_id'], ['type','ref_id','ref_type','amount'],[]);
        $transaction = $this->fetchData($query, $request);

        return response()->json($this->prepareResponse($transaction , "" , true));
    }



}
