<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Color;
use App\Models\Customer;
use App\Models\ModelType;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Series;
use App\Models\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
  function __construct()
  {
      $this->middleware('auth');
      $this->middleware('permission:order-list|order-create|order-edit|order-delete', ['only' => ['index','store']]);
      $this->middleware('permission:order-create', ['only' => ['create','store']]);
      $this->middleware('permission:order-edit', ['only' => ['edit','update']]);
      $this->middleware('permission:order-delete', ['only' => ['destroy']]);
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {

    $parameterNames = [];
    $customers = Customer::pluck('name', 'id');

    $query = Order::query()->with(['customer', 'employee']);

    if ($request->search) {

        $filters = $request->only(['customer', 'from_date', 'to_date']);

        if (!empty($filters['customer'])) {
            $query->where('customer_id', $filters['customer']);
            $parameterNames['customer'] = $filters['customer'];
        }

        if (!empty($filters['from_date']) && !empty($filters['to_date'])) {
            // Both from_date and to_date are provided
            $query->whereBetween('order_date', [$filters['from_date'], $filters['to_date']]);
            $parameterNames['from_date'] = $filters['from_date'];
            $parameterNames['to_date'] = $filters['to_date'];
        } elseif (!empty($filters['from_date'])) {
            // Only from_date is provided
            $query->where('order_date', '>=', $filters['from_date']);
            $parameterNames['from_date'] = $filters['from_date'];
        } elseif (!empty($filters['to_date'])) {
            // Only to_date is provided
            $query->where('order_date', '<=', $filters['to_date']);
            $parameterNames['to_date'] = $filters['to_date'];
        }
    }

    $orders = $query->orderBy('order_date', 'desc')->paginate(20);
    session(['printInvoiceId' => null]);

    return view('orders.index', compact(
      'orders',
      'customers',
      'parameterNames'
    ));
  }
    // create function
    public function create()
{
    // 1. FIX CUSTOMER JSON: Pluck only the name and id columns to send a clean array string
    $customers = \App\Models\Customer::pluck('name', 'id');

    // 2. FIX PRODUCTS NOT SHOWING: Eager load relationships and filter by 'In stock' status
    // Note: We check for '1' or 1 based on your migration comment: "1:Instock"
    $products = \App\Models\Product::with(['storage', 'color'])
        ->where('status', 1)
        ->orWhere('status', '1')
        ->get();

    // Debugging Check (Optional):
    // If your dropdown is STILL empty, uncomment the line below to check if your database actually has in-stock products:
    // dd($products->toArray());

    // 3. Return the view with the variables matching your Blade file exactly
    return view('orders.create', compact('customers', 'products'));
}

    // store function
    public function store(Request $request)
{
    // 1. Sum up the prices directly from your dynamic HTML items array
    $calculatedTotal = collect($request->items)->sum('price');

    // 2. Create the order with proper fallback values
    $order = Order::create([
        'customer_id'    => $request->customer_id ?: null,
        'employee_id'    => Auth::id(),
        'status'         => Order::STATUS_ACTIVE,

        // FIXED: Use the calculated total instead of an empty string
        'total_amount'   => $calculatedTotal,

        // FIXED: Fallback to an integer value (like 1) instead of an empty string ''
        'payment_status' => $request->payment_status ?? 1,
        'payment_type'   => $request->payment_type ?? 1,

        'note'           => $request->note,

        // FIXED: Your HTML form input field is named 'sale_date'
        'order_date'     => $request->sale_date ?? now(),
    ]);

    // 3. Loop through items to mark selected phone records as 'Sold' (Status 2)
    if ($request->has('items')) {
        foreach ($request->items as $productId => $item) {
            Product::where('id', $productId)->update(['status' => 2]);

            OrderDetail::create([
              'order_id' => $order->id,
              'product_id' => $productId,
              'unit_price' => $item['price'] ?? 0,
            ]);
        }
    }

    return redirect()->route('sales.index', withLang())->with('success', 'Sale registered successfully!');
}

     /**
     * Display the specified resource.
     */
    public function show(string $lang, Order $order)
    {
        // Fix: Swap 'items' with 'orderDetails' to match your model definition
        $order->load(['orderDetails.product.storage', 'orderDetails.product.color', 'customer', 'employee']);

        return view('orders.show', compact('order'));
    }


    /**
     * * Display the specified resource.
     * */
    public function checkProductOrder(Request $request)
    {
        // Attach order details to the order
        foreach ($request->productIds as $key => $productId) {

            // Check if the product is available
            $product = Product::available()->find($productId);
            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }
        }
        return response()->json(['message' => 'Submiting Order'], 201);
    }

    public function destroy(string $lang, Order $order)
    {
        $productIds = $order->orderDetails->pluck('product_id')->filter();
        Product::whereIn('id', $productIds)->update(['status' => 1]);

        $order->delete();

        return redirect()->route('sales.index', withLang())->with('success', 'Sale deleted successfully');
    }

     /**
     * Display the specified resource.
     */
    public function invoice(string $lang, Order $order)
    {
        $order = $order->with('orderDetails', 'customer', 'employee')->findOrfail($order->id);
        $order_detals = OrderDetail::where('order_id', $order->id)->with('product')->get();
        return view('orders.invoice', compact('order', 'order_detals'));
    }

    public function invoicePdf(Request $request, string $lang, Order $order)
    {
      $currentDate = Carbon::now()->format('Y-m-d');
      $order = $order->with('orderDetails', 'customer', 'employee')->findOrfail($order->id);
      $order_detals = OrderDetail::where('order_id', $order->id)->with('product')->get();
      $file_pdf = 'invoice-'.str_pad($order->id, 5, '0', STR_PAD_LEFT).'.pdf';
      $type = $request->type ?? 'download';
      return view('orders.invoice-pdf', compact('order', 'order_detals', 'currentDate' ,'file_pdf', 'type'));
    }

    public function indexOrder(Request $request)
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

        return view('orders.indexOrder', compact(
            'products',
            'customers',
            'brands'
        ));
    }

    public function storeOrder(Request $request)
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

                    OrderDetail::create([
                      'order_id' => $order->id,
                      'product_id' => $item['product_id'],
                      'unit_price' => $item['price'] ?? 0,
                    ]);
                }
            }
        });

        // Redirect back to index with a flash alert message banner
        return redirect()->route('sales.index', withLang())->with('success', 'Order processed successfully!');
    }
}
