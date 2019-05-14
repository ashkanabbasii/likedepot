<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\admin\BaseController;
use App\Library\Helper\Helper;
use App\Models\Source\Source;
use Illuminate\Http\Request;

class BaseSourceController extends BaseController
{
    public function index(Request $request)
    {
        $query = Source::whereRaw("1 = 1");
        $this->addFilterController($query, $request, [], ["name"]);
        $sources = $this->fetchData($query, $request);

        $sources->each(function ($s){
            $s->hasImg = Helper::hasSourceImage($s->id);
        });
        return response()->json($this->prepareResponse($sources , "" , true));
    }

}
