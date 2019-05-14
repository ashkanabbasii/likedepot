<?php

namespace App\Http\Controllers\admin;

use App\Library\Helper\Helper;
use App\Models\Blog\Blog;
use App\Models\Discounts\Discounts;
use App\Models\Source\Source;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends BaseController
{


    public function index(Request $request){



        $news = Blog::whereRaw('1=1');
        $news->each(function ($item){

            $item->time = Carbon::parse($item->created_at)->format('M d ,Y');

        });

        $this->addFilterController($news,$request,['created_at'],['title','status']);
        $item =  $this->fetchData($news,$request,true);

        return response()->json($this->prepareResponse($item, '',true));

    }
    public function store(Request $request)
    {

        $items = $request->only(['title' , "blog_body" , "status"]);

        $item = Blog::create($items);

        return response()->json($this->prepareResponse($item, ($item ? "Submitted" : "Sorry a problem occurred ")));

    }
    public function show($id)
    {
        $blog = Blog::where("id" , $id)->first();

        return response()->json($this->prepareResponse($blog , "" , false));

    }



    public function update(Request $request , $id)
    {

        $items = $request->only(['title' , "blog_body" , "status"]);
        $blog = Blog::where("id" , $id)->first();

        foreach ($items as $key => $value)
        {
            $blog->{$key} = $items[$key];
        }

        $blog->save();

        return response()->json($this->prepareResponse($blog->id, "Changes Done"));

    }

    public function destroy($id)
    {

        $blog = Blog::where("id" , $id)->first();
        $blog->delete();

        return response()->json($this->prepareResponse($blog , $blog ? "Successfully saved" : "Cannot Delete Item" , false));


    }

}
