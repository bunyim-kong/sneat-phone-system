<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS Phone Management Layout</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 font-sans h-screen flex flex-col overflow-hidden">

  <div class="flex flex-1 overflow-hidden">
    
    <aside class="w-20 bg-white border-r border-gray-200 flex flex-col items-center py-4 gap-4 flex-shrink-0">
      <div class="w-12 h-12 bg-amber-500 rounded-lg flex items-center justify-center text-white font-bold text-xl mb-4 shadow-sm">
        <i class="fa-apple fab"></i>
      </div>
      
      <button class="w-16 h-16 flex flex-col items-center justify-center rounded-xl bg-blue-600 text-white text-xs font-semibold gap-1 shadow-md transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        <span>Search</span>
      </button>

      <button class="w-16 h-16 flex flex-col items-center justify-center rounded-xl bg-blue-600 text-white text-xs font-semibold gap-1 shadow-md transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
        <span>All Phones</span>
      </button>

      <button class="w-16 h-16 flex flex-col items-center justify-center rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-medium gap-1 transition-all mt-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.17c.66-.81 1.11-1.93.99-3.06-.96.04-2.13.64-2.82 1.45-.6.7-1.13 1.84-1.01 2.94 1.07.08 2.18-.52 2.84-1.33z"/></svg>
        <span class="text-[10px] uppercase font-bold tracking-wider text-gray-500">Apple</span>
      </button>
    </aside>

    <main class="flex-1 bg-slate-50 p-6 overflow-y-auto">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition-all">
          <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden">
            <img src="https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=400" alt="iPhone" class="object-cover h-full w-full group-hover:scale-105 transition-transform duration-200">
          </div>
          <div class="p-4 flex flex-col flex-1">
            <h3 class="font-bold text-gray-800 text-sm">iPhone 13 <span class="text-xs font-normal text-gray-500">[ IMEI: 9111 ]</span></h3>
            <p class="text-xs text-gray-400 mt-1">Used, iPhone 13, 256G, Black, Original</p>
            <div class="mt-auto pt-3 flex items-center justify-between">
              <span class="text-base font-bold text-gray-900">$295.00</span>
            </div>
          </div>
        </div>

       
      


        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col items-center justify-center p-6 min-h-[280px]">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <p class="text-sm font-medium text-gray-600">Product Image</p>
          <p class="text-xs text-gray-400">Coming Soon</p>
        </div>

      </div>
    </main>

    <aside class="w-80 bg-white border-l border-gray-200 flex flex-col flex-shrink-0 justify-between">
      <div class="p-4 border-b border-gray-100">
        <div class="flex justify-between items-center mb-4">
          <span class="text-xs font-bold text-gray-400 tracking-wider uppercase">??????</span>
          <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
        </div>
        
        <div class="relative">
          <select class="w-full bg-slate-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none cursor-pointer">
            <option>?????</option>
            <option>Walk-in Customer</option>
          </select>
          <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
          </div>
        </div>
      </div>

      <div class="flex-1 overflow-y-auto p-4 flex flex-col items-center justify-center text-gray-400">
        <p class="text-sm">No items in order</p>
      </div>

      <div class="p-4 border-t border-gray-100 bg-slate-50/50">
        <div class="flex justify-between items-baseline mb-4">
          <span class="text-sm font-medium text-gray-600">Total</span>
          <span class="text-2xl font-black text-gray-900">$ 0</span>
        </div>
        
        <div class="flex gap-2">
          <button class="px-4 py-3 bg-gray-200 text-gray-500 font-medium rounded-xl text-sm hover:bg-gray-300 transition-colors flex items-center justify-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            Bill
          </button>
          <button class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-md shadow-blue-200 transition-colors flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            Submit Order
          </button>
        </div>
      </div>
    </aside>

  </div>
</body>
</html>