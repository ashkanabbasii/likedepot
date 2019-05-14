<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\Category\StoreCtegoryRequest;
use App\Http\Requests\Category\UpdateCtegoryRequest;
use App\Models\Category\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function index(Request $request)
    {
        $query = Category::whereRaw("1 = 1");
        $this->addFilterController($query, $request, [], ["name"]);
        $categories = $this->fetchData($query, $request);

        return response()->json($this->prepareResponse($categories , "" , true));
    }

    public function store(StoreCtegoryRequest $request)
    {

        $items = $request->only(['name']);

        $item = Category::create($items);

        return response()->json($this->prepareResponse($item, ($item ? "Submitted" : "Sorry a problem occurred")));

    }

    public function show($id)
    {
        $category = Category::where("id" , $id)->first();

        return response()->json($this->prepareResponse($category , "" , false));

    }

    public function update(UpdateCtegoryRequest $request , $id)
    {

        $items = $request->only(['name']);
        $category = Category::where("id" , $id)->first();

        foreach ($items as $key => $value)
        {
            $category->{$key} = $items[$key];
        }

        $category->save();

        return response()->json($this->prepareResponse($category->id, "Changes Done"));

    }

    public function destroy($id)
    {

        $category = Category::where("id" , $id)->first();
        $category->delete();

        return response()->json($this->prepareResponse($category , $category ? "Successfully saved" : "Cannot Delete Item" , false));


    }
}
