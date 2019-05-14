<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\admin\BaseController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Library\Helper\Helper;
use App\Models\User\User;

class RegistrationController extends BaseController
{
    public function store(RegisterRequest $request)
    {
        $userInfo = $request->only("name","username","password","email");
        $uniqueUser=Helper::IsUniqueUser($userInfo['username']);
        $uniqueEmail = Helper::IsUniqueEmail($userInfo['email']);

        if ($uniqueEmail && $uniqueUser){
            $userInfo["type"] = "User";
            $userInfo["wallet"] = 0;
            $userInfo["status"] = 1;
            User::create($userInfo);
            return response()->json($this->prepareResponse(true , "Registration Completed ."));
        }
        else{
            return response()->json($this->prepareResponse(false,'Your Username Or Email is duplicated'));
        }

    }
}
