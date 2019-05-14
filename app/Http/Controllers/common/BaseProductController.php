<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\admin\BaseController;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class BaseProductController extends BaseController
{
    public function index(Request $request)
    {

        $query = Product::with("source" , "category" , "service");
        $this->addFilterController($query, $request, ["source_id" , "service_id" , "cat_id",'name'], ["price" , "quantity" , "id"]);
        $products = $this->fetchData($query, $request);
        return response()->json($this->prepareResponse($products , "" , true));
    }
}
