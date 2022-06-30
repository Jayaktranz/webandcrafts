@if(isset($selectedProductDatas) && !empty($selectedProductDatas))
    <div class="container mb-4">
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col"> </th>
                            <th scope="col">Selected Product</th>
                            <th scope="col" class="text-center">Quantity</th>
                            <th scope="col" class="text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($selectedProductDatas as $prods)
                        @php 
                         $currentQuntity = isset($prods['Quantity'])? (int)$prods['Quantity']:1;
                         $amount = $prods['price'] * $currentQuntity;
                         $total += $amount;
                        @endphp
                        <tr>
                            <td><img src="{{ asset($prods['image'])}}" 
                                width="50" height="50"
                                /> 
                            </td>
                            <td>{{$prods['name']}}</td>
                            <td>
                            <input class="form-control quantityCheck" name="quantity[]" type="number" min="1" value="{{ $currentQuntity}}" />
                            <input type="hidden" class="prodVal" name="prodid[]" value="{{$prods['id']}}"/>
                            </td>
                            <td class="text-right">INR {{ number_format($amount, 2)}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><strong>Total</strong></td>
                            <td class="text-right"><strong>INR {{ number_format($total, 2)}}</strong></td>
                        </tr>
                        {{-- <tr>
                            <td><img src="https://dummyimage.com/50x50/55595c/fff" /> </td>
                            <td>Product Name Dada</td>
                            <td><input class="form-control" type="number" value="1" /></td>
                            <td class="text-right">124,90 €</td>
                        </tr>
                        <tr>
                            <td><img src="https://dummyimage.com/50x50/55595c/fff" /> </td>
                            <td>Product Name Toto</td>
                            <td><input class="form-control" type="number" value="1" /></td>
                            <td class="text-right">33,90 €</td>
                        </tr> --}}
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
