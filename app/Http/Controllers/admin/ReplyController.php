<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\Reply\StoreReplyRequest;
use App\Models\Reply\Reply;
use App\Models\Ticket\Ticket;

class ReplyController extends BaseController
{


    public function store(StoreReplyRequest $request)
    {
        $user = \Auth::user();
        $items = $request->only(['ticket_id' , 'reply']);
        $items['user_id'] = $user->id;
        $item = Reply::create($items);
        $item->load("user");
        $tk = Ticket::find($request->get('ticket_id'));
        $tk->view = '0';
        $tk->save();

        return response()->json($this->prepareResponse($item, ($item ? "Submitted" : "Sorry a problem occurred ")));

    }

    public function destroy($id)
    {

        $response = Reply::where("id" , $id)->first();
        $response->delete();

        return response()->json($this->prepareResponse($response , $response ? "Changes Done" : "Cannot Delete Item" , false));


    }
}
