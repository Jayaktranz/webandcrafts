@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 float-right">
            <a href="{{ route('admin.orders.create') }}" class="btn btn-success float-right">Add Order</a>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12 ">
                    @include('layouts.inc.messages')
                </div>
            </div>
            <div class="row">
                <h3 style="text-align: center">Orders</h3>
            </div>
            <div class="card mb-4 mlb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="datatable" width="100%" cellspacing="0">
                            <thead>
                                <th>Sl.No</th>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Phone</th>
                                <th>Net Amount</th>
                                <th>Order Date</th>
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
        ajax: "{{ route('admin.orders.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'order_id', name: 'order_id'},
            {data: 'customer_name', name: 'customer_name'},
            {data: 'phone', name: 'phone'},
            {data: 'netamount', name: 'netamount'},
            {data: 'order_date', name: 'order_date'},
            {data: 'action', name: 'action', orderable: true, searchable: true
            },
        ] ,
        "fnDrawCallback": function(){
            $('.newtrash').on("click",function(){
                $statsID=$(this).data('sid');
                if(confirm('Are you sure want to delete this order ?')){
                    $url='{{ route("admin.orders.destroy",":id") }}';
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
    });

    </script>
@endpush
