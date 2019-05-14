<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\common\BaseSourceController;
use App\Http\Requests\Image\StoreImageRequest;
use App\Http\Requests\Source\StoreSourceRequest;
use App\Http\Requests\Source\UpdateSourceRequest;
use App\Library\Helper\Helper;
use App\Models\Source\Source;

class SourceController extends BaseSourceController
{

    public function store(StoreSourceRequest $request)
    {

        $items = $request->only(['name']);

        $item = Source::create($items);

        return response()->json($this->prepareResponse($item->id, ($item ? "Service Registered" : "Service Not Registered")));

    }

    public function show($id)
    {
        $source = Source::where("id" , $id)->first();

        return response()->json($this->prepareResponse($source , "" , false));

    }

    public function update(UpdateSourceRequest $request , $id)
    {

        $items = $request->only(['name']);
        $source = Source::where("id" , $id)->first();

        foreach ($items as $key => $value)
        {
            $source->{$key} = $items[$key];
        }

        $source->save();

        return response()->json($this->prepareResponse($source->id, "Changes Done"));

    }

    public function destroy($id)
    {

        $source = Source::where("id" , $id)->first();
        $source->delete();

        return response()->json($this->prepareResponse($source , $source ? "Successfully saved" : "Sorry a problem occurred" , false));


    }
    public function uploadSourceImage(StoreImageRequest $request)
    {
        if($request->hasFile("file"))
        {
            $sourceId = Source::find($request->get("id"))->id;
            $url = Helper::uploadFile($request->file('file') , $sourceId , "sources");

            return response()->json([
                'success' => true,
                'message' => 'success !',
                'object' =>  $url
            ]);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'file is not found',
            ]);
        }
    }
}
