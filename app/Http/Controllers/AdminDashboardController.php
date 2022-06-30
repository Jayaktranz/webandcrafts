<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Order;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        $products_count = Product::get()->count();
        $orders_count = Order::get()->count();
        return view('admin.dashboard', compact('orders_count', 'products_count'));
    }
}
