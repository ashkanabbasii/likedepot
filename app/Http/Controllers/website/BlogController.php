<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\admin\BaseController;
use App\Models\Blog\Blog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends BaseController
{
    public function index(Request $request){

        $news = Blog::where('status','published')->orderBy('created_at','DESC')->get();
        $news->each(function ($item){

            $item->time = Carbon::parse($item->created_at)->format('M d ,Y');

        });
        return view('website.pages.blog')->with('blogPosts',$news);

    }
}
