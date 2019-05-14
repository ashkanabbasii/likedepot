<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\admin\BaseController;
use App\Http\Requests\Password\ForgetPassRequest;
use App\Mailers\UserMailer;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ForgetPassController extends BaseController
{
    public function forgetPassword(ForgetPassRequest $request,UserMailer $mailer){
        $email  = $request->get('email');
        $token="";

        for($i =0; $i< 17; $i++){

            $token.=chr(rand(65,90));

        }

        $user = User::where('email',$email)->first();

        if($user){

            $user->password_token = $token;
            $user->save();
            $mailer->sendEmailRegistrationActivationEmail($user);
            return response()->json($this->prepareResponse($user,'Your Password link sent to your email'));
        }

        else{

            return response()->json($this->prepareResponse(false,'This email address not found'));

        }

    }

    public function resetPass(Request $request){

        $q=$request->get('token');
        $user = User::find($request->get('user'));
        if($user->password_token === $q){

            return view('emaile.changepass')->with('user',$user);

        }
        else{
            return 'something went wrong';
        }

    }

    public function changePass(Request $request){

        $password = $request->get('password');

        $user = User::find($request->get('user'));

        if ($user){
            $user->setPasswordAttribute($password);
            $user->save();
            return 'Your password has been changed';

        }
        else{
            return 'something went wrong';
        }

    }
}
