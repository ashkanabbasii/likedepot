<?php

namespace App\Http\Controllers\admin;

use App\Library\Helper\Helper;
use App\Models\Discounts\Discounts;
use App\Models\Source\Source;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiscountController extends BaseController
{

    public function BannerExpire(Request $request){


        unlink(glob(public_path().'/uploads/banner/*')[0]);
        return response()->json([
            'success' => true,
            'message' => 'success !',

        ]);

    }

    public function uploadBannerImage(Request $request){

            if($request->hasFile("file"))
            {

                $url = Helper::uploadFile($request->file('file') , 1 , "banner");

                return response()->json([
                    'success' => true,
                    'message' => 'success !',
                    'object' =>  $url
                ]);
            }
            else
            {
                return response()->json([
                    'success' => false,
                    'message' => 'file is not found',
                ]);
            }


    }

    public function index(Request $request){


        $discounts =Discounts::whereRaw('1=1')->with('source');

        $this->addFilterController($discounts,$request,['id']);
        $item =  $this->fetchData($discounts,$request,true);

        return response()->json($this->prepareResponse($item, '',true));

    }
    public function store(Request $request)
    {

        $items = $request->only(['source_id' , "discount" , "status"]);

        $item = Discounts::create($items);

        return response()->json($this->prepareResponse($item, ($item ? "Submitted" : "Sorry a problem occurred ")));

    }
    public function show($id)
    {
        $discpunt = Discounts::where("id" , $id)->first();

        return response()->json($this->prepareResponse($discpunt , "" , false));

    }

    public function getDiscountInfo(Request $request){

        $source = Source::all();
        return response()->json($this->prepareResponse($source,''));

    }

    public function update(Request $request , $id)
    {

        $items = $request->only(['source_id' , "status" , "discount"]);
        $discpunt = Discounts::where("id" , $id)->first();

        foreach ($items as $key => $value)
        {
            $discpunt->{$key} = $items[$key];
        }

        $discpunt->save();

        return response()->json($this->prepareResponse($discpunt->id, "Changes Done"));

    }

    public function destroy($id)
    {

        $discount = Discounts::where("id" , $id)->first();
        $discount->delete();

        return response()->json($this->prepareResponse($discount , $discount ? "Successfully saved" : "Cannot Delete Item" , false));


    }

}
