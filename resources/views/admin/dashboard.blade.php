@extends('layouts.app')
@section('content')
<style>
    a{
        text-decoration: none;
        color: #fff;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
<div class="container">
    <div class="row">
        <div class="col-md-6 col-xl-6">
            <div class="card card bg-c-pink order-card order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Orders</h6>
                    <a href="{{ route('admin.orders.index') }}">
                    <h2 class="text-right"><i class="fa fa-cart-plus f-left"></i>
                    <span>{{$orders_count}}</span>
                    </h2>
                    </a>
                    <p class="m-b-0">
                        <a href="{{ route('admin.orders.index') }}">Manage Orders</a>
                        <span class="f-right">{{$orders_count}}</span></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-xl-6">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Products</h6>
                    <a href="{{ route('admin.products.index') }}">
                    <h2 class="text-right"><i class="fa fa-plus f-left"></i>
                    <span>{{$products_count}}</span>
                    </h2>
                    </a>
                    <p class="m-b-0">
                    <a href="{{ route('admin.products.index') }}">Manage Products</a>
                    <span class="f-right">{{$products_count}}</span>
                    </p>
                </div>
            </div>
        </div>
        
	</div>
</div>

        </div>
    </div>
</div>
@endsection
