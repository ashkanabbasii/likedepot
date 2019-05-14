<?php

namespace App\Http\Controllers\admin;
use App\Http\Requests\Category\StoreCtegoryRequest;
use App\Http\Requests\Category\UpdateCtegoryRequest;
use App\Models\Service\Service;
use Illuminate\Http\Request;

class ServicesController extends BaseController
{
    public function index(Request $request)
    {
        $query = Service::whereRaw("1 = 1");
        $this->addFilterController($query, $request, [], ["name"]);
        $services = $this->fetchData($query, $request);

        return response()->json($this->prepareResponse($services , "" , true));
    }

    public function store(StoreCtegoryRequest $request)
    {

        $items = $request->only(['name']);

        $item = Service::create($items);

        return response()->json($this->prepareResponse($item, ($item ? "Service Registered" : "Service Not Registered")));

    }

    public function show($id)
    {
        $service = Service::where("id" , $id)->first();

        return response()->json($this->prepareResponse($service , "" , false));

    }

    public function update(UpdateCtegoryRequest $request , $id)
    {

        $items = $request->only(['name']);
        $service = Service::where("id" , $id)->first();

        foreach ($items as $key => $value)
        {
            $service->{$key} = $items[$key];
        }

        $service->save();

        return response()->json($this->prepareResponse($service->id, "Changes Done"));

    }

    public function destroy($id)
    {

        $service = Service::where("id" , $id)->first();
        $service->delete();

        return response()->json($this->prepareResponse($service , $service ? "Successfully saved" : "Cannot Delete Item" , false));


    }
}
