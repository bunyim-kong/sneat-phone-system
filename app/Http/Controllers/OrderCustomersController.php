<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderCustomersController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand','series','color','storage'])
            ->where('status', 1);

        // Filter only call brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        $products = $query->latest()->get();

        //So here it remove walk-in customer and only add customer that have loan
        $customers = Customer::select('id', 'name', 'phone')->get();

        $brands = Brand::all();

        return view('ordercustomers.index', compact(
            'products',
            'customers',
            'brands'
        ));
    }

    public function store(Request $request)
    {
        // Calculate total price from the dynamic hidden input elements array
        $calculatedTotal = collect($request->items)->sum('price');

        DB::transaction(function () use ($request, $calculatedTotal) {
            // Save parent order record
            $order = Order::create([
                'customer_id'    => $request->customer_id ?: null,
                'employee_id'    => Auth::id(),
                'status'         => Order::STATUS_ACTIVE,
                'total_amount'   => $calculatedTotal,
                'payment_status' => $request->payment_status ?? 1,
                'payment_type'   => $request->payment_type ?? 1,
                'order_date'     => now(),
            ]);

            // Loop through selected phones to set their status to 'Sold' (Status 2)
            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    // Pull the correct inner product_id value key from the array dictionary
                    Product::where('id', $item['product_id'])->update(['status' => 2]);
                }
            }
        });

        // Redirect back to index with a flash alert message banner
        return redirect()->route('sales.index', withLang())->with('success', 'Order processed successfully!');
    }
}
