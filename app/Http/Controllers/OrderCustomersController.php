<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;

class OrderCustomersController extends Controller
{
    public function index(Request $request)
    {
        // Get available products for POS
        $products = Product::with(['brand', 'series', 'color', 'storage'])
            ->where('status', Product::STATUS_ID_AVAILABLE)
            ->latest()
            ->get();

        // Customers dropdown
        $customers = Customer::pluck('name', 'id');

        return view('ordercustomers.index', compact('products', 'customers'));
    }
}