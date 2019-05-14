<?php

namespace App\Http\Controllers\website\panel;

use App\Http\Controllers\admin\BaseController;
use App\Http\Requests\Password\ForgetPassRequest;
use App\Mailers\UserMailer;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function index(){

        $user = $this->getUser();
        return view('website.panel.user')->with('user',$user);
    }

}
