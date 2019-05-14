<?php

namespace App\Http\Controllers\admin;
use App\Models\NewsLetter\NewsLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class NewsLetterController extends BaseController
{
    public function index(Request $request)
    {
        $query = NewsLetter::whereRaw("1 = 1");
        $this->addFilterController($query, $request, [], ["id",'email']);
        $newsletters = $this->fetchData($query, $request);

        return response()->json($this->prepareResponse($newsletters , "" , true));
    }

    public function getCsv(Request $request){

        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=newsletter.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $list = NewsLetter::all()->toArray();

        array_unshift($list, array_keys($list[0]));

        $callback = function() use ($list)
        {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        $response = Response::stream($callback, 200, $headers);
        return $response;

    }
    public function update(Request $request , $id)
    {

        $items = $request->only(['email']);
        $newsletter = NewsLetter::where("id" , $id)->first();

        foreach ($items as $key => $value)
        {
            $newsletter->{$key} = $items[$key];
        }

        $newsletter->save();

        return response()->json($this->prepareResponse($newsletter->id, "Changes Done"));

    }

    public function destroy($id)
    {

        $newsletter = NewsLetter::where("id" , $id)->first();
        $newsletter->delete();

        return response()->json($this->prepareResponse($newsletter , $newsletter ? "Successfully saved" : "Cannot Delete Item" , false));


    }

}
