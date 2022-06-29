<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with('category')->get();
           // dd($products);
            return Datatables::of($products)
                ->addIndexColumn()
                ->addColumn('product_name',function($products){
                    return isset($products->name)? $products->name :'-' ;
                })
                ->addColumn('category',function($products){
                    return isset($products['category'])? $products['category']['name'] :'-' ; ;
                })
                ->editColumn('price', function($products){
                    return isset($products->price)? $products->price :'-' ;
                })
                ->addColumn('action',function($products){
                    $actionBtn = '<a href="'.route('admin.products.edit',['product'=>$products->id]).'" class="edit btn-lg"><i class="fa fa-edit"></i></a>';
                    $actionBtn .= '<a href="javascript:void(0)" data-sid="'.$products->id.'" class="delete btn btn-danger btn-sm newtrash"><i class="fa fa-trash"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categries = Category::get();
        return view('admin.product.create', compact('categries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $productImagePath = '';
        if($request->hasFile('image')){
            $file = $request->file('image');
            $productimage = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/products/images/'), $productimage);
            $productImagePath = 'products/images/'.$productimage;

        }else{
            $productImagePath = null;
        }
        $data = array(
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'image' => $productImagePath,
            'category_id' => $request->input('category_id'),
            'price' => floatval($request->input('price')),
        );
        $product = Product::create($data);

        return redirect()
        ->route('admin.products.index')
        ->with('success', 'Product '.$request->input('name').' added succesfully.');
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
