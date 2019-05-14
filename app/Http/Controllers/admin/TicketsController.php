<?php

namespace App\Http\Controllers\admin;
use App\Http\Requests\Ticket\UpdateTicketRequest;
use App\Models\Ticket\Ticket;
use Illuminate\Http\Request;

class TicketsController extends BaseController
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user']);

        $this->addFilterController($query, $request, [], ['user_id', 'status', 'important']);

        $tickets = $this->fetchData($query, $request);

        return response()->json($this->prepareResponse($tickets, "", true));
    }

    public function show($id)
    {
        $tickets = Ticket::where("id", $id)->with(["replies" => function ($q) {
            $q->with('user');
        }])->first();
        return response()->json($this->prepareResponse($tickets, ""));
    }


    public function update(UpdateTicketRequest $request, $id)
    {

        $items = $request->only(['status']);
        $ticket = Ticket::where("id", $id)->first();

        foreach ($items as $key => $value) {
            $ticket->{$key} = $items[$key];
        }
        $ticket->view = '0';
        $ticket->save();

        return response()->json($this->prepareResponse($ticket->id, "Changes was Success"));

    }

    public function destroy($id)
    {

        $response = Ticket::where("id", $id)->first();
        $response->delete();

        return response()->json($this->prepareResponse($response, $response ? "Deleted !" : "Cannot Delete Item", false));

    }

    public function pendingTicket(Request $request)
    {
        $query = Ticket::with(['user'])->where('status','0');

        $this->addFilterController($query, $request, [], ['user_id', 'status', 'important']);

        $tickets = $this->fetchData($query, $request);

        return response()->json($this->prepareResponse($tickets, "", true));
    }


}
