<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    protected $defaultPager = 10;

    protected function addFilterController(&$query , $request , $filters , $searchable = [] , $prefix = "")
    {

        if (count($filters)) {
            foreach ($filters as $filter) {
                $filter_get = explode("." , $filter);
                $filter_get = $filter_get[count($filter_get ) - 1];
                if ($request->has($filter_get) && $request->get($filter_get) !== null) {
                    $query->where($prefix . $filter, $request->get($filter_get));
                }
            }
        }

        if(count($searchable) && $request->has("search") !== null) {
            $search = $request->get("search");
            if ($search) {
                $query->where(function ($q) use ($searchable, $search) {
                    foreach ($searchable as $item) {

                        $q->orWhere($item, "like", "%$search%");

                    }
                });
            }
        }

        /*
        if($request->has("from") && $request->get("from") !== null)
        {
            $date = explode("-" , $request->get("from"));
            $gDate =  jDateTime::toGregorian($date[0] , $date[1] , $date[2]);

            $dt = Carbon::now();
            $d = $dt->year($gDate[0])->month($gDate[1])->day($gDate[2])->hour(00)->minute(00)->second(00)->toDateTimeString();

            $query->where($prefix ."created_at" , ">" , $d);
        }

        if($request->has("to") && $request->get("to") !== null)
        {
            $date = explode("-" , $request->get("to"));
            $gDate1 =  jDateTime::toGregorian($date[0] , $date[1] , $date[2]);

            $dt = Carbon::now();
            $d = $dt->year($gDate1[0])->month($gDate1[1])->day($gDate1[2])->hour(23)->minute(59)->second(59)->toDateTimeString();

            $query->where($prefix . "created_at" , "<" , $d);
        }
        */

        $orderby = $request->has("order") ? $request->only(["order"])["order"] : "created_at";
        $desc = $request->has("desc") ? $request->get("desc") : "desc";
        $query->orderByRaw("$orderby  $desc");

    }

    /**
     * @param $query
     * @param $request
     * @param bool $paginate
     * @return mixed
     */
    protected function fetchData(&$query , $request ,  $paginate = true)
    {
        if($paginate)
        {
            $limit = $request && $request->has("pager") ? $request->get("pager") : 10;
            return $query->paginate($limit);
        }
        else{
            return $query->get();
        }
    }

    /**
     * @param $result
     * @param string $message
     * @param bool $paginate
     * @return array
     */
    protected function prepareResponse($result , $message = "" , $paginate = false)
    {
        if($paginate){
            return [
                "success" => $result ? true : false,
                "message" => $message,
                "object" => [
                    "items" => $result->items(),
                    "page" => $result->currentPage(),
                    "total" => $result->total(),
                    "pages" => $result->lastPage(),
                    "perPage" => $result->perPage()
                ]
            ];
        }
        else{
            return [
                "success" => $result ? true : false,
                "message" => $message,
                "object" => $result
            ];
        }

    }

    protected function getUser(){
        $user =  Auth::user();

        return $user;
    }

}
