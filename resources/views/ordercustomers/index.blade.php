<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-slate-100 font-sans h-screen flex flex-col overflow-hidden">

<div class="flex flex-1 overflow-hidden">

  <!-- LEFT SIDEBAR -->
  <aside class="w-20 bg-white border-r border-gray-200 flex flex-col items-center py-4 gap-4 flex-shrink-0">

    <div class="w-12 h-12 bg-amber-500 rounded-lg flex items-center justify-center text-white font-bold text-xl mb-4 shadow-sm">
      <i class="fa-brands fa-apple"></i>
    </div>

    <button class="w-16 h-16 flex flex-col items-center justify-center rounded-xl bg-blue-600 text-white text-xs font-semibold gap-1">
      <i class="fa fa-search"></i>
      Search
    </button>

    <button class="w-16 h-16 flex flex-col items-center justify-center rounded-xl bg-blue-600 text-white text-xs font-semibold gap-1">
      <i class="fa fa-mobile"></i>
      Phones
    </button>

  </aside>

  <!-- MAIN PRODUCTS -->
  <main class="flex-1 bg-slate-50 p-6 overflow-y-auto">

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

      @forelse($products as $product)

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition-all">

          <div class="h-48 bg-gray-100 overflow-hidden">
            <img
              src="{{ $product->image_name }}"
              class="object-cover h-full w-full group-hover:scale-105 transition-transform"
            >
          </div>

          <div class="p-4 flex flex-col flex-1">

            <h3 class="font-bold text-gray-800 text-sm">
              {{ $product->product_name }}
              <span class="text-xs text-gray-500">
                [ IMEI: {{ $product->product_imei }} ]
              </span>
            </h3>

            <p class="text-xs text-gray-400 mt-1">
              {{ $product->condition_name }},
              {{ $product->series_name ?? '' }},
              {{ $product->storage->name ?? '' }},
              {{ $product->color->name ?? '' }}
            </p>

            <div class="mt-auto pt-3 flex items-center justify-between">
              <span class="text-base font-bold text-gray-900">
                ${{ $product->selling_price }}
              </span>

              <button
                class="px-3 py-1 bg-blue-600 text-white text-xs rounded-lg"
              >
                Add
              </button>
            </div>

          </div>
        </div>

      @empty

        <p class="text-gray-500">No products found</p>

      @endforelse

    </div>

  </main>

  <!-- RIGHT CART -->
  <aside class="w-80 bg-white border-l border-gray-200 flex flex-col justify-between">

    <div class="p-4 border-b">
      <h2 class="text-sm font-bold text-gray-500">CUSTOMER</h2>

      <select class="w-full mt-2 border rounded-lg p-2">
        <option>Select Customer</option>
        @foreach($customers as $id => $name)
          <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
      </select>
    </div>

    <div class="flex-1 flex items-center justify-center text-gray-400">
      No items in order
    </div>

    <div class="p-4 border-t bg-slate-50">
      <div class="flex justify-between mb-4">
        <span>Total</span>
        <span class="font-bold">$0</span>
      </div>

      <button class="w-full bg-blue-600 text-white py-3 rounded-xl">
        Submit Order
      </button>
    </div>

  </aside>

</div>

</body>
</html>