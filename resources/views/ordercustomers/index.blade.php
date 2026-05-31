<!DOCTYPE html>
<html lang="en" x-data="{ showBrands: false }">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order System</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-slate-100 font-sans h-screen flex flex-col overflow-hidden">

<div class="flex flex-1 overflow-hidden">

  <!-- left sidebar -->
  <aside class="w-20 bg-white border-r border-gray-200 flex flex-col items-center py-4 gap-4 flex-shrink-0">

     <div class="app-brand justify-content-center">
          <img src="{{ $company->image_logo }}" alt="logo"  class="w-8 h-auto"/>
    </div>

    
    
    <!-- Search bar -->
    <input type="hidden" name="search" value="{{ request('search') }}">

    <!-- phone brand -->
    <button
      @click="showBrands = !showBrands"
      class="w-16 h-16 flex flex-col items-center justify-center rounded-xl bg-blue-600 text-white text-xs font-semibold gap-1"
    >
      <i class="fa fa-mobile"></i>
      Brand
    </button>

    
    <div x-show="showBrands" x-transition class="flex flex-col gap-2 w-full px-2 mt-2">

      @foreach($brands as $brand)
        <a href="?brand_id={{ $brand->id }}&search={{ request('search') }}">
          <button type="button"
            class="w-full bg-gray-100 hover:bg-blue-500 hover:text-white text-[10px] py-2 rounded-lg transition">
            {{ $brand->name }}
          </button>
        </a>
      @endforeach

    </div>

  </aside>

  <!-- all the product -->
  <main class="flex-1 bg-slate-50 p-6 overflow-y-auto">

    <!-- SEARCH BAR -->
    <form method="GET" class="mb-4">
      <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search product / IMEI..."
        class="w-full px-4 py-2 rounded-lg border bg-white"
      >
    </form>

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

              <button class="px-3 py-1 bg-blue-600 text-white text-xs rounded-lg">
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

  <!-- right cart -->
  <aside class="w-80 bg-white border-l border-gray-200 flex flex-col justify-between">

    <!-- Customer -->
    <div class="p-4 border-b">
      <h2 class="text-sm font-bold text-gray-500 mb-2">CUSTOMER</h2>

      <select class="w-full bg-slate-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700">
        <option value="" disabled selected>Select Customer</option>

        @foreach($customers as $id => $name)
          <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
      </select>
    </div>

    <!-- cart -->
    <div class="flex-1 flex items-center justify-center text-gray-400">
      No items in order
    </div>

    <!-- total -->
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