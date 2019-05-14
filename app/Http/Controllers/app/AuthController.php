<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\admin\BaseController;
use App\Http\Requests\Auth\ChangePassRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetRequest;
use App\Library\Helper\Helper;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function login(LoginRequest $request)
    {
        $info = $request->only(["username", "password"]);
        $info["username"] = strtolower($info["username"]);
        if ($token = Auth::attempt($info)) {
            $user = Auth::user();
            if(!$user->status)
            {

                \Auth::logout();
                return response()->json([
                    'success' => false,
                    'object' => [],
                    'message' => 'Your Account has been suspended'
                ]);
            }

            $token = $user->createToken('Application Mobile')->accessToken;

            return response()->json([
                'success' => true,
                'message' => "Welcome",
                'object' => ["user" => $user, "token" => $token]
            ]);

        }

        return response()->json([
            'success' => false,
            'message' => "username or password is wrong .",
            'object' => []
        ]);
    }


    public function logOutUser(Request $request)
    {

        $request->user()->token()->revoke();

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
