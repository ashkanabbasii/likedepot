<?php

namespace App\Http\Controllers\website\panel;

use App\Http\Controllers\admin\BaseController;
use App\Models\Ticket\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketsController extends BaseController
{
    public function index(Request $request){

        $user =$this->getUser();
        $query = Ticket::where('user_id',$user->id)->with('user');
        $this->addFilterController($query, $request, [], ["id"]);
        $tickets = $this->fetchData($query,$request);
        $tickets->each(function ($d){

            $d->cdate =  Carbon::parse($d->created_at)->diffForHumans(Carbon::now());


        });
        $ticket = Ticket::where('user_id',$user->id)->each(function ($q){
            $q->view = '1';
            $q->save();
        });
        return view('website.panel.ticket')->with(['user'=>$user,'tickets'=>$tickets]);

    }


    public function store(Request $request)

    {
        $user =$this->getUser();

        if ($user){
            $items= $request->only(['subject','message','user_id']);
            $items['status']=0;
            $items['important']=1;
            $items['view'] = '1';
            $items['user_id']=$user->id;
            $ticket =Ticket::create($items);

            return response()->json($this->prepareResponse($ticket,'Your ticket added successfully'));

        }
        else{
            return response()->json($this->prepareResponse(false,'You have connection errors'));

        }

    }

    public function show($id)
    {
        $tickets = Ticket::where("id" , $id)->with("replies")->first();
        $user =$this->getUser();
        $tickets->view = '1';
        $tickets->save();
        $tickets->cdate =  Carbon::parse($tickets->created_at)->diffForHumans(Carbon::now());

        $tickets->replies->each(function ($ticket){
            $ticket->cdate =  Carbon::parse($ticket->created_at)->diffForHumans(Carbon::now());
        });
        return view('website.panel.ticketShow')->with(['user'=>$user,'tickets'=>$tickets]);


    }
}
