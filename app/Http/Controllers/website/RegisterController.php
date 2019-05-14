<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\admin\BaseController;
use App\Jobs\Register\StoreRegisterJob;
use App\Library\Helper\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends BaseController
{
    public function Register(Request $request){



        $userInfo = $request->only("name","username","password","email");
        $uniqueUser=Helper::IsUniqueUser($userInfo['username']);
        $uniqueEmail = Helper::IsUniqueEmail($userInfo['email']);

        if ($uniqueEmail && $uniqueUser){

            $user =  $this->dispatch(new StoreRegisterJob($userInfo));

            return response()->json($this->prepareResponse(true , "Registration Completed ."));

        }
        else{
            return response()->json($this->prepareResponse(false,'Your Username Or Email is duplicated'));
        }





    }
}
