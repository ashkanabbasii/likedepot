<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\Auth\LoginAdminRequest;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginAdminRequest $request)
    {
        $info = $request->only(["username", "password"]);
        $allow = User::where(['username'=>$info['username']])->where('type','<>','User')->first();

        if ($allow){
            if ($token = Auth::attempt($info)) {
                $user = Auth::user();
                $token = $user->createToken('Admin Panel')->accessToken;
                if ($token) {
                    return response()->json([
                        'success' => true,
                        'message' => "Welcome",
                        'object' => ["user" => $user, "token" => $token]
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => "username or password is not valid",
                    'object' => []
                ]);

            }
        }
        return response()->json([
            'success' => false,
            'message' => "username or password is not valid",
            'object' => []
        ]);
    }

    public function logOutUser(Request $request)
    {

        $request->user()->token()->revoke();
        //Auth::guard('api')->logOut();
        \Session::flush();
        \Session::regenerate();

        return response()->json([
            'success' => true,
            'object' => []
        ]);
    }

    protected function guard()
    {
        return Auth::guard('api');
    }
}
