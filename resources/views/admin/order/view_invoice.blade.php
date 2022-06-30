<!DOCTYPE html>
<html>

<head>
	<title>Order Invoice</title>
	<style>
		table,
		th,
		td {
			border: 1px solid black;
			border-collapse: collapse;
			padding: 6px;
            padding-right:40px; 
		}
        
	</style>
</head>

<body style="text-align:center">

	<h1 style="color: rgb(61, 50, 128);">INVOICE</h1>
	<h2>ORDER #{{$order->order_id}}</h2>
	<center>
		<table>
			<tr>
				<td>
                    <b>Order ID</b>
                </td>
				<td >{{ $order->order_id }}</td>
			</tr>
            <tr>
				<td>
                    <b>Customer Name</b>
                </td>
				<td >{{ ucfirst($order->customer_fname).' '.ucfirst($order->customer_lname)}}</td>
			</tr>
			<tr>
				<td>
                    <b>Products</b>
                </td>
				<td >
                    @foreach($order->products as $k=>$product)
                    <span>{{$k+1 }}. {{$product->name}} X {{$product->pivot->quantity}} = <b>{{number_format(($product->pivot->quantity * $product->price), 2)}}</b></span>
                    <br/>
                    @endforeach
                </td>
			</tr>
			<tr>
				<td>
                    <b>Total</b>
                </td>
				<td >
                    <b>INR {{ number_format($order->total_amount, 2)}}</b>
                </td>
			</tr>
		</table>
	</center>

</body>

</html>
