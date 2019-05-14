<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\admin\BaseController;
use App\Models\Discounts\Discounts;
use App\Models\NewsLetter\NewsLetter;
use App\Models\Source\Source;
use Illuminate\Http\Request;

class PageController extends BaseController
{
    public function home(Request $request)
    {
        $user = $this->getUser();
        $sources = Source::with(['discount','products'])->get();
        if (isset(glob(public_path().'/uploads/banner/*')[0])){

            $banner ='/uploads/banner/'.array_slice(scandir('uploads/banner'), 2)[0];


            return view('website.pages.home')->with(['sources'=>$sources,'banner'=>$banner,'user'=>$user]);
        }


       return view('website.pages.home')->with(['sources'=>$sources,'banner'=>false,'user'=>$user]);
    }

     public function fq(Request $request){

        return view('website.pages.fq');

    }
    public function about(Request $request){

        return view('website.pages.about');

    }
    public function newsletter(Request $request){

        if ($request->has('email')){

            $newsletter = NewsLetter::create(['email'=>$request->get('email')]);
            $newsletter->save();

            return response()->json($this->prepareResponse($newsletter,'Your Email Has Been Stored'));

        }
        else{
            return response()->json($this->prepareResponse(false,'Please Try Again Later'));
        }

    }

    public function orderList(Request $request){
        $sources = Source::with(['discount','products'])->get();
        return view('website.pages.orders')->with('sources' , $sources);

    }
}
