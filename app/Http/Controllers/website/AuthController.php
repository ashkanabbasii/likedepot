<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\admin\BaseController;
use App\Http\Requests\Auth\ChangePassRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetRequest;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{

    public function login(LoginRequest $request)
    {

        $info = $request->only(["username", "password"]);
        $info["username"] = strtolower($info["username"]);
        $user = User::where(['username'=>$info['username']])->first();

        if ($user && Hash::check($info['password'],$user->password)){
            if ($user->status == 1){
                  Auth::login($user);
                  $view = view('website.partials.after_login')->with('user',$user);

                    return response()->json([
                        'success' => true,
                        'message' => "You've logged in",
                        'object' => ['view'=>$view->render(),'user'=>$user]
                    ]);

            }
            else{

                return response()->json([
                    'success' => false,
                    'message' => "Your Account is Suspended",
                    'object' => []
                ]);
            }
        }
        else{
            return response()->json([
                'success' => false,
                'message' => "username or password is wrong .",
                'object' => []
            ]);
        }


    }


    public function logOutUser(Request $request)
    {

        \Session::flush();
        \Session::regenerate();
        return response()->json([
            'success' => true,
            'object' => []
        ]);
    }

    public function changePass(ChangePassRequest $request)
    {

        $info = $request->only(['old_password' , 'new_password' ]);
        $user  = Auth::user();

        if( $user && $user->checkPasswordAttribute($info["old_password"] ) ){

            $user->password = $info["new_password"];

            $user->save();

            return response()->json($this->prepareResponse(true, "Password Changed ."));
        }
        else
        {
            return response()->json($this->prepareResponse(false, "Old password is wrong ."));
        }

    }
    public function getUser()
    {
        $user = \Auth::user();
        return response()->json($this->prepareResponse($user, ""));
    }
}
