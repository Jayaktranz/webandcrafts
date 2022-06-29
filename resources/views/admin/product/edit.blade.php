@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 float-right">
            <a href="{{ route('admin.products.index') }}" class="btn btn-success float-right">Back to products</a>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Product ') }}
                    <h4><strong>{{ $product_details->name}}</strong></h4>
                </div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.products.update', ['product'=>$product_details->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name')?? $product_details->name }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="image" class="col-md-4 col-form-label text-md-end">{{ __('Product Image') }}</label>

                            <div class="col-md-6">
                                <input id="product_image" type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror" onchange="previewFile(this);">

                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <img id="previewImg" 
                                 src="{{ url($product_details->image)}}" 
                                 width="70" height="70"
                                 alt="Image Preview" />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="category_id" class="col-md-4 col-form-label text-md-end">{{ __('Category') }}</label>

                            <div class="col-md-6">
                                <select id="category_id" name="category_id"  class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach ($categries as $c)
                                    <option value={{ $c['id'] }} @if($c['id'] == $product_details->category->id) selected @endif>{{ $c['name'] }}</option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                    <span class="invalid-feedback" style="display:block !important;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="price" class="col-md-4 col-form-label text-md-end">{{ __('Price (In INR)') }}</label>

                            <div class="col-md-6">
                                <input id="price" type="text" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price')?? $product_details->price }}">

                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Product') }}
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
function previewFile(input){
            if ($("input[type=file]")[0].files.length === 0) {
                $("#previewImg").attr("src", '');
            } else {
            var file = $("input[type=file]").get(0).files[0];
            if(file){
            var reader = new FileReader();
            reader.onload = function(){
            $("#previewImg").attr("src", reader.result);
            }
             reader.readAsDataURL(file);
             }
           }
    }
    </script>
@endpush
