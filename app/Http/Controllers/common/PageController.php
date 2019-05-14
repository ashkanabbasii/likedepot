<?php

namespace App\Http\Controllers\common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function getLink(Request $request){

        $device = $request->header('User-Agent');

        if(strpos($device,'AppleWebKit') !== false){
            return redirect('https://likedepot.net/likedepot.ipa');
        }
        else{
            return redirect('https://likedepot.net/likedepot.apk');
        }

    }

    public function installApp(Request $request){

        $q  = $request->get('q');


        return view('pages.install-app')->with('tab',$q);

    }
}
