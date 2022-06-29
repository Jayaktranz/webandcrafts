<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
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
                    $actionBtn = '<a href="'.route('admin.products.edit',['product'=>$products->id]).'" class="edit  btn-lg"><i class="fa fa-edit"></i></a>';
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
      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product_details = Product::with('category')->find((int) $id);
        $categries = Category::get();
        if(isset($product_details)){
            
            return view('admin.product.edit', compact('categries','product_details'));

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $data = array();
        $productImagePath = '';
        $data = array(
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'category_id' => $request->input('category_id'),
            'price' => floatval($request->input('price')),
        );

        if($request->hasFile('image')){
            $file = $request->file('image');
            $productimage = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/products/images/'), $productimage);
            $productImagePath = 'products/images/'.$productimage;
            $data['image'] = $productImagePath;
        }
       
        $product = Product::where(['id' => $id])
        ->update($data);

        return redirect()
        ->route('admin.products.index')
        ->with('success', 'Product details of '.$request->input('name').' updated succesfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ($request->ajax()) {
            $validator=Validator::make($request->all(),[
                'statsid' => 'required|exists:products,id',
            ],[ ]);
            if($validator->fails()){
                return response()->json(['status'=>'invalid']);
            }else{
                 $product = Product::find((int) $id);   
                 $product->delete();
                 return response()->json(['status' => 'success']);
            }
        }
    }
}
