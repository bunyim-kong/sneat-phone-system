@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <!-- Left: Product Search & Cart -->
    <div class="col-md-7">
      <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">{{ __('sidebar.shop.orders.title') }}</h5>
          <span class="badge bg-label-primary" id="cart-count">0 items</span>
        </div>
        <div class="card-body">
          <!-- Search -->
          <div class="mb-3">
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-search"></i></span>
              <input type="text" id="product-search" class="form-control" placeholder="Search by IMEI, name, code...">
            </div>
          </div>
          <!-- Product Results -->
          <div id="product-results" class="row g-2 mb-3" style="max-height:320px; overflow-y:auto;">
            <div class="col-12 text-center text-muted py-4">
              <i class="bx bx-search-alt fs-1"></i>
              <p class="mt-2">Search for a product to add</p>
            </div>
          </div>
          <hr>
          <!-- Cart Table -->
          <h6 class="mb-3">Cart</h6>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Product</th>
                  <th>IMEI</th>
                  <th>Price ($)</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="cart-body">
                <tr id="cart-empty-row">
                  <td colspan="5" class="text-center text-muted py-3">No items in cart</td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="table-light fw-bold">
                  <td colspan="3" class="text-end">Total:</td>
                  <td id="cart-total">$0.00</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Right: Order Info & Submit -->
    <div class="col-md-5">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Order Details</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('sales.store', withLang()) }}" method="POST" id="sale-form">
            @csrf

            <!-- Hidden product ids -->
            <div id="product-ids-container"></div>

            <!-- Customer -->
            <div class="mb-3">
              <label class="form-label">{{ __('customer.menu.title') }}</label>
              <select name="customer_id" class="form-select" id="customer-select">
                <option value="">-- Walk-in Customer --</option>
                @foreach($customers as $customer)
                  <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                @endforeach
              </select>
            </div>

            <!-- Order Date -->
            <div class="mb-3">
              <label class="form-label">Order Date</label>
              <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <!-- Payment Type -->
            <div class="mb-3">
              <label class="form-label">Payment Type</label>
              <select name="payment_type" class="form-select" required>
                <option value="1">Cash</option>
                <option value="2">Bank</option>
                <option value="3">Other</option>
              </select>
            </div>

            <!-- Payment Status -->
            <div class="mb-3">
              <label class="form-label">Payment Status</label>
              <select name="payment_status" class="form-select" required>
                <option value="1">Paid</option>
                <option value="2">Unpaid</option>
              </select>
            </div>

            <!-- Discount -->
            <div class="mb-3">
              <label class="form-label">Discount ($)</label>
              <input type="number" name="discount" id="discount" class="form-control" value="0" min="0" step="0.01">
            </div>

            <!-- Note -->
            <div class="mb-3">
              <label class="form-label">Note</label>
              <textarea name="note" class="form-control" rows="2"></textarea>
            </div>

            <!-- Summary -->
            <div class="alert alert-light border mb-3">
              <div class="d-flex justify-content-between">
                <span>Subtotal:</span>
                <strong id="summary-subtotal">$0.00</strong>
              </div>
              <div class="d-flex justify-content-between">
                <span>Discount:</span>
                <strong id="summary-discount">$0.00</strong>
              </div>
              <hr class="my-2">
              <div class="d-flex justify-content-between fs-5">
                <span>Grand Total:</span>
                <strong id="summary-total" class="text-primary">$0.00</strong>
              </div>
              <!-- Hidden total sent to server -->
              <input type="hidden" name="total_amount" id="input-total">
            </div>

            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                <i class="bx bx-cart-alt me-1"></i> Place Order
              </button>
              <a href="{{ route('sales.index', withLang()) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  let cart = []; // { id, name, imei, price }

  // ── Product Search ──────────────────────────────────────────
  let searchTimeout;
  document.getElementById('product-search').addEventListener('input', function () {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    if (q.length < 2) {
      document.getElementById('product-results').innerHTML = `
        <div class="col-12 text-center text-muted py-4">
          <i class="bx bx-search-alt fs-1"></i>
          <p class="mt-2">Search for a product to add</p>
        </div>`;
      return;
    }
    searchTimeout = setTimeout(() => searchProducts(q), 300);
  });

  function searchProducts(q) {
    document.getElementById('product-results').innerHTML = `
      <div class="col-12 text-center py-3">
        <div class="spinner-border spinner-border-sm text-primary"></div> Searching...
      </div>`;

    fetch(`{{ url('/') }}/{{ app()->getLocale() }}/products/search?q=${encodeURIComponent(q)}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => renderProductResults(data))
    .catch(() => {
      document.getElementById('product-results').innerHTML =
        `<div class="col-12 text-danger">Error loading products.</div>`;
    });
  }

  function renderProductResults(products) {
    const container = document.getElementById('product-results');
    if (!products.length) {
      container.innerHTML = `<div class="col-12 text-center text-muted py-3">No available products found.</div>`;
      return;
    }
    container.innerHTML = products.map(p => `
      <div class="col-12">
        <div class="d-flex align-items-center justify-content-between border rounded px-3 py-2 mb-1 bg-light">
          <div>
            <div class="fw-semibold">${p.product_name}</div>
            <small class="text-muted">IMEI: ${p.product_imei ?? '-'} &nbsp;|&nbsp; $${parseFloat(p.selling_price).toFixed(2)}</small>
          </div>
          <button type="button" class="btn btn-sm btn-primary" onclick="addToCart(${p.id}, '${escHtml(p.product_name)}', '${escHtml(p.product_imei ?? '')}', ${p.selling_price})">
            <i class="bx bx-plus"></i>
          </button>
        </div>
      </div>
    `).join('');
  }

  // ── Cart Logic ───────────────────────────────────────────────
  function addToCart(id, name, imei, price) {
    if (cart.find(i => i.id === id)) {
      alert('This product is already in the cart.');
      return;
    }
    cart.push({ id, name, imei, price: parseFloat(price) });
    renderCart();
  }

  function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
  }

  function renderCart() {
    const tbody = document.getElementById('cart-body');
    const emptyRow = document.getElementById('cart-empty-row');

    if (!cart.length) {
      tbody.innerHTML = `<tr id="cart-empty-row"><td colspan="5" class="text-center text-muted py-3">No items in cart</td></tr>`;
      updateSummary(0);
      updateHiddenInputs();
      document.getElementById('submit-btn').disabled = true;
      document.getElementById('cart-count').textContent = '0 items';
      return;
    }

    tbody.innerHTML = cart.map((item, idx) => `
      <tr>
        <td>${idx + 1}</td>
        <td>${item.name}</td>
        <td><small>${item.imei || '-'}</small></td>
        <td>
          <input type="number" class="form-control form-control-sm" style="width:100px"
            value="${item.price.toFixed(2)}" min="0" step="0.01"
            onchange="updatePrice(${item.id}, this.value)">
        </td>
        <td>
          <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.id})">
            <i class="bx bx-trash"></i>
          </button>
        </td>
      </tr>
    `).join('');

    const subtotal = cart.reduce((s, i) => s + i.price, 0);
    updateSummary(subtotal);
    updateHiddenInputs();
    document.getElementById('submit-btn').disabled = false;
    document.getElementById('cart-count').textContent = `${cart.length} item${cart.length > 1 ? 's' : ''}`;
  }

  function updatePrice(id, value) {
    const item = cart.find(i => i.id === id);
    if (item) item.price = parseFloat(value) || 0;
    const subtotal = cart.reduce((s, i) => s + i.price, 0);
    updateSummary(subtotal);
    updateHiddenInputs();
  }

  function updateSummary(subtotal) {
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const grand = Math.max(0, subtotal - discount);
    document.getElementById('cart-total').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('summary-subtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('summary-discount').textContent = `$${discount.toFixed(2)}`;
    document.getElementById('summary-total').textContent = `$${grand.toFixed(2)}`;
    document.getElementById('input-total').value = grand.toFixed(2);
  }

  function updateHiddenInputs() {
    const container = document.getElementById('product-ids-container');
    container.innerHTML = cart.map(item =>
      `<input type="hidden" name="product_ids[]" value="${item.id}">
       <input type="hidden" name="unit_prices[]" value="${item.price.toFixed(2)}">`
    ).join('');
  }

  // Recalculate when discount changes
  document.getElementById('discount').addEventListener('input', function () {
    const subtotal = cart.reduce((s, i) => s + i.price, 0);
    updateSummary(subtotal);
  });

  // ── Form Validation ──────────────────────────────────────────
  document.getElementById('sale-form').addEventListener('submit', function (e) {
    if (!cart.length) {
      e.preventDefault();
      alert('Please add at least one product to the cart.');
    }
  });

  function escHtml(str) {
    return String(str).replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[s]));
  }
</script>
@endpush