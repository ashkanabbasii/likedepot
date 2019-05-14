<?php

namespace App\Http\Controllers\admin;


use App\Models\user\user;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function index(Request $request)
    {

        $query = User::whereRaw('1 = 1');
        $this->addFilterController($query, $request, ['id'], ['name','username','id','email'],[]);
        $users = $this->fetchData($query, $request);

        return response()->json($this->prepareResponse($users , "" , true));
    }



    public function show($id)
    {
        $user = User::where("id" , $id)->first();

        return response()->json($this->prepareResponse($user , "" , false));

    }

    public function update(Request $request , $id)
    {

        $items = $request->only(['name','username','status','email','wallet','type']);
        $user = User::where("id" , $id)->first();

        foreach ($items as $key => $value)
        {
            $user->{$key} = $items[$key];
        }

        $user->save();

        return response()->json($this->prepareResponse($user->id, "Changes was Success"));

    }

    public function destroy($id)
    {

        $user = User::where("id" , $id)->first();
        $user->delete();

        return response()->json($this->prepareResponse($user , $user ? "Successfully Saved" : "Sorry a problem occurred" , false));


    }

}
