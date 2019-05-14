<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\common\BaseProductController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\Service\Service;
use App\Models\Source\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends BaseProductController
{
    public function index(Request $request)
    {



      $query =  DB::table('products')
            ->join( 'sources', 'sources.id','=','products.source_id')
            ->join('categories','categories.id','=','products.cat_id')
            ->join( 'services', 'services.id' , '=' , 'products.service_id')
            ->select(
              'products.*',
              'sources.name as source_name',
              'categories.name as category_name',
              'services.name as service_name'

          );

        $this->addFilterController($query, $request,['id'], ['sources.name','categories.name','services.name'],[]);

        $products = $this->fetchData($query, $request);

        return response()->json($this->prepareResponse($products , "" , true));
    }
    public function prepareSubmitProduct()
    {
        $categories = Category::all();
        $services = Service::all();
        $sources = Source::all();

        return response()->json($this->prepareResponse(compact('sources' ,'categories' , 'services'), "" ));

    }

    public function store(StoreProductRequest $request)
    {

        $items = $request->only(['source_id' , "cat_id" , "service_id" , "quantity", "price",'description','gender','gender_percent']);

        $item = Product::create($items);

        return response()->json($this->prepareResponse($item, ($item ? "Submitted" : "Sorry a problem occurred ")));

    }

    public function show($id)
    {
        $product = Product::with(['category','source','service'])->where("id" , $id)->first();
        return response()->json($this->prepareResponse($product , "" , false));

    }

    public function update(UpdateProductRequest $request , $id)
    {

        $items = $request->only(['source_id' , "cat_id" , "service_id" , "quantity", "price","description",'gender','gender_percent']);
        $product = Product::where("id" , $id)->first();

        foreach ($items as $key => $value)
        {
            $product->{$key} = $items[$key];
        }

        $product->save();

        return response()->json($this->prepareResponse($product->id, "Changes Done"));

    }

    public function destroy($id)
    {

        $product = Product::where("id" , $id)->first();
        $product->delete();

        return response()->json($this->prepareResponse($product , $product ? "Successfully saved" : "Cannot Delete Item" , false));


    }
}
