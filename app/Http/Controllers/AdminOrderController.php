<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::with('products')->get();
           // dd($products);
            return Datatables::of($orders)
                ->addIndexColumn()
                ->addColumn('customer_name',function($orders){
                    return  ucfirst($orders->customer_fname).' '.ucfirst($orders->customer_lname);
                })
                ->addColumn('phone',function($orders){
                    return $orders->phone_number;
                })
                ->addColumn('netamount',function($orders){
                    return'INR '.number_format( $orders->total_amount, 2);
                })
                ->editColumn('order_date', function($orders){
                    return isset($orders->order_date)? Carbon::parse($orders->order_date)->format('d M Y') :'-' ;
                })
                ->addColumn('action',function($orders){
                    $actionBtn = '<a href="'.route('admin.orders.edit',['order'=>$orders->order_id]).'" class="edit  btn-lg"><i class="fa fa-edit"></i></a>';
                    $actionBtn .= '<a href="javascript:void(0)" data-sid="'.$orders->order_id.'" class="delete btn btn-danger btn-sm newtrash"><i class="fa fa-trash"></i></a>';
                    $actionBtn .= '<a href="'.route('admin.orders.show',['order'=>$orders->order_id]).'" target="_blank" class="edit  btn-lg"><i class="fa fa-file"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.order.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::with('category')->get();
        return view('admin.order.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        $products = (array)$request->input('prodid');
        $quantities = (array)$request->input('quantity');
        $row = Order::latest()->first();
        $OrderId = 'WAC';
        $OrderId.= str_pad((isset($row) ?intval($row->id+1):1 ), 8, '0', STR_PAD_LEFT);
        $amount = 0;
        $synArr = [];
        for($i=0; $i<count($products); $i++){
            $p = Product::find($products[$i]);
            $amount += $p->price *$quantities[$i];
            $synArr[$products[$i]] = array("quantity"=>$quantities[$i]);
        }
        $data = [
            'customer_fname' => $request->input('customer_fname'),
            'customer_lname' => $request->input('customer_lname'),
            'order_id ' => $OrderId,
            'phone_number' => $request->input('phone_number'),
            'order_date' => now(),
            'total_amount' => $amount, 
        ];
           // dd($data);
        $order = new Order;
        $order->customer_fname = $data['customer_fname'];
        $order->customer_lname = $data['customer_lname'];
        $order->order_id =  $OrderId;
        $order->phone_number =  $data['phone_number'];
        $order->order_date =  $data['order_date'];
        $order->total_amount =  $amount;
        $order->save();
        $order = $order->products()->sync($synArr);

        return redirect()
        ->route('admin.orders.index')
        ->with('success', 'Order '.$OrderId.' added succesfully.');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with('products')->where('order_id', $id)->first();
        return view('admin.order.view_invoice', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $order = Order::with('products')->where('order_id', $id)->first();
       $products = Product::with('category')->get();
       $selected_products = $order->products->pluck('id')->toArray();
       $selectedProductDatas = $order->products->toArray();
            foreach($selectedProductDatas as $k=>$sele){ 
                $selectedProductDatas[$k]['Quantity'] = $selectedProductDatas[$k]['pivot']['quantity'];
        }
        return view('admin.order.edit', compact('order','products','selected_products','selectedProductDatas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, $id)
    {
        // dd($request->all());
        $order = Order::where('order_id', $id)->first();
        $products = (array)$request->input('prodid');
        $quantities = (array)$request->input('quantity');
        $amount = 0;
        $synArr = [];
        for($i=0; $i<count($products); $i++){
            $p = Product::find($products[$i]);
            $amount += $p->price *$quantities[$i];
            $synArr[$products[$i]] = array("quantity"=>$quantities[$i]);
        }
        $data = [
            'customer_fname' => $request->input('customer_fname'),
            'customer_lname' => $request->input('customer_lname'),
            'phone_number' => $request->input('phone_number'),
            'total_amount' => $amount, 
        ];
           // dd($data);
        $order->customer_fname = $data['customer_fname'];
        $order->customer_lname = $data['customer_lname'];
        $order->phone_number =  $data['phone_number'];
        $order->total_amount =  $amount;
        $order->save();
        $order = $order->products()->sync($synArr);

        return redirect()
        ->route('admin.orders.index')
        ->with('success', 'Details of order '.$id.' updated succesfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if ($request->ajax()) {
            $validator=Validator::make($request->all(),[
                'statsid' => 'required|exists:orders,order_id',
            ],[ ]);
            if($validator->fails()){
                return response()->json(['status'=>'invalid']);
            }else{
                 $order = Order::where('order_id', $id)->first();   
                 //$order->products()->detach();
                 $order->delete();
                 return response()->json(['status' => 'success']);
            }
        }
    }

    public function getOrderSelectedProducts(Request $request){
        if ($request->ajax()) {
           
            $selectedProds = $request->input('productItems');
            $selectedProductDatas = Product::whereIn('id', (array) $selectedProds)
                                    ->get()->toArray();
            //$seSSArr= array();
            // foreach($selectedProductDatas as $k=>$sele){
            //     $c['ProdId'] = $sele['id'];
            //     $c['Quantity'] = 1;
            //     array_push($seSSArr, $c);
            //     // if($request->session()->has('myCartBase')){
            //     //     $seSSArr = $request->session()->get('myCartBase');
            //     //     if(!in_array($sele['id'], array_column($seSSArr, 'ProdId'))){
            //     //         $c['ProdId'] = $sele['id'];
            //     //         $c['ProdId'] = $sele['id'];

            //     //     }

            //     // }

            // }  
            // $request->session()->put('myCartBase', $seSSArr);                     
            $view = view('admin.order.selected_order', compact('selectedProductDatas'))->render();

            return response()->json([
                'status' => 'success',
                'view' => $view
            ]);
        }
    }

    public function changeQuantity(Request $request){
        if ($request->ajax()) {
            $selectedProds = $request->input('AllProductVal');
            $selectedProdQuantity = $request->input('AllProductQuantityVal');
            $selectedProductDatas = Product::whereIn('id', (array) $selectedProds)
                                    ->get()->toArray();
            foreach($selectedProductDatas as $k=>$sele){ 
                $selectedProductDatas[$k]['Quantity'] = $selectedProdQuantity[$k];
            }
            $view = view('admin.order.selected_order', compact('selectedProductDatas'))->render();

            return response()->json([
                'status' => 'success',
                'view' => $view
            ]);                    

        }
    }
}
