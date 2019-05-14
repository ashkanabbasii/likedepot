<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\admin\BaseController;
use App\Models\Ticket\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\Helper\Helper;
use Illuminate\Support\Facades\Auth;


class TicketController extends BaseController
{
    public function index(Request $request){
            
        $user = \Auth::user();
        $query = Ticket::where('user_id',$user->id)->with('user');
        $this->addFilterController($query, $request, [], ["id"]);
        $tickets = $this->fetchData($query,$request);
        $tickets->each(function ($d){

            $d->cdate =  Carbon::parse($d->created_at)->diffForHumans(Carbon::now());


        });



        return response()->json($this->prepareResponse($tickets,'',true));

    }

    public function store(Request $request)

    {
        $user = \Auth::user();

        if ($user){
            $items= $request->only(['subject','message','user_id']);
            $items['status']=0;
            $items['important']=1;
            $items['view'] = '1';
            Ticket::create($items);
            return response()->json($this->prepareResponse(true,'Your ticket added successfully'));

        }
        else{
            return response()->json($this->prepareResponse(false,'You have connection errors'));

        }

    }

    public function show($id)
    {
        $tickets = Ticket::where("id" , $id)->with("replies")->first();

        $tickets->view = '1';
        $tickets->save();
        $tickets->cdate =  Carbon::parse($tickets->created_at)->diffForHumans(Carbon::now());

        $tickets->replies->each(function ($ticket){
            $ticket->cdate =  Carbon::parse($ticket->created_at)->diffForHumans(Carbon::now());
        });

        return response()->json($this->prepareResponse($tickets,'Your ticket added successfully'));

    }


}
