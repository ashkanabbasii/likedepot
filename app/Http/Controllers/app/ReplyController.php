<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\admin\BaseController;
use App\Http\Requests\Reply\StoreReplyRequest;
use App\Models\Reply\Reply;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Library\Helper\Helper;



class ReplyController extends BaseController
{


    public function store(StoreReplyRequest $request)
    {
        $user = \Auth::user();
        $items = $request->only(['ticket_id' , 'reply']);
        $items['user_id'] = $user->id;
        $item = Reply::create($items);

        $item->cdate =  Carbon::parse($item->created_at)->diffForHumans(Carbon::now());

        return response()->json($this->prepareResponse($item, ($item ? "ثبت شد ." : "ثبت نشد . ")));

    }


}
