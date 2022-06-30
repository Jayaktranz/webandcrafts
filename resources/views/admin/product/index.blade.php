@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 float-right">
            <a href="{{ route('admin.products.create') }}" class="btn btn-success float-right">Add Product</a>
            <button class="btn btn-danger float-right" id="categoryModalBtn">Add Category</button>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12 ">
                    @include('layouts.inc.messages')
                </div>
            </div>
            <div class="row">
                <h3 style="text-align: center">Product Lists</h3>
            </div>
            <div class="card mb-4 mlb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="datatable" width="100%" cellspacing="0">
                            <thead>
                                <th>Sl.No</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@include('admin.category.add_category')
@endsection
@push('custom-scripts')
    <script>
        $(function(){
        $.ajaxSetup({
         headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
        var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.products.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'product_name', name: 'product_name'},
            {data: 'category', name: 'category'},
            {data: 'price', name: 'price'},
            {data: 'action', name: 'action', orderable: true, searchable: true
            },
        ] ,
        "fnDrawCallback": function(){
            $('.newtrash').on("click",function(){
                $statsID=$(this).data('sid');
                if(confirm('Are you sure want to delete this product ?')){
                    $url='{{ route("admin.products.destroy",":id") }}';
                    $url=$url.replace(':id',$statsID);
                    $.ajax({
                        'url':$url,
                        'type':'DELETE',
                        'data':{ 'statsid':$statsID, '_token':'{{ csrf_token() }}' },
                        'dataType':'JSON',
                        success: function(reponse){
                         if(reponse.status === 'success')
                         table.draw();
                        },
                        error: function (xhr) {
                         console.log(xhr.responseText);
                        }
                    });
                }

            });
           
        },
    });

    $('#categoryModalBtn').on('click', function() {
      $('#categoryModal').modal('show');
    });

    $(document).on('click', '#addCategory', function(){
        $('#error_email').html('');
        var categoryName= $('#category_name').val();
        $.ajax({
               'url':"{{ route('admin.categories.store') }}",
                        'type':'POST',
                        'data':{ 'categoryName':categoryName},
                        'dataType':'JSON',
                        success: function(response){
                          if(response.status === 'success')
                          {
                            $('#category_name').val('');
                            $('#categoryModal').modal('hide');
                            window.location.reload;
                          }else if(response.status === 'invalid'){
                            $('#error_email').html('<p>'+ response.errors.categoryName[0] + '</p>');
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
