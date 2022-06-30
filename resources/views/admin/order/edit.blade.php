@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 float-right">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-success float-right">Back to orders</a>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Order') }}
                    <h4><strong>{{ $order->order_id}}</strong></h4>
                </div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.orders.update', ['order'=>$order->order_id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="customer_fname" class="col-md-4 col-form-label text-md-end">{{ __('Customer Firstname') }}</label>

                            <div class="col-md-6">
                                <input id="customer_fname" type="text" class="form-control @error('customer_fname') is-invalid @enderror" name="customer_fname" value="{{ old('customer_fname')?? $order->customer_fname }}" required autocomplete="customer_fname" autofocus>

                                @error('customer_fname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="customer_lname" class="col-md-4 col-form-label text-md-end">{{ __('Customer Lastname') }}</label>

                            <div class="col-md-6">
                                <input id="customer_lname" type="text" class="form-control @error('customer_lname') is-invalid @enderror" name="customer_lname" value="{{ old('customer_lname')?? $order->customer_lname }}" required autocomplete="customer_lname" autofocus>

                                @error('customer_lname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-end">{{ __('Phone Number') }}</label>

                            <div class="col-md-6">
                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number')?? $order->phone_number }}">

                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="product_lists" class="col-md-4 col-form-label text-md-end">{{ __('Select Products') }}</label>

                            <div class="col-md-6">
                                <select id="product_lists" name="product_lists[]"  class="js-example-basic-multiple form-control" multiple>
                                    @foreach($products as $product)
                                    <option value="{{$product['id']}}">{{ $product['name'].' ('.$product['category']['name'].')' }}</option>
                                    @endforeach
                                </select>

                                @error('product_lists')
                                    <span class="invalid-feedback" style="display:block !important;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3 selectedProductLists">
                         @include('admin.order.selected_order')
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Order') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
var selected_products =  @json($selected_products);  
$(document).ready(function() {
    $.ajaxSetup({
         headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#product_lists').select2({
        placeholder: "Select Products",
    }).val(selected_products)
    .trigger('change.select2')
    .on("change", function(e) {
        var productItems= $(this).val(); 
        $.ajax({
               'url':"{{ route('admin.selectedprods') }}",
                        'type':'POST',
                        'data':{ 'productItems':productItems},
                        'dataType':'JSON',
                        success: function(response){
                          if(response.status === 'success')
                          {
                              $('div.selectedProductLists').html(response.view);   
                          }  
                        },
                        error: function (xhr) {
                         console.log(xhr.responseText);
                        }
                    });
        
    });

    $(document).on('change', '.quantityCheck', function(e){
        var SelectedProductQuantityVal = $(this).val();
        var SelectedProductVal = $(this).next('input.prodVal').val();
        var AllProductVal= new Array();
        var AllProductQuantityVal= new Array();
       $('input[name^="prodid"]').each(function() 
       {  AllProductVal.push($(this).val());  }
       );
       $('input[name^="quantity"]').each(function() 
       {  AllProductQuantityVal.push($(this).val());  }
       );

       $.ajax({
               'url':"{{ route('admin.changeQuantity') }}",
                        'type':'POST',
                        'data':{ 
                            'SelectedProductVal':SelectedProductVal,
                            'SelectedProductQuantityVal':SelectedProductQuantityVal,
                            'AllProductVal':AllProductVal,
                            'AllProductQuantityVal':AllProductQuantityVal
                         },
                        'dataType':'JSON',
                        success: function(response){
                          if(response.status === 'success')
                          {
                              $('div.selectedProductLists').html(response.view);   
                          }  
                        },
                        error: function (xhr) {
                         console.log(xhr.responseText);
                        }
                });
    });
});
</script>  
@endpush