<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;

class OrderCustomersController extends Controller
{
    public function index()
    {
        $customers = Customer::pluck('name', 'id');

        $orders = Order::orderBy('order_date', 'desc')->paginate(20);

        $products = Product::latest()->get();

        $parameterNames = [];

        return view('ordercustomers.index', compact(
            'orders',
            'customers',
            'products',
            'parameterNames'
        ));
    }
}