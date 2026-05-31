<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Brand;
use Illuminate\Http\Request;

class OrderCustomersController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand','series','color','storage'])
            ->where('status', Product::STATUS_ID_AVAILABLE);

        // Filter only call brand
        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        $products = $query->latest()->get();

        //So here it remove walk-in customer and only add customer that have loan
        $customers = Customer::where('name', '!=', 'Walk-in Customer')
            ->pluck('name', 'id');

        $brands = Brand::all();

        return view('ordercustomers.index', compact(
            'products',
            'customers',
            'brands'
        ));
    }
}